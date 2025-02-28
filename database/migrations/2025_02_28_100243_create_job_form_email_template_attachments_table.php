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
        Schema::create('job_form_email_template_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_form_email_template_id')->constrained('job_form_email_templates','id', 'fk_jfet_id')->cascadeOnDelete();
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
        Schema::dropIfExists('job_form_email_template_attachments');
    }
};
