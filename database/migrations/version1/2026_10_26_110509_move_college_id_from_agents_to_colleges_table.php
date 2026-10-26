<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add agent_id column (agents.id is INT signed)
        Schema::table('colleges', function (Blueprint $table) {
            $table->integer('agent_id')->nullable()->after('id');
        });

        Schema::table('colleges', function (Blueprint $table) {
            $table->foreign('agent_id')
                ->references('id')
                ->on('agents')
                ->nullOnDelete();
        });

        // Migrate data from agents.college_id to colleges.agent_id
        DB::table('agents')
            ->whereNotNull('college_id')
            ->orderBy('id')
            ->chunk(500, function ($agents) {
                foreach ($agents as $agent) {
                    DB::table('colleges')
                        ->where('id', $agent->college_id)
                        ->update(['agent_id' => $agent->id]);
                }
            });

        // Drop FK on agents.college_id if it exists (it may not)
        $this->dropForeignKeyIfExists('agents', 'college_id');

        // Drop column
        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn('college_id');
        });
    }

    public function down(): void
    {
        // Restore college_id column on agents
        Schema::table('agents', function (Blueprint $table) {
            $table->integer('college_id')->nullable()->after('user_id');
        });

        Schema::table('agents', function (Blueprint $table) {
            $table->foreign('college_id')
                ->references('id')
                ->on('colleges')
                ->nullOnDelete();
        });

        // Restore data from colleges.agent_id to agents.college_id
        DB::table('colleges')
            ->whereNotNull('agent_id')
            ->orderBy('id')
            ->chunk(500, function ($colleges) {
                foreach ($colleges as $college) {
                    DB::table('agents')
                        ->where('id', $college->agent_id)
                        ->update(['college_id' => $college->id]);
                }
            });

        // Drop FK on colleges.agent_id if it exists
        $this->dropForeignKeyIfExists('colleges', 'agent_id');

        // Drop column
        Schema::table('colleges', function (Blueprint $table) {
            $table->dropColumn('agent_id');
        });
    }

    /**
     * Drop a foreign key on table.column only if it exists.
     */
    private function dropForeignKeyIfExists(string $table, string $column): void
    {
        $fks = DB::select(
            "SELECT CONSTRAINT_NAME
             FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = ?
               AND COLUMN_NAME = ?
               AND REFERENCED_TABLE_NAME IS NOT NULL",
            [$table, $column]
        );

        foreach ($fks as $fk) {
            DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
        }
    }
};