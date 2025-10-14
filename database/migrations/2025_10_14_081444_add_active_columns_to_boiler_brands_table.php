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
        Schema::table('boiler_brands', function (Blueprint $table) {
            $table->smallInteger('active')->default(1)->comment('0=Inactive, 1=Active')->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boiler_brands', function (Blueprint $table) {
            //
        });
    }
};
