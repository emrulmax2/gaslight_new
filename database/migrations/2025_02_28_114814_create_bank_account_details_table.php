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
        Schema::create('bank_account_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('Company_id')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('name_on_account')->nullable();
            $table->string('sort_code')->nullable();
            $table->string('account_number')->nullable();
            $table->string('payment_term')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_bank_account_details');
    }
};
