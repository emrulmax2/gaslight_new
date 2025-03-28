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
        Schema::table('gas_breakdown_record_appliances', function (Blueprint $table) {
            $table->string('performance_co', 50)->nullable()->after('performance_analyser_ratio');
            $table->string('performance_co2', 50)->nullable()->after('performance_co');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gas_breakdown_record_appliances', function (Blueprint $table) {
            //
        });
    }
};
