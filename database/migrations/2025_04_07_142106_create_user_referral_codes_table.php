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
        Schema::create('user_referral_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->constrained('users', 'id')->cascadeOnDelete();
            $table->string('code')->unique();
            $table->smallInteger('active')->default(1)->comment('0=Inactive, 1=Active');

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
        Schema::dropIfExists('user_referral_codes');
    }
};
