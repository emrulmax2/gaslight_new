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
        Schema::create('gas_safety_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->index()->constrained('customers', 'id')->cascadeOnDelete();
            $table->foreignId('customer_job_id')->index()->constrained('customer_jobs', 'id')->cascadeOnDelete();
            $table->foreignId('job_form_id')->index()->constrained('job_forms', 'id')->cascadeOnDelete();
            $table->smallInteger('cp_alarm_fitted')->nullable();
            $table->smallInteger('cp_alarm_satisfactory')->nullable();
            $table->string('satisfactory_visual_inspaction', 50)->nullable();
            $table->string('emergency_control_accessible', 50)->nullable();
            $table->string('satisfactory_gas_tightness_test', 50)->nullable();
            $table->string('equipotential_bonding_satisfactory', 50)->nullable();
            $table->string('co_alarm_fitted', 50)->nullable();
            $table->string('co_alarm_in_date', 50)->nullable();
            $table->string('co_alarm_test_satisfactory', 50)->nullable();
            $table->string('smoke_alarm_fitted', 50)->nullable();
            $table->text('fault_details')->nullable();
            $table->text('rectification_work_carried_out')->nullable();
            $table->text('details_work_carried_out')->nullable();
            $table->string('flue_cap_put_back', 50)->nullable();
            $table->date('inspection_date')->nullable();
            $table->date('next_inspection_date')->nullable();
            $table->string('received_by')->nullable();
            $table->foreignId('relation_id')->nullable()->index()->constrained('relations', 'id')->cascadeOnDelete();
            $table->enum('status', ['Draft', 'Approved', 'Cancelled'])->default('Draft');
            

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
        Schema::dropIfExists('gas_safety_records');
    }
};
