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
        Schema::create('gas_breakdown_record_appliances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gas_breakdown_record_id')->index()->constrained('gas_breakdown_records', 'id')->cascadeOnDelete();
            $table->integer('appliance_serial')->nullable();
            $table->foreignId('appliance_location_id')->nullable()->index()->constrained('appliance_locations', 'id')->cascadeOnDelete();
            $table->foreignId('boiler_brand_id')->nullable()->index()->constrained('boiler_brands', 'id')->cascadeOnDelete();
            $table->string('model')->nullable();
            $table->foreignId('appliance_type_id')->nullable()->index()->constrained('appliance_types', 'id')->cascadeOnDelete();
            $table->string('gc_no')->nullable();
            $table->string('serial_no')->nullable();

            $table->string('performance_analyser_ratio', 50)->nullable();
            $table->string('opt_correctly', 50)->nullable();
            $table->string('conf_safety_standards', 50)->nullable();
            $table->string('notice_exlained', 50)->nullable();
            $table->string('flueing_is_safe', 50)->nullable();
            $table->string('ventilation_is_safe', 50)->nullable();
            $table->string('emition_combustion_test', 50)->nullable();
            $table->string('burner_pressure', 50)->nullable();
            $table->string('location_of_fault', 50)->nullable();
            $table->string('fault_resolved', 50)->nullable();
            $table->string('parts_fitted', 50)->nullable();
            $table->text('fitted_parts_name')->nullable();
            $table->string('parts_required', 50)->nullable();
            $table->text('required_parts_name')->nullable();
            $table->string('monoxide_alarm_fitted', 50)->nullable();
            $table->string('is_safe', 50)->nullable();
            $table->string('parts_available', 50)->nullable();
            $table->string('recommend_replacement', 50)->nullable();
            $table->string('magnetic_filter_fitted', 50)->nullable();
            $table->string('improvement_recommended', 50)->nullable();
            $table->text('enginner_comments')->nullable();

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
        Schema::dropIfExists('gas_breakdown_record_appliances');
    }
};
