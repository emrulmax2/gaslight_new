<?php

namespace Database\Seeders;

use App\Models\JobForm;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobFormNewRowsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JobForm::insert([
            [ 
                'id' => 13,
                'parent_id' => 5,
                'name' => 'Gas Boiler System Commissioning Checklist',
                'slug' => 'gas_boiler_system_commissioning_checklist',
                'active' => 1,
                'roder' => 6,
                'created_by' => 1,
                'created_at' => date("Y-m-d", time())
            ],
            [ 
                'id' => 14,
                'parent_id' => 0,
                'name' => 'Miscellaneous',
                'slug' => null,
                'active' => 1,
                'roder' => 3,
                'created_by' => 1,
                'created_at' => date("Y-m-d", time())
            ],
            [ 
                'id' => 15,
                'parent_id' => 14,
                'name' => 'Powerflush Certificate',
                'slug' => 'power_flush_record',
                'active' => 1,
                'roder' => 1,
                'created_by' => 1,
                'created_at' => date("Y-m-d", time())
            ],
            [ 
                'id' => 16,
                'parent_id' => 14,
                'name' => 'Installation / Commissioning Decommissioning Record',
                'slug' => 'installation_commissioning_decommissioning_record',
                'active' => 1,
                'roder' => 2,
                'created_by' => 1,
                'created_at' => date("Y-m-d", time())
            ],
        ]);
    }
}
