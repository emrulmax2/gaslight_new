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
        Schema::create('existing_record_drafts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->index()->constrained('customers', 'id')->cascadeOnDelete();
            $table->foreignId('customer_job_id')->index()->constrained('customer_jobs', 'id')->cascadeOnDelete();
            $table->foreignId('job_form_id')->index()->constrained('job_forms', 'id')->cascadeOnDelete();
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();

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
        Schema::dropIfExists('existing_record_drafts');
    }
};
