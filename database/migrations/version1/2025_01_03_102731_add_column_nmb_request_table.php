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
        Schema::table('nmb_consent_requests', function (Blueprint $table) {
            $table->string('consent_id')->nullable();
            $table->text('jwt')->nullable();
            $table->string('status')->nullable();
            $table->string('view_id')->nullable();
            $table->string('counterparty_id')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nmb_consent_requests', function (Blueprint $table) {
            $table->dropColumn('consent_id');
            $table->dropColumn('jwt');
            $table->dropColumn('status');
            $table->dropColumn('view_id');
            $table->dropColumn('counterparty_id');
            
        });
    }
};
