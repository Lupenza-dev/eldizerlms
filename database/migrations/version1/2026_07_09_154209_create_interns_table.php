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
        Schema::create('interns', function (Blueprint $table) {
            // Schema::dropIfExists('interns');
            $table->id();
            $table->integer('customer_id');
            $table->integer('hospital_id');
            $table->string('professional');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('letter')->nullable();
            $table->uuid();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interns');
    }
};
