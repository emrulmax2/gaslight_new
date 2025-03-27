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
        Schema::create('gas_boiler_system_commissioning_checklist_appliances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gas_boiler_system_commissioning_checklist_id')->index('fkgbscc_ind')->constrained('gas_boiler_system_commissioning_checklists', 'id', 'fkgbscc_id')->cascadeOnDelete();
            $table->integer('appliance_serial')->nullable();
            $table->foreignId('boiler_brand_id')->nullable()->index('fkboiler_b_ind')->constrained('boiler_brands', 'id', 'fkboiler_b_id')->cascadeOnDelete();
            $table->string('model')->nullable();
            $table->string('serial_no')->nullable();
            $table->foreignId('appliance_time_temperature_heating_id')->nullable()->index('fkatth_ind')->constrained('appliance_time_temperature_heatings', 'id', 'fkatth_id')->cascadeOnDelete();
            
            $table->string('tmp_control_hot_water')->nullable();
            $table->string('heating_zone_vlv', 50)->nullable();
            $table->string('hot_water_zone_vlv', 50)->nullable();
            $table->string('therm_radiator_vlv', 50)->nullable();
            $table->string('bypass_to_system', 50)->nullable();
            $table->string('boiler_interlock', 50)->nullable();
            $table->string('flushed_and_cleaned', 50)->nullable();
            $table->string('clearner_name', 50)->nullable();
            $table->string('inhibitor_quantity', 50)->nullable();
            $table->string('inhibitor_amount', 50)->nullable();
            $table->string('primary_ws_filter_installed', 50)->nullable();
            $table->string('gas_rate', 50)->nullable();
            $table->string('gas_rate_unit', 50)->nullable();
            $table->string('cho_factory_setting', 50)->nullable();
            $table->string('burner_opt_pressure', 50)->nullable();
            $table->string('burner_opt_pressure_unit', 50)->nullable();
            $table->string('centeral_heat_flow_temp', 50)->nullable();
            $table->string('centeral_heat_return_temp', 50)->nullable();
            $table->string('is_in_hard_water_area', 50)->nullable();
            $table->string('is_scale_reducer_fitted', 50)->nullable();
            $table->string('what_reducer_fitted', 50)->nullable();
            $table->string('dom_gas_rate', 50)->nullable();
            $table->string('dom_gas_rate_unit', 50)->nullable();
            $table->string('dom_burner_opt_pressure', 50)->nullable();
            $table->string('dom_burner_opt_pressure_unit', 50)->nullable();
            $table->string('dom_cold_water_temp', 50)->nullable();
            $table->string('dom_checked_outlet', 50)->nullable();
            $table->string('dom_water_flow_rate', 50)->nullable();
            $table->string('con_drain_installed', 50)->nullable();
            $table->string('point_of_termination', 50)->nullable();
            $table->string('dispsal_method', 50)->nullable();
            $table->string('min_ratio', 50)->nullable();
            $table->string('min_co', 50)->nullable();
            $table->string('min_co2', 50)->nullable();
            $table->string('max_ratio', 50)->nullable();
            $table->string('max_co', 50)->nullable();
            $table->string('max_co2', 50)->nullable();
            $table->string('app_building_regulation', 50)->nullable();
            $table->string('commissioned_man_ins', 50)->nullable();
            $table->string('demonstrated_understood', 50)->nullable();
            $table->string('literature_including', 50)->nullable();
            $table->string('is_next_inspection', 50)->nullable();

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
        Schema::dropIfExists('gas_boiler_system_commissioning_checklist_appliances');
    }
};
