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
        

        Schema::table('company_staff', function (Blueprint $table) {
            $table->dropForeign(['staff_id']);
        });

        Schema::rename('staffs', 'staff');

        Schema::table('company_staff', function (Blueprint $table) {
            $table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_staff', function (Blueprint $table) {
            $table->dropForeign(['staff_id']);
        });

        Schema::rename('staff', 'staffs');

        Schema::table('company_staff', function (Blueprint $table) {
            $table->foreign('staff_id')->references('id')->on('staffs')->onDelete('cascade');
        });
    }
};
