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
        Schema::dropIfExists('existing_record_drafts');
        Schema::dropIfExists('gas_boiler_system_commissioning_checklists');
        Schema::dropIfExists('gas_boiler_system_commissioning_checklist_appliances');
        Schema::dropIfExists('gas_breakdown_records');
        Schema::dropIfExists('gas_breakdown_record_appliances');
        Schema::dropIfExists('gas_commission_decommission_records');
        Schema::dropIfExists('gas_commission_decommission_record_appliances');
        Schema::dropIfExists('gas_commission_decommission_record_appliance_work_types');
        Schema::dropIfExists('gas_job_sheet_records');
        Schema::dropIfExists('gas_job_sheet_record_details');
        Schema::dropIfExists('gas_job_sheet_record_documents');
        Schema::dropIfExists('gas_landlord_safety_records');
        Schema::dropIfExists('gas_landlord_safety_record_appliances');
        Schema::dropIfExists('gas_power_flush_records');
        Schema::dropIfExists('gas_power_flush_record_checklists');
        Schema::dropIfExists('gas_power_flush_record_rediators');
        Schema::dropIfExists('gas_safety_records');
        Schema::dropIfExists('gas_safety_record_appliances');
        Schema::dropIfExists('gas_service_records');
        Schema::dropIfExists('gas_service_record_appliances');
        Schema::dropIfExists('gas_unvented_hot_water_cylinder_records');
        Schema::dropIfExists('gas_unvented_hot_water_cylinder_record_inspections');
        Schema::dropIfExists('gas_unvented_hot_water_cylinder_record_systems');
        Schema::dropIfExists('gas_warning_notices');
        Schema::dropIfExists('gas_warning_notice_appliances');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('quotes');
        Schema::dropIfExists('quote_items');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
