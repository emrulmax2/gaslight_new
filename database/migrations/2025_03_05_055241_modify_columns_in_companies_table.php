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
        Schema::table('companies', function (Blueprint $table) {
            $table->string('vat_number')->nullable()->after('business_type');
            $table->tinyInteger('display_company_name')->nullable()->after('vat_number');
            $table->string('gas_safe_registration_no')->nullable()->after('display_company_name');
            $table->string('registration_no')->nullable()->after('gas_safe_registration_no');
            $table->string('registration_body_for')->nullable()->after('registration_no');
            $table->string('registration_body_for_legionella')->nullable()->after('registration_body_for');
            $table->string('registration_body_no_for_legionella')->nullable()->after('registration_body_for_legionella');
            $table->string('company_web_site')->nullable()->after('registration_body_no_for_legionella');
            $table->string('building_or_no')->nullable()->after('company_web_site');
            $table->string('company_tagline')->nullable()->after('building_or_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'vat_number',
                'display_company_name',
                'gas_safe_registration_no',
                'registration_no',
                'registration_body_for',
                'registration_body_for_legionella',
                'registration_body_no_for_legionella',
                'company_web_site',
                'building_or_no',
                'company_tagline',
            ]);
        });
    }
};
