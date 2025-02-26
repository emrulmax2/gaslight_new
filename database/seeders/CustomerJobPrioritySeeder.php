<?php

namespace Database\Seeders;

use App\Models\CustomerJobPriority;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerJobPrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CustomerJobPriority::insert([
            [ 
                'name' => 'Normal',
                'active' => 1,
                'created_by' => 1,
                'created_at' => date("Y-m-d", time())
            ],
            [ 
                'name' => 'High',
                'active' => 1,
                'created_by' => 1,
                'created_at' => date("Y-m-d", time())
            ],
            [ 
                'name' => 'Low',
                'active' => 1,
                'created_by' => 1,
                'created_at' => date("Y-m-d", time())
            ]

        ]);
    }
}
