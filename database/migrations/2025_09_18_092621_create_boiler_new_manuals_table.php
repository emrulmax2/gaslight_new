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
        Schema::create('boiler_new_manuals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boiler_new_brand_id')->constrained()->onDelete('cascade');
            $table->string('gc_no')->nullable();
            $table->string('url')->nullable();
            $table->string('model')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('year_of_manufacture')->nullable();
            $table->string('document')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boiler_new_manuals');
    }
};
