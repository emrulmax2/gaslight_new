<?php

namespace Database\Seeders;

use App\Models\CustomerJobStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerJobStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CustomerJobStatus::insert([
            [ 
                'name' => 'In Progress',
                'active' => 1,
                'created_by' => 1,
                'created_at' => date("Y-m-d", time())
            ],
            [ 
                'name' => 'To Be Invoiced',
                'active' => 1,
                'created_by' => 1,
                'created_at' => date("Y-m-d", time())
            ],
            [ 
                'name' => 'Follow-up Needed',
                'active' => 1,
                'created_by' => 1,
                'created_at' => date("Y-m-d", time())
            ],
            [ 
                'name' => 'Completed',
                'active' => 1,
                'created_by' => 1,
                'created_at' => date("Y-m-d", time())
            ]

        ]);
    }
}
