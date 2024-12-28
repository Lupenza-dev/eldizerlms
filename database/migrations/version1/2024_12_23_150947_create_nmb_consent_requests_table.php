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
        Schema::create('nmb_consent_requests', function (Blueprint $table) {
            $table->id();
            $table->string('consent_type');
            $table->integer('nmb_subscriber_id');
            $table->string('consent_request_id');
            $table->string('from_account_bank_scheme');
            $table->string('from_bank_id');
            $table->string('from_account_scheme');
            $table->string('from_account_number');
            $table->string('to_account_counterparty_name');
            $table->string('to_account_bank_scheme');
            $table->string('to_bank_id');
            $table->string('to_account_scheme');
            $table->string('to_account_number');
            $table->string('currency');
            $table->string('max_single_amount');
            $table->string('max_monthly_amount');
            $table->string('max_number_of_monthly_transactions');
            $table->string('max_yearly_amount');
            $table->string('max_number_of_yearly_transactions');
            $table->string('max_total_amount');
            $table->string('max_number_of_transactions');
            $table->string('valid_from');
            $table->string('time_to_live');
            $table->string('email');
            $table->string('phone_number');
            $table->string('consumer_id');
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
        Schema::dropIfExists('nmb_consent_requests');
    }
};
