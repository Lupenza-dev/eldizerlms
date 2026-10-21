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
        Schema::create('eco_water_applicants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_application_id');
            $table->unsignedBigInteger('eco_water_campus_id');
            $table->string('remarks')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->boolean('is_eco_water_eligible')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eco_water_applicants');

         Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('is_eco_water_eligible');
        });
    }
};
