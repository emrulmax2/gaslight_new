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
        Schema::create('invoice_cancel_reasons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->smallInteger('active')->default(1)->comment('0=inactive,1=active');
            
            $table->foreignId('created_by')->index()->constrained('users')->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->index()->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_cancel_reasons');
    }
};
