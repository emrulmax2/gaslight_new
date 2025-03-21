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
        Schema::table('gas_safety_records', function (Blueprint $table) {
            $table->string('certificate_number', 50)->nullable()->after('job_form_id');
        });
        DB::statement("ALTER TABLE gas_safety_records CHANGE COLUMN status status ENUM('Draft', 'Approved', 'Approved & Sent', 'Cancelled') NOT NULL DEFAULT 'Draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gas_safety_records', function (Blueprint $table) {
            //
        });
    }
};
