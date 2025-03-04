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
        Schema::table('staff', function (Blueprint $table) {
            $table->string('gas_safe_id_card')->nullable();
            $table->string('oil_registration_number')->nullable();
            $table->string('installer_ref_no')->nullable();
            $table->string('address_line_1')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('state')->nullable()->change();
            $table->string('country')->nullable()->change();
            $table->string('zip')->nullable()->change();
            
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn('gas_safe_id_card');
            $table->dropColumn('oil_registration_number');
            $table->dropColumn('installer_ref_no');
            $table->string('address_line_1')->nullable(false)->change();
            $table->string('city')->nullable(false)->change();
            $table->string('state')->nullable(false)->change();
            $table->string('country')->nullable(false)->change();
            $table->string('zip')->nullable(false)->change();
            

        });
    }
};
