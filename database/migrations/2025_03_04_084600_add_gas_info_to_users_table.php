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
        Schema::table('users', function (Blueprint $table) {
            $table->string('gas_safe_id_card')->nullable()->after('remember_token');
            $table->string('oil_registration_number')->nullable()->after('gas_safe_id_card');
            $table->string('installer_ref_no')->nullable()->after('oil_registration_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('gas_safe_id_card');
            $table->dropColumn('oil_registration_number');
            $table->dropColumn('installer_ref_no');
        });
    }
};
