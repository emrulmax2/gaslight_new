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
        Schema::create('user_pricing_package_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->constrained('users', 'id')->cascadeOnDelete();
            $table->foreignId('user_pricing_package_id')->index('fkuppi_upp_ind')->constrained('user_pricing_packages', 'id', 'fkuppi_upp_id')->cascadeOnDelete();
            $table->string('invoice_id', 191)->nullable();
            $table->date('start')->nullable();
            $table->date('end')->nullable();
            $table->enum('status', ['trialing', 'active', 'incomplete', 'incomplete_expired', 'past_due', 'canceled', 'unpaid', 'paused']);

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
        Schema::dropIfExists('user_pricing_package_invoices');
    }
};
