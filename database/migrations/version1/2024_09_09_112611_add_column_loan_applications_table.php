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
            $table->integer('loan_type')->nullable()->default(1)->comment(" 1 =cash , 2 => paylater");
            $table->integer('device_id')->nullable();
            $table->softDeletes();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->dropColumn('loan_type');
            $table->dropColumn('device_id');
            $table->dropColumn('deleted_at');
        });
    }
};
