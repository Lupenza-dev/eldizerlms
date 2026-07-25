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
        Schema::table('payment_mandates', function (Blueprint $table) {
            $table->string('lifecycle_status')->nullable();
            $table->string('remarks')->nullable();
        });

       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_mandates', function (Blueprint $table) {
            $table->dropColumn('lifecycle_status');
            $table->dropColumn('remarks');
        });

       
    }
};
