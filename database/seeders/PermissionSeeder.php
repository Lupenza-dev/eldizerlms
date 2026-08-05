<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('permissions')->truncate();


        DB::table('permissions')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'name' => "Delete User",
                    'guard_name' => "web",
                    'created_at' => '2024-03-27 03:04:00',
                    'updated_at' => '2024-03-27 03:04:00',
                ),
            1 =>
                array(
                    'id' => 2,
                    'name' => "Create User",
                    'guard_name' => "web",
                    'created_at' => '2024-03-27 03:04:00',
                    'updated_at' => '2024-03-27 03:04:00',
                ),
            2 =>
                array(
                    'id' => 3,
                    'name' => "View User",
                    'guard_name' => "web",
                    'created_at' => '2024-03-27 03:04:00',
                    'updated_at' => '2024-03-27 03:04:00',
                ),
            3 =>
                array(
                    'id' => 4,
                    'name' => "Approve Loan",
                    'guard_name' => "web",
                    'created_at' => '2024-03-27 03:04:00',
                    'updated_at' => '2024-03-27 03:04:00',
                ),
            4 =>
                array(
                    'id' => 5,
                    'name' => "Approve Payment",
                    'guard_name' => "web",
                    'created_at' => '2024-03-27 03:04:00',
                    'updated_at' => '2024-03-27 03:04:00',
                ),
           
            ));

        app(PermissionRegistrar::class)->forgetCachedPermissions();

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
