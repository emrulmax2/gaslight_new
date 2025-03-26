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
        Schema::create('gas_service_record_appliances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gas_service_record_id')->index()->constrained('gas_service_records', 'id')->cascadeOnDelete();
            $table->integer('appliance_serial')->nullable();
            $table->foreignId('appliance_location_id')->nullable()->index()->constrained('appliance_locations', 'id')->cascadeOnDelete();
            $table->foreignId('boiler_brand_id')->nullable()->index()->constrained('boiler_brands', 'id')->cascadeOnDelete();
            $table->string('model')->nullable();
            $table->foreignId('appliance_type_id')->nullable()->index()->constrained('appliance_types', 'id')->cascadeOnDelete();
            $table->string('gc_no')->nullable();
            $table->string('serial_no')->nullable();
            
            $table->string('opt_pressure')->nullable();
            $table->string('rented_accommodation', 50)->nullable();
            $table->string('type_of_work_carried_out', 50)->nullable();
            $table->string('test_carried_out', 50)->nullable();
            $table->string('is_electricial_bonding', 50)->nullable();
            $table->string('low_analyser_ratio', 50)->nullable();
            $table->string('low_co', 50)->nullable();
            $table->string('low_co2', 50)->nullable();
            $table->string('high_analyser_ratio', 50)->nullable();
            $table->string('high_co', 50)->nullable();
            $table->string('high_co2', 50)->nullable();

            $table->string('heat_exchanger', 50)->nullable();
            $table->text('heat_exchanger_detail')->nullable();
            $table->string('burner_injectors', 50)->nullable();
            $table->text('burner_injectors_detail')->nullable();
            $table->string('flame_picture', 50)->nullable();
            $table->text('flame_picture_detail')->nullable();
            $table->string('ignition', 50)->nullable();
            $table->text('ignition_detail')->nullable();
            $table->string('electrics', 50)->nullable();
            $table->text('electrics_detail')->nullable();
            $table->string('controls', 50)->nullable();
            $table->text('controls_detail')->nullable();
            $table->string('leak_gas_water', 50)->nullable();
            $table->text('leak_gas_water_detail')->nullable();
            $table->string('seals', 50)->nullable();
            $table->text('seals_detail')->nullable();
            $table->string('pipework', 50)->nullable();
            $table->text('pipework_detail')->nullable();
            $table->string('fans', 50)->nullable();
            $table->text('fans_detail')->nullable();
            $table->string('fireplace', 50)->nullable();
            $table->text('fireplace_detail')->nullable();
            $table->string('closure_plate', 50)->nullable();
            $table->text('closure_plate_detail')->nullable();
            $table->string('allowable_location', 50)->nullable();
            $table->text('allowable_location_detail')->nullable();
            $table->string('boiler_ratio', 50)->nullable();
            $table->text('boiler_ratio_detail')->nullable();
            $table->string('stability', 50)->nullable();
            $table->text('stability_detail')->nullable();
            $table->string('return_air_ple', 50)->nullable();
            $table->text('return_air_ple_detail')->nullable();
            $table->string('ventillation', 50)->nullable();
            $table->text('ventillation_detail')->nullable();
            $table->string('flue_termination', 50)->nullable();
            $table->text('flue_termination_detail')->nullable();
            $table->string('smoke_pellet_flue_flow', 50)->nullable();
            $table->text('smoke_pellet_flue_flow_detail')->nullable();
            $table->string('smoke_pellet_spillage', 50)->nullable();
            $table->text('smoke_pellet_spillage_detail')->nullable();
            $table->string('working_pressure', 50)->nullable();
            $table->text('working_pressure_detail')->nullable();
            $table->string('savety_devices', 50)->nullable();
            $table->text('savety_devices_detail')->nullable();
            $table->string('gas_tightness', 50)->nullable();
            $table->text('gas_tightness_detail')->nullable();
            $table->string('expansion_vassel_checked', 50)->nullable();
            $table->text('expansion_vassel_checked_detail')->nullable();
            $table->string('other_regulations', 50)->nullable();
            $table->text('other_regulations_detail')->nullable();
            $table->string('is_safe_to_use', 50)->nullable();
            $table->string('instruction_followed', 50)->nullable();
            $table->text('work_required_note')->nullable();

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
        Schema::dropIfExists('gas_service_record_appliances');
    }
};
