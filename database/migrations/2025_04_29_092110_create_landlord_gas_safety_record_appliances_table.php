<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gas_landlord_safety_record_appliances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gas_landlord_safety_record_id')->index('fklgsra_ind')->constrained('gas_landlord_safety_records', 'id', 'fklgsra_id')->cascadeOnDelete();
            $table->integer('appliance_serial')->nullable();
            $table->foreignId('appliance_location_id')->nullable()->index('fk_lgsra_alid_ind')->constrained('appliance_locations', 'id', 'fk_lgsra_alid_id')->cascadeOnDelete();
            $table->foreignId('boiler_brand_id')->nullable()->index('fk_lgsra_bbid_ind')->constrained('boiler_brands', 'id', 'fk_lgsra_bbid_id')->cascadeOnDelete();
            $table->string('model')->nullable();
            $table->foreignId('appliance_type_id')->nullable()->index('fk_lgsra_atid_ind')->constrained('appliance_types', 'id', 'fk_lgsra_atid_id')->cascadeOnDelete();
            $table->string('serial_no')->nullable();
            $table->string('gc_no')->nullable();
            $table->foreignId('appliance_flue_type_id')->nullable()->index('fk_lgsra_aftid_ind')->constrained('appliance_flue_types', 'id', 'fk_lgsra_aftid_id')->cascadeOnDelete();
            $table->string('opt_pressure')->nullable();
            $table->string('safety_devices', 50)->nullable();
            $table->string('spillage_test', 50)->nullable();
            $table->string('smoke_pellet_test', 50)->nullable();
            $table->string('low_analyser_ratio', 100)->nullable();
            $table->string('low_co', 100)->nullable();
            $table->string('low_co2', 100)->nullable();
            $table->string('high_analyser_ratio', 100)->nullable();
            $table->string('high_co', 100)->nullable();
            $table->string('high_co2', 100)->nullable();
            $table->string('satisfactory_termination', 50)->nullable();
            $table->string('flue_visual_condition', 50)->nullable();
            $table->string('adequate_ventilation', 50)->nullable();
            $table->string('landlord_appliance', 50)->nullable();
            $table->string('inspected', 50)->nullable();
            $table->string('appliance_visual_check', 50)->nullable();
            $table->string('appliance_serviced', 50)->nullable();
            $table->string('appliance_safe_to_use', 50)->nullable();
            

            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gas_landlord_safety_record_appliances');
    }
};
