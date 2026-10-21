<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->json('images')->nullable()->after('image');
        });

        // Migrate existing single image -> array kwenye column mpya
        DB::table('devices')
            ->whereNotNull('image')
            ->orderBy('id')
            ->each(function ($device) {
                DB::table('devices')
                    ->where('id', $device->id)
                    ->update([
                        'images' => json_encode([$device->image]),
                    ]);
            });
    }

    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropColumn(['description', 'images']);
        });
    }
};