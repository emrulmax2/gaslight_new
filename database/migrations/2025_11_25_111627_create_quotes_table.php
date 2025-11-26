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
            $table->foreignId('company_id')->nullable()->index()->constrained('companies')->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->index()->constrained('customers')->cascadeOnDelete();
            $table->foreignId('customer_job_id')->nullable()->index()->constrained('customer_jobs')->cascadeOnDelete();
            $table->foreignId('job_form_id')->nullable()->index()->constrained('job_forms')->cascadeOnDelete();
            $table->foreignId('customer_property_id')->nullable()->index()->constrained('customer_properties')->cascadeOnDelete();
            $table->foreignId('customer_property_occupant_id')->nullable()->index()->constrained('customer_property_occupants')->cascadeOnDelete();

            $table->string('quote_number', 50)->nullable();
            $table->date('issued_date')->nullable();
            $table->date('expire_date')->nullable();
            $table->enum('status', ['Draft', 'Send', 'Accepted', 'Cancelled', 'Expired'])->default('Draft');
            
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
        Schema::dropIfExists('quotes');
    }
};
