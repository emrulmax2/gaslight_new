<?php

namespace Database\Seeders;

use App\Models\JobForm;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobFormNewRows3Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JobForm::insert([
            [ 
                'id' => 18,
                'parent_id' => 14,
                'name' => 'Job Sheet',
                'slug' => 'job_sheet',
                'active' => 1,
                'roder' => 4,
                'created_by' => 1,
                'created_at' => date("Y-m-d", time())
            ],
        ]);
    }
}
