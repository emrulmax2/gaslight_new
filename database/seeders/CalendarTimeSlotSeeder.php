<?php

namespace Database\Seeders;

use App\Models\CalendarTimeSlot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CalendarTimeSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CalendarTimeSlot::insert([
            [ 
                'title' => 'Morning',
                'start' => '08:00:00',
                'end' => '12:00:00',
                'color_code' => '#ffec9c',
                'active' => 1,
                'created_by' => 1,
                'created_at' => date("Y-m-d", time())
            ],
            [ 
                'title' => 'Afternoon',
                'start' => '12:00:00',
                'end' => '16:00:00',
                'color_code' => '#e7bb67',
                'active' => 1,
                'created_by' => 1,
                'created_at' => date("Y-m-d", time())
            ],
            [ 
                'title' => 'Evening',
                'start' => '16:00:00',
                'end' => '20:00:00',
                'color_code' => '#6a71a5',
                'active' => 1,
                'created_by' => 1,
                'created_at' => date("Y-m-d", time())
            ],
            [ 
                'title' => 'Night',
                'start' => '20:00:00',
                'end' => '24:00:00',
                'color_code' => '#091e36',
                'active' => 1,
                'created_by' => 1,
                'created_at' => date("Y-m-d", time())
            ],

        ]);
    }
}
