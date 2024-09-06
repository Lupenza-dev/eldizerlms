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

        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->integer('device_category_id');
            $table->integer('user_id');
            $table->string('name');
            $table->integer('price');
            $table->integer('plan');
            $table->integer('initial_deposit');
            $table->string('image')->nullable();
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
        Schema::dropIfExists('devices');
    }
};
