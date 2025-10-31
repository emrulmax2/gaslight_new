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
        Schema::create('job_form_base_email_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_form_id')->index()->constrained()->cascadeOnDelete();
            $table->string('subject');
            $table->text('content');

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
        Schema::dropIfExists('job_form_base_email_templates');
    }
};
