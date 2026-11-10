<?php

namespace Database\Seeders;

use App\Models\Loan\LoanProduct;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Default loan products + backfill of loan_applications.loan_product_id.
 *
 * Safe to run any number of times:
 *  - existing products (matched by code) are never overwritten, so admin edits survive;
 *  - only applications without a product are backfilled.
 */
class LoanProductSeeder extends Seeder
{
    /** Legacy loan_type value => product code. */
    private const LEGACY_TYPES = [1 => 'CASH', 2 => 'PAY_LATER', 3 => 'ECO_WATER'];

    public function run(): void
    {
        $common = [
            'interest_rate'         => 0.035,
            'has_fees_and_charges'  => true,
            'fees_is_percentage'    => true,
            'fees_value'            => 0.095,
            'has_penalty'           => true,
            'penalty_is_percentage' => true,
            'penalty_value'         => 0.05,
            'min_amount'            => 1000,
            'is_active'             => true,
        ];

        $products = [
            'CASH' => [
                'id'                        => 1,
                'name'                      => 'Cash',
                'description'               => 'Borrow money - cash loan via agent',
                'icon'                      => 'bx-money',
                'color'                     => 'blue',
                'calculation_method'        => LoanProduct::METHOD_INTEREST,
                'markup_rate'               => 0.20,
                'fixed_total_amount'        => null,
                'plan_months'               => 3,
                'mandate_multiplier'        => 2,
                'requires_agent'            => true,
                'requires_device'           => false,
                'allows_initial_deposit'    => false,
                'requires_eco_water_campus' => false,
                'register_with_eco_water'   => false,
                'sort_order'                => 1,
            ],
            'PAY_LATER' => [
                'id'                        => 2,
                'name'                      => 'Pay Later',
                'description'               => 'Buy a device on installment',
                'icon'                      => 'bx-mobile-alt',
                'color'                     => 'emerald',
                'calculation_method'        => LoanProduct::METHOD_PRINCIPAL_ONLY,
                'markup_rate'               => 0,
                'fixed_total_amount'        => null,
                'plan_months'               => 3,
                'mandate_multiplier'        => 2,
                'requires_agent'            => true,
                'requires_device'           => true,
                'allows_initial_deposit'    => true,
                'requires_eco_water_campus' => false,
                'register_with_eco_water'   => false,
                'sort_order'                => 2,
            ],
            'ECO_WATER' => [
                'id'                        => 3,
                'name'                      => 'Eco Water',
                'description'               => 'Water project loan',
                'icon'                      => 'bx-water',
                'color'                     => 'cyan',
                'calculation_method'        => LoanProduct::METHOD_FIXED_AMOUNT,
                'markup_rate'               => 0,
                'fixed_total_amount'        => 15000,
                'plan_months'               => 1,
                'mandate_multiplier'        => 1,
                'requires_agent'            => false,
                'requires_device'           => false,
                'allows_initial_deposit'    => false,
                'requires_eco_water_campus' => true,
                'register_with_eco_water'   => true,
                'sort_order'                => 3,
            ],
        ];

        // Ids 1,2,3 mirror the legacy loan_type values used by the mobile app.
        $ids = [];
        foreach ($products as $code => $attributes) {
            $product = LoanProduct::withTrashed()->firstOrCreate(['code' => $code], array_merge($common, $attributes));
            $ids[$code] = $product->id;
        }

        // Backfill applications created before loan products existed.
        foreach (self::LEGACY_TYPES as $loanType => $code) {
            DB::table('loan_applications')
                ->whereNull('loan_product_id')
                ->where('loan_type', $loanType)
                ->update(['loan_product_id' => $ids[$code]]);
        }

        // NULL / unknown loan_type falls back to Cash (the loan_type column default).
        DB::table('loan_applications')
            ->whereNull('loan_product_id')
            ->update(['loan_product_id' => $ids['CASH']]);
    }
}
