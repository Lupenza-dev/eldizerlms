<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('loan_contracts', function (Blueprint $table) {
            $table->integer('loan_type')->nullable()->comment("1 =>Cash , 2 => PayLater , 3 => EcoWater");
            $table->integer('device_id')->nullable();
        });

        DB::statement('
            UPDATE loan_contracts
            INNER JOIN loan_applications ON loan_applications.id = loan_contracts.loan_application_id
            SET loan_contracts.loan_type = loan_applications.loan_type,
                loan_contracts.device_id = loan_applications.device_id
        ');
    }

    public function down(): void
    {
        Schema::table('loan_contracts', function (Blueprint $table) {
            $table->dropColumn(['loan_type', 'device_id']);
        });
    }
};
