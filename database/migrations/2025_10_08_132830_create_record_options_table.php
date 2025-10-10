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
        Schema::create('record_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('record_id')->nullable()->index()->constrained('records')->cascadeOnDelete();
            $table->foreignId('job_form_id')->nullable()->index()->constrained('job_forms')->nullOnDelete();
            $table->string('name');
            $table->json('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('record_options');
    }
};
