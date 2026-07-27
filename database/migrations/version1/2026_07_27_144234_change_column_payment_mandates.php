<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mandate_payment_collections', function (Blueprint $table) {
            // $table->string('installment_order')->nullable()->change();
            $table->string('installment_amount')->nullable()->change();
            // $table->string('min_installment_amount')->nullable()->change();
            // $table->string('max_installment_amount')->nullable()->change();
            $table->string('outstanding_amount')->nullable()->change();
            $table->string('payment_date')->nullable()->change();
            $table->string('last_paid_amount')->nullable()->change();
            $table->string('status')->nullable()->change();
            $table->string('remarks')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mandate_payment_collections', function (Blueprint $table) {
            // $table->string('installment_order')->nullable(false)->change();
            $table->string('installment_amount')->nullable(false)->change();
            // $table->string('min_installment_amount')->nullable(false)->change();
            // $table->string('max_installment_amount')->nullable(false)->change();
            $table->string('outstanding_amount')->nullable(false)->change();
            $table->string('payment_date')->nullable(false)->change();
            $table->string('last_paid_amount')->nullable(false)->change();
            $table->string('status')->nullable(false)->change();
            $table->string('remarks')->nullable(false)->change();
        });
    }
};
