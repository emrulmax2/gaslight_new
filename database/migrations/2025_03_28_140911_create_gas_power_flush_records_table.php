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
        Schema::create('gas_power_flush_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->index('fkgpfr_c_ind')->constrained('customers', 'id', 'fkgpfr_c_id')->cascadeOnDelete();
            $table->foreignId('customer_job_id')->index('fkgpfr_cj_ind')->constrained('customer_jobs', 'id', 'fkgpfr_cj_id')->cascadeOnDelete();
            $table->foreignId('job_form_id')->index('jkgpfr_jf_ind')->constrained('job_forms', 'id', 'jkgpfr_jf_id')->cascadeOnDelete();
            
            $table->string('certificate_number', 50)->nullable();
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
        Schema::dropIfExists('gas_power_flush_records');
    }
};
