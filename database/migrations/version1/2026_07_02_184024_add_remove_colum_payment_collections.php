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
        Schema::table('mandate_payment_collections', function (Blueprint $table) {
            $table->dropColumn('installment_order');
            $table->dropColumn('min_installment_amount');
            $table->dropColumn('max_installment_amount');
        });

       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mandate_payment_collections', function (Blueprint $table) {
            // $table->dropColumn('lifecycle_status');
            // $table->dropColumn('remarks');
            $table->string('installment_order')->nullable();
            $table->string('min_installment_amount')->nullable();
            $table->string('max_installment_amount')->nullable();
        });

       
    }
};
