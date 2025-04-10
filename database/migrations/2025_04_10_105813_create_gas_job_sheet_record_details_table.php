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
        Schema::create('gas_job_sheet_record_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gas_job_sheet_record_id')->index('fkjsheet_ind')->constrained('gas_job_sheet_records', 'id', 'fkjsheet_id')->cascadeOnDelete();
            
            $table->date('date')->nullable();
            $table->text('job_note')->nullable();
            $table->text('spares_required')->nullable();
            $table->text('job_ref')->nullable();
            $table->text('arrival_time')->nullable();
            $table->text('departure_time')->nullable();
            $table->text('hours_used')->nullable();
            $table->text('awaiting_parts')->nullable();
            $table->string('job_completed', 50)->nullable();

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
        Schema::dropIfExists('gas_job_sheet_record_details');
    }
};
