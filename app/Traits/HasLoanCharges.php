<?php

namespace App\Traits;

use App\Models\Loan\LoanProduct;

/**
 * Reads the charges snapshot in `other_fees`, supporting both the legacy flat shape
 * {"fees_and_charges":0.095,"late_payment":0.05} and the product shape
 * {"fees_and_charges":{"enabled":..,"type":..,"value":..},"penalty":{...}}.
 */
trait HasLoanCharges
{
    public function normalizedCharges(): array
    {
        $raw = is_array($this->other_fees) ? $this->other_fees : json_decode($this->other_fees ?? '', true);
        $raw = is_array($raw) ? $raw : [];

        $normalize = function ($value) {
            if (is_array($value)) {
                return [
                    'enabled' => (bool) ($value['enabled'] ?? true),
                    'type'    => $value['type'] ?? 'percentage',
                    'value'   => (float) ($value['value'] ?? 0),
                ];
            }
            if ($value === null) {
                return null;
            }
            return ['enabled' => true, 'type' => 'percentage', 'value' => (float) $value];
        };

        return [
            'fees_and_charges' => $normalize($raw['fees_and_charges'] ?? null),
            'penalty'          => $normalize($raw['penalty'] ?? $raw['late_payment'] ?? null),
        ];
    }

    public function getFeeChargesAttribute(): string
    {
        return LoanProduct::chargeLabel($this->normalizedCharges()['fees_and_charges']);
    }

    public function getPenaltyChargesAttribute(): string
    {
        return LoanProduct::chargeLabel($this->normalizedCharges()['penalty']);
    }

    /**
     * Legacy alias kept for existing views and the mobile API (`late_charges`).
     */
    public function getLateChargesAttribute(): string
    {
        return $this->penalty_charges;
    }
}
