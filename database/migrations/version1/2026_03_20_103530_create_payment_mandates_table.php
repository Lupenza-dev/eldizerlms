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
    Schema::create('payment_mandates', function (Blueprint $table) {
        $table->id();
        $table->string('channel');
        $table->string('reference');
        $table->string('periodicity');
        $table->string('debit_type');
        $table->string('installment_amount');
        $table->string('min_installment_amount');
        $table->string('max_installment_amount');
        $table->string('total_amount');
        $table->string('paid_amount');
        $table->string('outstanding_amount');
        $table->string('number_of_installment');
        $table->date('start_date');
        $table->date('end_date');
        $table->string('contract_status');
        $table->string('approved');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_mandates');
    }
};
