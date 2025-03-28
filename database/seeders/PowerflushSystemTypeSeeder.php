<?php

namespace Database\Seeders;

use App\Models\PowerflushSystemType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PowerflushSystemTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PowerflushSystemType::insert([
            ['name' => 'Vented', 'active' => 1, 'created_by' => 1, 'created_at' => date("Y-m-d", time())],
            ['name' => 'Sealed', 'active' => 1, 'created_by' => 1, 'created_at' => date("Y-m-d", time())],
            ['name' => 'Fully pumped', 'active' => 1, 'created_by' => 1, 'created_at' => date("Y-m-d", time())],
            ['name' => 'Gravity hot water', 'active' => 1, 'created_by' => 1, 'created_at' => date("Y-m-d", time())],
            ['name' => 'Thermal store', 'active' => 1, 'created_by' => 1, 'created_at' => date("Y-m-d", time())]
        ]);
    }
}
