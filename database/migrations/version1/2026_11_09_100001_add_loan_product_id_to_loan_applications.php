<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->foreignId('loan_product_id')
                ->nullable()
                ->after('loan_type')
                ->constrained('loan_products');
        });

        // Backfill from the legacy loan_type column (product ids 1,2,3 mirror loan_type 1,2,3).
        DB::table('loan_applications')
            ->whereIn('loan_type', [1, 2, 3])
            ->update(['loan_product_id' => DB::raw('loan_type')]);

        // Anything else (NULL / unknown) falls back to Cash, matching the loan_type column default.
        DB::table('loan_applications')
            ->whereNull('loan_product_id')
            ->update(['loan_product_id' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('loan_product_id');
        });
    }
};
