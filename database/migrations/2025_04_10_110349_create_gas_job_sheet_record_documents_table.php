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
        Schema::create('gas_job_sheet_record_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gas_job_sheet_record_id')->index('fkjsheetdoc_ind')->constrained('gas_job_sheet_records', 'id', 'fkjsheetdoc_id')->cascadeOnDelete();
            
            $table->string('name')->nullable();
            $table->string('path')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('size')->nullable();

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
        Schema::dropIfExists('gas_job_sheet_record_documents');
    }
};
