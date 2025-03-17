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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->index()->constrained('invoices', 'id')->cascadeOnDelete();
            $table->enum('type', ['Default', 'Discount'])->default('Default');
            $table->text('description')->nullable();
            $table->integer('units')->default(1);
            $table->double('unit_price', 10, 2)->nullable();
            $table->double('vat_rate', 10, 2)->nullable();
            $table->double('vat_amount', 10, 2)->nullable();

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
        Schema::dropIfExists('invoice_items');
    }
};
