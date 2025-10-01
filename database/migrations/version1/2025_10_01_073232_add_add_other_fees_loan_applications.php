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
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->json('other_fees')->nullable();
        });

        Schema::table('loan_contracts', function (Blueprint $table) {
            $table->json('other_fees')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->dropColumn('other_fees');
        });

        Schema::table('loan_contracts', function (Blueprint $table) {
            $table->dropColumn('other_fees');
        });
    }
};
