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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('key', 100)->unique()->comment('stable slug used by hub pages and the seeder');
            $table->string('title');
            $table->string('description')->nullable()->comment('hub card subtitle');
            $table->string('icon', 100)->nullable()->comment('boxicons class, e.g. bx-cog');
            $table->string('color', 30)->nullable()->comment('tailwind color name for hub cards');
            $table->foreignId('parent_id')->nullable()->constrained('menus')->nullOnDelete();
            $table->string('display_mode', 20)->default('link')->comment('link | section | dropdown | hub');
            $table->string('route_name')->nullable();
            $table->string('url')->nullable();
            $table->string('active_routes', 500)->nullable()->comment('comma separated Route::is patterns');
            $table->string('permission')->nullable()->comment('optional Spatie permission required to see the menu');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['parent_id', 'sort_order']);
        });

        Schema::create('menu_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus')->cascadeOnDelete();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['menu_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_role');
        Schema::dropIfExists('menus');
    }
};
