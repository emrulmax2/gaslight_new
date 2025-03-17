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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->index()->constrained('customers', 'id')->cascadeOnDelete();
            $table->foreignId('customer_job_id')->index()->constrained('customer_jobs', 'id')->cascadeOnDelete();
            $table->foreignId('job_form_id')->index()->constrained('job_forms', 'id')->cascadeOnDelete();
            $table->string('invoice_number', 50);
            $table->date('issued_date')->nullable();
            $table->string('reference_no', 50)->nullable();
            $table->smallInteger('non_vat_invoice')->nullable();
            $table->string('vat_number', 100)->nullable();
            $table->double('advance_amount', 10, 2)->nullable();
            $table->foreignId('payment_method_id')->nullable()->index()->constrained('payment_methods', 'id')->cascadeOnDelete();
            $table->date('advance_date')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('invoices');
    }
};
