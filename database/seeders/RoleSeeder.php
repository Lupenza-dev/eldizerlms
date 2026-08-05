<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('roles')->truncate();


        DB::table('roles')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'name' => "Admin",
                    'guard_name' => "web",
                    'created_at' => '2024-03-27 03:04:00',
                    'updated_at' => '2024-03-27 03:04:00',
                ),
            1 =>
                array(
                    'id' => 2,
                    'name' => "Super Admin",
                    'guard_name' => "web",
                    'created_at' => '2024-03-27 03:04:00',
                    'updated_at' => '2024-03-27 03:04:00',
                ),
            2 =>
                array(
                    'id' => 3,
                    'name' => "Agent",
                    'guard_name' => "web",
                    'created_at' => '2024-03-27 03:04:00',
                    'updated_at' => '2024-03-27 03:04:00',
                ),
            3 =>
                array(
                    'id' => 4,
                    'name' => "Customer",
                    'guard_name' => "web",
                    'created_at' => '2024-03-27 03:04:00',
                    'updated_at' => '2024-03-27 03:04:00',
                ),
            4 =>
                array(
                    'id' => 5,
                    'name' => "Internal User",
                    'guard_name' => "web",
                    'created_at' => '2024-03-27 03:04:00',
                    'updated_at' => '2024-03-27 03:04:00',
                ),
           
            ));

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
