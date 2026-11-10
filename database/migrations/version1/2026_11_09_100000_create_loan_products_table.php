<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_products');
    }
};
