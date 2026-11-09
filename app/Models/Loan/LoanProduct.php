<?php

namespace App\Models\Loan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class LoanProduct extends Model
{
    use HasFactory, SoftDeletes;

    public const METHOD_INTEREST       = 'interest';
    public const METHOD_PRINCIPAL_ONLY = 'principal_only';
    public const METHOD_FIXED_AMOUNT   = 'fixed_amount';

    public const METHODS = [
        self::METHOD_INTEREST       => 'Interest (amount + markup %)',
        self::METHOD_PRINCIPAL_ONLY => 'Principal only (no markup)',
        self::METHOD_FIXED_AMOUNT   => 'Fixed total amount',
    ];

    protected $fillable = [
        'uuid', 'name', 'code', 'description', 'icon', 'color',
        'calculation_method', 'markup_rate', 'fixed_total_amount', 'plan_months', 'interest_rate',
        'has_fees_and_charges', 'fees_is_percentage', 'fees_value',
        'has_penalty', 'penalty_is_percentage', 'penalty_value',
        'mandate_multiplier', 'min_amount', 'max_amount',
        'requires_agent', 'requires_device', 'allows_initial_deposit',
        'requires_eco_water_campus', 'register_with_eco_water', 'is_active',
        'sort_order', 'created_by',
    ];

    protected $casts = [
        'markup_rate'               => 'float',
        'fixed_total_amount'        => 'float',
        'plan_months'               => 'integer',
        'interest_rate'             => 'float',
        'has_fees_and_charges'      => 'boolean',
        'fees_is_percentage'        => 'boolean',
        'fees_value'                => 'float',
        'has_penalty'               => 'boolean',
        'penalty_is_percentage'     => 'boolean',
        'penalty_value'             => 'float',
        'mandate_multiplier'        => 'float',
        'min_amount'                => 'float',
        'max_amount'                => 'float',
        'requires_agent'            => 'boolean',
        'requires_device'           => 'boolean',
        'allows_initial_deposit'    => 'boolean',
        'requires_eco_water_campus' => 'boolean',
        'register_with_eco_water'   => 'boolean',
        'is_active'                 => 'boolean',
        'sort_order'                => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::orderedUuid();
            }
        });
    }

    public function loanApplications()
    {
        return $this->hasMany(LoanApplication::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Resolve a product from a product id, falling back to the legacy loan_type (ids mirror 1,2,3).
     */
    public static function resolve($productId = null, $legacyLoanType = null, bool $activeOnly = true): ?self
    {
        $id = $productId ?: $legacyLoanType;
        if (! $id) {
            return null;
        }

        return static::query()
            ->when($activeOnly, fn ($q) => $q->where('is_active', true))
            ->find($id);
    }

    /**
     * Charges snapshot stored on loan_applications.other_fees / loan_contracts.other_fees.
     */
    public function chargesSnapshot(): array
    {
        return [
            'fees_and_charges' => [
                'enabled' => $this->has_fees_and_charges,
                'type'    => $this->fees_is_percentage ? 'percentage' : 'fixed',
                'value'   => $this->fees_value,
            ],
            'penalty' => [
                'enabled' => $this->has_penalty,
                'type'    => $this->penalty_is_percentage ? 'percentage' : 'fixed',
                'value'   => $this->penalty_value,
            ],
        ];
    }

    public function otherFeesJson(): string
    {
        return json_encode($this->chargesSnapshot());
    }

    /**
     * Human label for a normalized charge array: "9.5%", "TZS 5,000" or "N/A".
     */
    public static function chargeLabel(?array $charge): string
    {
        if (! $charge || empty($charge['enabled'])) {
            return 'N/A';
        }

        $value = (float) ($charge['value'] ?? 0);

        return ($charge['type'] ?? 'percentage') === 'fixed'
            ? 'TZS ' . number_format($value)
            : rtrim(rtrim(number_format($value * 100, 2, '.', ''), '0'), '.') . '%';
    }

    public function getFeesLabelAttribute(): string
    {
        return self::chargeLabel($this->chargesSnapshot()['fees_and_charges']);
    }

    public function getPenaltyLabelAttribute(): string
    {
        return self::chargeLabel($this->chargesSnapshot()['penalty']);
    }

    public function getInterestLabelAttribute(): string
    {
        return rtrim(rtrim(number_format($this->interest_rate * 100, 2, '.', ''), '0'), '.') . '%';
    }

    public function getMethodLabelAttribute(): string
    {
        return self::METHODS[$this->calculation_method] ?? $this->calculation_method;
    }

    public const COLORS = [
        'blue'    => '#2563eb',
        'emerald' => '#059669',
        'cyan'    => '#0891b2',
        'violet'  => '#7c3aed',
        'amber'   => '#d97706',
        'rose'    => '#e11d48',
        'indigo'  => '#4f46e5',
        'orange'  => '#ea580c',
        'slate'   => '#475569',
    ];

    public function getHexColorAttribute(): string
    {
        return self::COLORS[$this->color] ?? '#16a34a';
    }

    public function badge(): string
    {
        return "<span class='badge text-white' style='background-color:{$this->hex_color}'>" . e($this->name) . '</span>';
    }
}
