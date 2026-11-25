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
        Schema::create('payment_requests', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('phone_number')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('payment_reference')->index();
            $table->string('payment_method')->nullable();
            $table->string('payment_channel')->nullable();
            $table->date('payment_date');
            $table->unsignedBigInteger('loan_contract_id')->nullable()->index();
            $table->unsignedBigInteger('customer_id')->nullable()->index();
            $table->unsignedBigInteger('added_by')->nullable()->index();
            $table->text('remarks')->nullable();
            $table->enum('status', ['pending', 'rejected', 'success'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_requests');
    }
};
