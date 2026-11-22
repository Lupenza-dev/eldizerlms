<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loan_products', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('code', 50)->unique();
            $table->text('description')->nullable();
            $table->string('icon', 100)->nullable()->comment('boxicons class, e.g. bx-money');
            $table->string('color', 30)->nullable()->comment('tailwind color name, e.g. blue');

            // Pricing
            $table->string('calculation_method', 30)->default('interest')
                ->comment('interest => amount*(1+markup_rate), principal_only => amount, fixed_amount => fixed_total_amount');
            $table->decimal('markup_rate', 8, 4)->default(0);
            $table->decimal('fixed_total_amount', 15, 2)->nullable();
            $table->unsignedInteger('plan_months')->default(3);
            $table->decimal('interest_rate', 8, 4)->default(0.035);

            // Fees and charges
            $table->boolean('has_fees_and_charges')->default(true);
            $table->boolean('fees_is_percentage')->default(true)->comment('true => rate, false => fixed TZS amount');
            $table->decimal('fees_value', 15, 4)->default(0.095);

            // Penalty
            $table->boolean('has_penalty')->default(true);
            $table->boolean('penalty_is_percentage')->default(true)->comment('true => rate, false => fixed TZS amount');
            $table->decimal('penalty_value', 15, 4)->default(0.05);

            // Mandate & limits
            $table->decimal('mandate_multiplier', 8, 2)->default(1);
            $table->decimal('min_amount', 15, 2)->default(1000);
            $table->decimal('max_amount', 15, 2)->nullable();

            // Behaviour flags
            $table->boolean('requires_agent')->default(false);
            $table->boolean('requires_device')->default(false);
            $table->boolean('allows_initial_deposit')->default(false);
            $table->boolean('requires_eco_water_campus')->default(false);
            $table->boolean('register_with_eco_water')->default(false);
            $table->boolean('is_active')->default(true);

            $table->integer('sort_order')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Seed the three products that were previously hardcoded (ids match legacy loan_type values).
        $now = now();
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
            'created_at'            => $now,
            'updated_at'            => $now,
        ];

        DB::table('loan_products')->insert([
            array_merge($common, [
                'id'                        => 1,
                'uuid'                      => (string) Str::orderedUuid(),
                'name'                      => 'Cash',
                'code'                      => 'CASH',
                'description'               => 'Borrow money - cash loan via agent',
                'icon'                      => 'bx-money',
                'color'                     => 'blue',
                'calculation_method'        => 'interest',
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
            ]),
            array_merge($common, [
                'id'                        => 2,
                'uuid'                      => (string) Str::orderedUuid(),
                'name'                      => 'Pay Later',
                'code'                      => 'PAY_LATER',
                'description'               => 'Buy a device on installment',
                'icon'                      => 'bx-mobile-alt',
                'color'                     => 'emerald',
                'calculation_method'        => 'principal_only',
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
            ]),
            array_merge($common, [
                'id'                        => 3,
                'uuid'                      => (string) Str::orderedUuid(),
                'name'                      => 'Eco Water',
                'code'                      => 'ECO_WATER',
                'description'               => 'Water project loan',
                'icon'                      => 'bx-water',
                'color'                     => 'cyan',
                'calculation_method'        => 'fixed_amount',
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
            ]),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_products');
    }
};
