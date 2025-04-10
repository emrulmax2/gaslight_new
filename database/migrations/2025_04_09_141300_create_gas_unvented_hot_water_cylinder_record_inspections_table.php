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
        Schema::create('gas_unvented_hot_water_cylinder_record_inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gas_unvented_hot_water_cylinder_record_id')->index('fkguhwcrinsp_ind')->constrained('gas_unvented_hot_water_cylinder_records', 'id', 'fkguhwcrinsp_id')->cascadeOnDelete();
            
            $table->string('system_opt_pressure')->nullable();
            $table->string('opt_presure_exp_vsl')->nullable();
            $table->string('opt_presure_exp_vlv')->nullable();
            $table->string('tem_relief_vlv')->nullable();
            $table->string('opt_temperature')->nullable();
            $table->string('combined_temp_presr')->nullable();
            $table->string('max_circuit_presr')->nullable();
            $table->string('flow_temp')->nullable();
            $table->string('d1_mormal_size')->nullable();
            $table->string('d1_length')->nullable();
            $table->string('d1_discharges_no')->nullable();
            $table->string('d1_manifold_size')->nullable();
            $table->string('d1_is_tundish_install_same_location', 50)->nullable();
            $table->string('d1_is_tundish_visible', 50)->nullable();
            $table->string('d1_is_auto_dis_intall', 50)->nullable();
            $table->string('d2_mormal_size')->nullable();
            $table->string('d2_pipework_material')->nullable();
            $table->string('d2_minimum_v_length', 50)->nullable();
            $table->string('d2_fall_continuously', 50)->nullable();
            $table->string('d2_termination_method', 50)->nullable();
            $table->string('d2_termination_satisfactory', 50)->nullable();
            $table->text('comments')->nullable();

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
        Schema::dropIfExists('gas_unvented_hot_water_cylinder_record_inspections');
    }
};
