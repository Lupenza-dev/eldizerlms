<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeviceCategory extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('device_categories')->truncate();


        DB::table('device_categories')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'name' => "Mobile Phone",
                    'created_at' => '2024-03-27 03:04:00',
                    'updated_at' => '2024-03-27 03:04:00',
                ),
            1 =>
                array(
                    'id' => 2,
                    'name' => "Laptop",
                    'created_at' => '2024-03-27 03:04:00',
                    'updated_at' => '2024-03-27 03:04:00',
                ),
           
            ));

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
