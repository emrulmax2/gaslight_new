<?php

namespace Database\Seeders;

use App\Models\PowerflushPipeworkType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PowerflushPipeworkTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PowerflushPipeworkType::insert([
            ['name' => 'Copper 15mm / 22mm', 'active' => 1, 'created_by' => 1, 'created_at' => date("Y-m-d", time())],
            ['name' => 'Microbore', 'active' => 1, 'created_by' => 1, 'created_at' => date("Y-m-d", time())],
            ['name' => 'Single pipe', 'active' => 1, 'created_by' => 1, 'created_at' => date("Y-m-d", time())],
            ['name' => 'Steel pipe work', 'active' => 1, 'created_by' => 1, 'created_at' => date("Y-m-d", time())]
        ]);
    }
}
