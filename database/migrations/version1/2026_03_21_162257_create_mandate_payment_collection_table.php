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
        Schema::create('mandate_payment_collection', function (Blueprint $table) {
            $table->id();
            $table->string('mandate_reference');
            $table->string('installment_order');
            $table->string('installment_amount');
            $table->string('min_installment_amount');
            $table->string('max_installment_amount');
            $table->string('current_balance');
            $table->string('outstanding_amount');
            $table->string('payment_date');
            $table->string('last_paid_amount');
            $table->string('reference');
            $table->string('status');
            $table->string('remarks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mandate_payment_collection');
    }
};
