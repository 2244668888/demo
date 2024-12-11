<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('services')->insert([
            ['name' => 'GYM', 'price' => 1000.00],
            ['name' => 'Restaurant', 'price' => 1500.00],
            ['name' => 'Banquet', 'price' => 2000.00],
            ['name' => 'GRC', 'price' => 2500.00],
        ]);
    }
}
