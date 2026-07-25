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
            $table->boolean('is_mandate_sent')->default(true);
            $table->date('mandate_date')->nullable();
            $table->date('mandate_sent_date')->nullable();
            $table->integer('mandate_created_by')->nullable();
        });

       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->dropColumn('is_mandate_sent');
            $table->dropColumn('mandate_date');
            $table->dropColumn('mandate_sent_date');
            $table->dropColumn('mandate_created_by');
        });

       
    }
};
