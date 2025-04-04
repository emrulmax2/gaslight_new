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
        Schema::create('gas_power_flush_record_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gas_power_flush_record_id')->index('fkgpfrc_ind')->constrained('gas_power_flush_records', 'id', 'fkgpfrc_id')->cascadeOnDelete();
            $table->foreignId('powerflush_system_type_id')->nullable()->index('fkpfst_ind')->constrained('powerflush_system_types', 'id', 'fkpfst_id')->cascadeOnDelete();

            $table->foreignId('boiler_brand_id')->nullable()->index('fkgbb_ind')->constrained('boiler_brands', 'id', 'fkgbb_id')->cascadeOnDelete();
            $table->string('radiators', 50)->nullable();
            $table->string('pipework', 50)->nullable();
            $table->foreignId('appliance_type_id')->nullable()->index('fkgapt_ind')->constrained('appliance_types', 'id', 'fkgapt_id')->cascadeOnDelete();
            $table->foreignId('appliance_location_id')->nullable()->index('fkgloc_ind')->constrained('appliance_locations', 'id', 'fkgloc_id')->cascadeOnDelete();
            $table->string('serial_no', 50)->nullable();
            $table->foreignId('powerflush_cylinder_type_id')->nullable()->index('fkgpfct_ind')->constrained('powerflush_cylinder_types', 'id', 'fkgpfct_id')->cascadeOnDelete();
            $table->foreignId('powerflush_pipework_type_id')->nullable()->index('fkgppw_ind')->constrained('powerflush_pipework_types', 'id', 'fkgppw_id')->cascadeOnDelete();
            $table->string('twin_radiator_vlv_fitted', 50)->nullable();
            $table->string('completely_warm_on_fired', 50)->nullable();
            $table->string('circulation_for_all_readiators', 50)->nullable();
            $table->string('suffifiently_sound', 50)->nullable();
            $table->foreignId('powerflush_circulator_pump_location_id')->nullable()->index('fkpcpl_ind')->constrained('powerflush_circulator_pump_location_id', 'id', 'fkpcpl_id')->cascadeOnDelete();
            $table->string('number_of_radiators', 50)->nullable();
            $table->foreignId('radiator_type_id')->nullable()->index('fkgrdt_ind')->constrained('radiator_type_id', 'id', 'fkgrdt_id')->cascadeOnDelete();
            $table->string('getting_warm', 50)->nullable();
            $table->string('are_trvs_fitted', 50)->nullable();
            $table->string('sign_of_neglect', 50)->nullable();
            $table->string('radiator_open_fully', 50)->nullable();
            $table->string('number_of_valves', 50)->nullable();
            $table->string('valves_located', 50)->nullable();
            $table->string('fe_tank_location', 50)->nullable();
            $table->string('fe_tank_checked', 50)->nullable();
            $table->string('fe_tank_condition', 50)->nullable();
            $table->foreignId('color_id')->nullable()->index('fkcolor_ind')->constrained('colors', 'id', 'fkcolor_id')->cascadeOnDelete();
            $table->foreignId('before_color_id')->nullable()->index('fkbcolor_ind')->constrained('colors', 'id', 'fkbcolor_id')->cascadeOnDelete();
            $table->string('mw_ph', 50)->nullable();
            $table->string('mw_chloride', 50)->nullable();
            $table->string('mw_hardness', 50)->nullable();
            $table->string('mw_inhibitor', 50)->nullable();
            $table->string('bpf_ph', 50)->nullable();
            $table->string('bpf_chloride', 50)->nullable();
            $table->string('bpf_hardness', 50)->nullable();
            $table->string('bpf_inhibitor', 50)->nullable();
            $table->string('apf_ph', 50)->nullable();
            $table->string('apf_chloride', 50)->nullable();
            $table->string('apf_hardness', 50)->nullable();
            $table->string('apf_inhibitor', 50)->nullable();
            $table->string('mw_tds_reading', 50)->nullable();
            $table->string('bf_tds_reading', 50)->nullable();
            $table->string('af_tds_reading', 50)->nullable();

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
        Schema::dropIfExists('gas_power_flush_record_checklists');
    }
};
