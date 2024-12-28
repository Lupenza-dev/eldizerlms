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
        Schema::create('nmb_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('nmb_account')->nullable();
            $table->string('nmb_username');
            $table->string('nmb_password');
            $table->string('token');
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
        Schema::dropIfExists('nmb_subscriptions');
    }
};
