<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Designation;
use App\Models\SstPercentage;
use App\Models\PoImportantNote;
use App\Models\SpecBreak;
use Illuminate\Database\Seeder;

class GeneralSettingSeeder extends Seeder
{
    public function run(): void
    {
        SstPercentage::create([
            'sst_percentage' => '10',
        ]);
        PoImportantNote::create([
            'po_note' => '',
        ]);
        SpecBreak::create([
            'normal_hour' => '0',
            'ot_hour' => '0',
        ]);
        Designation::create([
            'name' => 'COO'
        ]);
        Designation::create([
            'name' => 'CEO'
        ]);
        Designation::create([
            'name' => 'MD'
        ]);
        Department::create([
            'name' => 'Finance'
        ]);
    }
}
