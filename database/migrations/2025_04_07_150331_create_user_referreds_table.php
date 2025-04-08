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
        Schema::create('user_referreds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_referral_code_id')->index()->constrained('user_referral_codes', 'id')->cascadeOnDelete();
            $table->foreignId('referrer_id')->index()->constrained('users', 'id')->cascadeOnDelete();
            $table->foreignId('referee_id')->index()->constrained('users', 'id')->cascadeOnDelete();
            $table->string('code');

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
        Schema::dropIfExists('user_referreds');
    }
};
