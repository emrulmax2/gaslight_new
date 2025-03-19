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
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->index()->constrained('customers', 'id')->cascadeOnDelete();
            $table->foreignId('customer_job_id')->index()->constrained('customer_jobs', 'id')->cascadeOnDelete();
            $table->foreignId('job_form_id')->index()->constrained('job_forms', 'id')->cascadeOnDelete();
            $table->string('quote_number', 50);
            $table->date('issued_date')->nullable();
            $table->string('reference_no', 50)->nullable();
            $table->smallInteger('non_vat_quote')->nullable();
            $table->string('vat_number', 100)->nullable();
            $table->text('notes')->nullable();
            $table->text('payment_term')->nullable();
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
        Schema::dropIfExists('quotes');
    }
};
