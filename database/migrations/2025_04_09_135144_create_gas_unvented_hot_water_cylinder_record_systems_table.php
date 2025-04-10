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
        Schema::create('gas_unvented_hot_water_cylinder_record_systems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gas_unvented_hot_water_cylinder_record_id')->index('fkguhwcrs_ind')->constrained('gas_unvented_hot_water_cylinder_records', 'id', 'fkguhwcrs_id')->cascadeOnDelete();
            
            $table->string('type')->nullable();
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->string('location')->nullable();
            $table->string('serial_no')->nullable();
            $table->string('gc_number')->nullable();
            $table->string('direct_or_indirect', 50)->nullable();
            $table->string('boiler_solar_immersion')->nullable();
            $table->string('capacity')->nullable();
            $table->string('warning_label_attached', 50)->nullable();
            $table->string('water_pressure')->nullable();
            $table->string('flow_rate')->nullable();
            $table->string('fully_commissioned')->nullable();

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
        Schema::dropIfExists('gas_unvented_hot_water_cylinder_record_systems');
    }
};
