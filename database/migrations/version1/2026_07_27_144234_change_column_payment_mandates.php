<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mandate_payment_collections', function (Blueprint $table) {
            $table->string('installment_order')->nullable();
            $table->string('installment_amount')->nullable();
            $table->string('min_installment_amount')->nullable();
            $table->string('max_installment_amount')->nullable();
            $table->string('outstanding_amount')->nullable();
            $table->string('payment_date')->nullable();
            $table->string('last_paid_amount')->nullable();
            $table->string('status')->nullable();
            $table->string('remarks')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mandate_payment_collections', function (Blueprint $table) {
               $table->string('installment_order')->nullable();
            $table->string('installment_amount')->nullable();
            $table->string('min_installment_amount')->nullable();
            $table->string('max_installment_amount')->nullable();
            $table->string('outstanding_amount')->nullable();
            $table->string('payment_date')->nullable();
            $table->string('last_paid_amount')->nullable();
            $table->string('status')->nullable();
            $table->string('remarks')->nullable();
            
        });
    }
};
