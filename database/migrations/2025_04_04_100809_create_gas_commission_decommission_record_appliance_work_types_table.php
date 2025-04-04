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
        Schema::create('gas_commission_decommission_record_appliance_work_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gas_commission_decommission_record_appliance_id')->index('fkcdra_ind')->constrained('gas_commission_decommission_record_appliances', 'id', 'fkcdra_id')->cascadeOnDelete();
            $table->foreignId('commission_decommission_work_type_id')->index('fkcdwt_ind')->constrained('commission_decommission_work_types', 'id', 'fkcdwt_id')->cascadeOnDelete();
            
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gas_commission_decommission_record_appliance_work_types');
    }
};
