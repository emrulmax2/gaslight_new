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
        Schema::create('record_inspection_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('record_id')->index()->constrained('records')->cascadeOnDelete();
            $table->date('inspection_date');
            $table->timestamp('sent_at')->nullable();

            $table->foreignId('created_by')->nullable()->index()->constrained('users')->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->index()->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['record_id', 'inspection_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('record_inspection_notifications');
    }
};
