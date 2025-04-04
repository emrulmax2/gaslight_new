<?php

namespace Database\Seeders;

use App\Models\CommissionDecommissionWorkType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommissionDecommissionWorkTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CommissionDecommissionWorkType::insert([
            ['name' => 'Installation', 'active' => 1, 'created_by' => 1, 'created_at' => date("Y-m-d", time())],
            ['name' => 'LPG', 'active' => 1, 'created_by' => 1, 'created_at' => date("Y-m-d", time())],
            ['name' => 'Domestic', 'active' => 1, 'created_by' => 1, 'created_at' => date("Y-m-d", time())],
            ['name' => 'Commissioning', 'active' => 1, 'created_by' => 1, 'created_at' => date("Y-m-d", time())],
            ['name' => 'Catering', 'active' => 1, 'created_by' => 1, 'created_at' => date("Y-m-d", time())],
            ['name' => 'Laundry', 'active' => 1, 'created_by' => 1, 'created_at' => date("Y-m-d", time())],
            ['name' => 'NG', 'active' => 1, 'created_by' => 1, 'created_at' => date("Y-m-d", time())],
            ['name' => 'Non-Domestic', 'active' => 1, 'created_by' => 1, 'created_at' => date("Y-m-d", time())],
            ['name' => 'Decommissioning', 'active' => 1, 'created_by' => 1, 'created_at' => date("Y-m-d", time())],
            ['name' => 'Other', 'active' => 1, 'created_by' => 1, 'created_at' => date("Y-m-d", time())]
        ]);
    }
}
