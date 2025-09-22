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
        Schema::create('assignment_participants', function (Blueprint $table) {
            $table->id();
            $table->integer('assignment_id');
            $table->integer('user_id');
            $table->integer('score_gained');
            $table->integer('total_questions');
            $table->json('answers');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_participants');
    }
};
