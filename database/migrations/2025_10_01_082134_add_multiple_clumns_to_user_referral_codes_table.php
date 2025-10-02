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
        Schema::table('user_referral_codes', function (Blueprint $table) {
            $table->smallInteger('is_global')->default(0)->after('code');
            $table->integer('no_of_days')->nullable()->after('is_global');
            $table->date('expiry_date')->nullable()->after('no_of_days');
            $table->integer('max_no_of_use')->nullable()->after('expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_referral_codes', function (Blueprint $table) {
            //
        });
    }
};
