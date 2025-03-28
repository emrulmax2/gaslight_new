<?php

namespace Database\Seeders;

use App\Models\PowerflushCirculatorPumpLocation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PowerflushCirculatorPumpLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PowerflushCirculatorPumpLocation::insert([
            ['name' => 'In boiler casing', 'active' => 1, 'created_by' => 1, 'created_at' => date("Y-m-d", time())],
            ['name' => 'Adjacent to boiler', 'active' => 1, 'created_by' => 1, 'created_at' => date("Y-m-d", time())],
            ['name' => 'In airing cupboard', 'active' => 1, 'created_by' => 1, 'created_at' => date("Y-m-d", time())],
            ['name' => 'Elsewhere', 'active' => 1, 'created_by' => 1, 'created_at' => date("Y-m-d", time())]
        ]);
    }
}
