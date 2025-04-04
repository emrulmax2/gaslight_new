<?php

namespace Database\Seeders;

use App\Models\CalendarTimeSlot;
use App\Models\Company;
use App\Models\Title;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->callIfNotSeeded(UserSeeder::class);
        $this->callIfNotSeeded(OptionSeeder::class);
        $this->callIfNotSeeded(SuperAdminSeeder::class);
        $this->callIfNotSeeded(CustomerJobStatusSeeder::class);
        $this->callIfNotSeeded(CustomerJobPrioritySeeder::class);
        $this->callIfNotSeeded(JobFormSeeder::class);
        $this->callIfNotSeeded(CalendarTimeSlotSeeder::class);
        $this->callIfNotSeeded(CompanySeeder::class);
        $this->callIfNotSeeded(StaffSeeder::class);
        $this->callIfNotSeeded(TitleSeeder::class);
        $this->callIfNotSeeded(PaymentMethodSeeder::class);
        $this->callIfNotSeeded(ApplianceFlueTypeSeeder::class);
        $this->callIfNotSeeded(ApplianceLocationSeeder::class);
        $this->callIfNotSeeded(ApplianceTypeSeeder::class);
        $this->callIfNotSeeded(BoilerBrandsSeeder::class);
        $this->callIfNotSeeded(RelationSeeder::class);
        $this->callIfNotSeeded(GasWarningClassificationSeeder::class);
        $this->callIfNotSeeded(ApplianceTimeTemperatureHeaginSeeder::class);
        $this->callIfNotSeeded(JobFormNewRowsSeeder::class);
        $this->callIfNotSeeded(ColorSeeder::class);
        $this->callIfNotSeeded(PowerflushCirculatorPumpLocationSeeder::class);
        $this->callIfNotSeeded(PowerflushCylinderTypeSeeder::class);
        $this->callIfNotSeeded(PowerflushPipeworkTypeSeeder::class);
        $this->callIfNotSeeded(PowerflushSystemTypeSeeder::class);
        $this->callIfNotSeeded(RadiatorTypeSeeder::class);
        $this->callIfNotSeeded(CommissionDecommissionWorkType::class);
    }

    /**
     * Call the given seeder class if data has not been seeded.
     *
     * @param string $class
     * @return void
     */
    public function callIfNotSeeded($class)
    {
        // Extract the class name
        $className = (new \ReflectionClass($class))->getShortName();

        // Check if the data has already been seeded
        $seeded = DB::table('seeders')->where('seeder', $className)->exists();

        if (!$seeded) {
            // Call the seeder
            parent::call($class);

            // Mark the seeder as run
            DB::table('seeders')->insert(['seeder' => $className]);
        }
    }
}
