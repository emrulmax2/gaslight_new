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
        Schema::table('customer_jobs', function (Blueprint $table) {
            $table->enum('status', ['Due', 'Completed', 'Cancelled'])->default('Due')->after('estimated_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_jobs', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
