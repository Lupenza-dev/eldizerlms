<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        
        Schema::create('agent_college', function (Blueprint $table) {
            $table->id();
            $table->integer('agent_id');
            $table->integer('college_id');
            $table->timestamps();

            $table->unique(['agent_id', 'college_id']);
            $table->index('agent_id');
            $table->index('college_id');

            $table->foreign('agent_id')
                ->references('id')->on('agents')
                ->cascadeOnDelete();

            $table->foreign('college_id')
                ->references('id')->on('colleges')
                ->cascadeOnDelete();
        });

        if (Schema::hasColumn('agents', 'college_id')) {
            DB::table('agents')
                ->whereNotNull('college_id')
                ->orderBy('id')
                ->chunk(500, function ($agents) {
                    $rows = [];
                    foreach ($agents as $agent) {
                        $rows[] = [
                            'agent_id'   => $agent->id,
                            'college_id' => $agent->college_id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                    if (! empty($rows)) {
                        DB::table('agent_college')->insertOrIgnore($rows);
                    }
                });

            Schema::table('agents', function (Blueprint $table) {
                $table->dropColumn('college_id');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('agents', 'college_id')) {
            Schema::table('agents', function (Blueprint $table) {
                $table->integer('college_id')->nullable()->after('user_id');
            });

            $pairs = DB::table('agent_college')
                ->orderBy('id')
                ->get()
                ->groupBy('agent_id');

            foreach ($pairs as $agentId => $rows) {
                DB::table('agents')
                    ->where('id', $agentId)
                    ->update(['college_id' => $rows->first()->college_id]);
            }
        }

        Schema::dropIfExists('agent_college');
    }
};