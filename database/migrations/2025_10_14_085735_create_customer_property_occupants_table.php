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
        Schema::create('customer_property_occupants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_property_id')->index()->constrained('customer_properties')->cascadeOnDelete();
            $table->string('occupant_name', 191)->nullable();
            $table->string('occupant_email')->nullable();
            $table->string('occupant_phone', 50)->nullable();
            $table->date('due_date')->nullable();
            $table->smallInteger('active')->default(1);

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
        Schema::dropIfExists('customer_property_occupants');
    }
};
