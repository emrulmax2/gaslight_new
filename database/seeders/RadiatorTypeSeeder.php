<?php

namespace Database\Seeders;

use App\Models\RadiatorType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RadiatorTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RadiatorType::insert([
            ['name' => 'Steel/Cast Iron', 'active' => 1, 'created_by' => 1, 'created_at' => date("Y-m-d", time())],
            ['name' => 'Aluminium', 'active' => 1, 'created_by' => 1, 'created_at' => date("Y-m-d", time())]
        ]);
    }
}
