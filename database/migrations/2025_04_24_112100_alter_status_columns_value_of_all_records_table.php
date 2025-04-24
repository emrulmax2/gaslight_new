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
        DB::statement("ALTER TABLE invoices CHANGE COLUMN status status ENUM('Draft', 'Approved', 'Approved & Sent', 'Cancelled') NOT NULL DEFAULT 'Draft'");
        DB::statement("ALTER TABLE quotes CHANGE COLUMN status status ENUM('Draft', 'Approved', 'Approved & Sent', 'Cancelled') NOT NULL DEFAULT 'Draft'");
        DB::statement("ALTER TABLE gas_boiler_system_commissioning_checklists CHANGE COLUMN status status ENUM('Draft', 'Approved', 'Approved & Sent', 'Cancelled') NOT NULL DEFAULT 'Draft'");
        DB::statement("ALTER TABLE gas_breakdown_records CHANGE COLUMN status status ENUM('Draft', 'Approved', 'Approved & Sent', 'Cancelled') NOT NULL DEFAULT 'Draft'");
        DB::statement("ALTER TABLE gas_power_flush_records CHANGE COLUMN status status ENUM('Draft', 'Approved', 'Approved & Sent', 'Cancelled') NOT NULL DEFAULT 'Draft'");
        DB::statement("ALTER TABLE gas_service_records CHANGE COLUMN status status ENUM('Draft', 'Approved', 'Approved & Sent', 'Cancelled') NOT NULL DEFAULT 'Draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
