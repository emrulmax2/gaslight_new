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
        Schema::table('records', function (Blueprint $table) {
            $table->foreignId('customer_property_id')->nullable()->after('job_form_id')->index()->constrained('customer_properties')->nullOnDelete();
            $table->foreignId('customer_property_occupant_id')->nullable()->after('customer_property_id')->index()->constrained('customer_property_occupants', 'id', 'fkr_cpoid')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('records', function (Blueprint $table) {
            //
        });
    }
};
