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
        Schema::create('heslb_beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->string('index_number');
            $table->string('full_name');
            $table->string('code');
            $table->string('course_code');
            $table->string('reg_no');
            $table->integer('year_of_study');
            $table->string('academic_year');
            $table->boolean('status')->default(true);
            $table->uuid();
            $table->timestamps();
            $table->softDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('heslb_beneficiaries');
    }
};
