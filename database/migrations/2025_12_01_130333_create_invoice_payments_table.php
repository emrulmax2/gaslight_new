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
        Schema::create('invoice_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->nullable()->index()->constrained('invoices')->cascadeOnDelete();
            $table->date('payment_date')->nullable();
            $table->foreignId('payment_method_id')->nullable()->index()->constrained('payment_methods', 'id', 'fkpmi_id')->nullOnDelete();
            $table->double('amount', 10, 2)->default(0);

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
        Schema::dropIfExists('invoice_payments');
    }
};
