<?php

namespace Database\Seeders;

use App\Models\PricingPackage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PricingPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PricingPackage::insert([
            [
                'title' => '14 Days Free Trail',
                'subtitle' => null,
                'description' => null,
                'period' => 'Free Trail',
                'price' => 0,
                'order' => 0,

                'active' => 1, 
                'created_by' => 1, 
                'created_at' => date("Y-m-d", time())
            ],
            [
                'title' => 'Monthly',
                'subtitle' => null,
                'description' => null,
                'period' => 'Monthly',
                'price' => 10,
                'order' => 1,

                'active' => 1, 
                'created_by' => 1, 
                'created_at' => date("Y-m-d", time())
            ],
            [
                'title' => 'Yearly',
                'subtitle' => null,
                'description' => null,
                'period' => 'Yearly',
                'price' => 108,
                'order' => 2,

                'active' => 1, 
                'created_by' => 1, 
                'created_at' => date("Y-m-d", time())
            ],
        ]);
    }
}
