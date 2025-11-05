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
        Schema::create('record_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('record_id')->nullable()->index()->constrained('records')->cascadeOnDelete();
            $table->enum('action', ['Created', 'Updated', 'Approved', 'Email Sent', 'Invoice Created'])->default('Created')->nullable();
            $table->string('subject')->nullable();
            $table->text('content')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('created_by')->index()->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('record_histories');
    }
};
