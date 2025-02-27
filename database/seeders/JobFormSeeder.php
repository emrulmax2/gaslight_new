<?php

namespace Database\Seeders;

use App\Models\JobForm;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JobForm::insert([
            [ 
                'parent_id' => 0,
                'name' => 'Invoicing',
                'active' => 1,
                'roder' => 1,
                'created_by' => 1,
                'created_at' => date("Y-m-d", time())
            ],
            [ 
                'parent_id' => 1,
                'name' => 'Estimate',
                'active' => 1,
                'roder' => 1,
                'created_by' => 1,
                'created_at' => date("Y-m-d", time())
            ],
            [ 
                'parent_id' => 1,
                'name' => 'Quote',
                'active' => 1,
                'roder' => 2,
                'created_by' => 1,
                'created_at' => date("Y-m-d", time())
            ],
            [ 
                'parent_id' => 1,
                'name' => 'Invoice',
                'active' => 1,
                'roder' => 3,
                'created_by' => 1,
                'created_at' => date("Y-m-d", time())
            ],


            [ 
                'parent_id' => 0,
                'name' => 'Domestic Gas Records',
                'active' => 1,
                'roder' => 2,
                'created_by' => 1,
                'created_at' => date("Y-m-d", time())
            ],
            [ 
                'parent_id' => 5,
                'name' => 'Homeowner Gas Safety Record',
                'active' => 1,
                'roder' => 1,
                'created_by' => 1,
                'created_at' => date("Y-m-d", time())
            ],
            [ 
                'parent_id' => 5,
                'name' => 'Landlord Gas Safety Record',
                'active' => 1,
                'roder' => 2,
                'created_by' => 1,
                'created_at' => date("Y-m-d", time())
            ],
            [ 
                'parent_id' => 5,
                'name' => 'Gas Warning Notice',
                'active' => 1,
                'roder' => 3,
                'created_by' => 1,
                'created_at' => date("Y-m-d", time())
            ],
            [ 
                'parent_id' => 5,
                'name' => 'Gas Service Record',
                'active' => 1,
                'roder' => 4,
                'created_by' => 1,
                'created_at' => date("Y-m-d", time())
            ],
            [ 
                'parent_id' => 5,
                'name' => 'Gas Breakdown Record',
                'active' => 1,
                'roder' => 5,
                'created_by' => 1,
                'created_at' => date("Y-m-d", time())
            ],


            [ 
                'parent_id' => 0,
                'name' => 'Non-Domestic Gas Records',
                'active' => 1,
                'roder' => 3,
                'created_by' => 1,
                'created_at' => date("Y-m-d", time())
            ],
            [ 
                'parent_id' => 11,
                'name' => 'ND Gas Safety Record',
                'active' => 1,
                'roder' => 1,
                'created_by' => 1,
                'created_at' => date("Y-m-d", time())
            ],

        ]);
    }
}
