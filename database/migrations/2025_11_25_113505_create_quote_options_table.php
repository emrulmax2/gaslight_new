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
        Schema::create('quote_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->nullable()->index()->constrained('quotes')->cascadeOnDelete();
            $table->string('name');
            $table->json('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_options');
    }
};
