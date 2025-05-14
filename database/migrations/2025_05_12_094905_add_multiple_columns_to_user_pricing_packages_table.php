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
        Schema::table('user_pricing_packages', function (Blueprint $table) {
            $table->smallInteger('cancellation_requested')->default('0')->nullable()->after('active');
            $table->unsignedBigInteger('requested_by')->nullable()->after('cancellation_requested');
            $table->dateTime('requested_at')->nullable()->after('requested_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_pricing_packages', function (Blueprint $table) {
            //
        });
    }
};
