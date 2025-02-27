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
        Schema::create('customer_job_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->index()->constrained()->cascadeOnDelete();
            $table->foreignId('customer_job_id')->index()->constrained()->cascadeOnDelete();
            $table->string('display_file_name')->nullable();
            $table->string('current_file_name')->nullable();
            $table->string('doc_type', 50)->nullable();
            $table->string('disk_type', 50)->nullable();
            $table->text('path')->nullable();

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
        Schema::dropIfExists('customer_job_documents');
    }
};
