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
        Schema::create('customer_properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->index()->constrained()->cascadeOnDelete();
            $table->string('address_line_1');
            $table->string('address_line_2');
            $table->string('postal_code');
            $table->string('state')->nullable();
            $table->string('city');
            $table->string('country')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->text('note')->nullable();

            $table->string('occupant_name')->nullable();
            $table->string('occupant_email')->nullable();
            $table->string('occupant_phone')->nullable();
            $table->date('due_date')->nullable();

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
        Schema::dropIfExists('customer_properties');
    }
};
