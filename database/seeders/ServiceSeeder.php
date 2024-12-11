<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        Service::create([
            'name' => 'GRC',
            'price' => 100.00
        ]);
        Service::create([
            'name' => 'Banquet',
            'price' => 200.00
        ]);
        Service::create([
            'name' => 'Resturant',
            'price' => 300.00
        ]);
        Service::create([
            'name' => 'Lobby',
            'price' => 400.00
        ]);
        Service::create([
            'name' => 'Park',
            'price' => 600.00
        ]);
    }
}
