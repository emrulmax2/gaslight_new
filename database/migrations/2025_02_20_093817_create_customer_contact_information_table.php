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
        Schema::create('customer_contact_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->index()->constrained()->cascadeOnDelete();
            $table->string('mobile', 50)->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('other_email')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_contact_information');
    }
};
