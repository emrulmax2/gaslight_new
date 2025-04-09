<?php

namespace Database\Seeders;

use App\Models\JobForm;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobFormNewRows2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JobForm::insert([
            [ 
                'id' => 17,
                'parent_id' => 14,
                'name' => 'Unvented Hot Water Cylinders',
                'slug' => 'unvented_hot_water_cylinders',
                'active' => 1,
                'roder' => 3,
                'created_by' => 1,
                'created_at' => date("Y-m-d", time())
            ],
        ]);
    }
}
