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
        Schema::create('customer_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->index()->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('customer_property_id')->nullable();
            $table->text('description')->nullable();
            $table->text('details')->nullable();
            $table->foreignId('customer_job_priority_id')->nullable()->index()->constrained()->cascadeOnDelete();
            $table->date('due_date');
            $table->foreignId('customer_job_status_id')->nullable()->index()->constrained()->cascadeOnDelete();
            $table->string('reference_no');
            $table->double('estimated_amount', 10, 2)->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_jobs');
    }
};
