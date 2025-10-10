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
        Schema::create('records', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')->nullable()->index()->constrained('companies')->cascadeOnDelete();
            $table->foreignId('customer_id')->index()->constrained('customers')->cascadeOnDelete();
            $table->foreignId('customer_job_id')->index()->constrained('customer_jobs')->cascadeOnDelete();
            $table->foreignId('job_form_id')->index()->constrained('job_forms')->cascadeOnDelete();
            $table->string('certificate_number', 50)->nullable();
            $table->date('inspection_date')->nullable();
            $table->date('next_inspection_date')->nullable();
            $table->string('received_by')->nullable();
            $table->foreignId('relation_id')->nullable()->index()->constrained('relations')->cascadeOnDelete();
            $table->enum('status', ['Draft', 'Approved', 'Cancelled'])->default('Draft');

            $table->foreignId('created_by')->index()->constrained('users')->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->index()->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('records');
    }
};
