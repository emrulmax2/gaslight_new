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
        Schema::create('gas_commission_decommission_record_appliances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gas_commission_decommission_record_id')->index('fkgcdr_ind')->constrained('gas_commission_decommission_records', 'id', 'fkgcdr_id')->cascadeOnDelete();
            $table->integer('appliance_serial')->nullable();
            
            $table->text('details_work_carried_out')->nullable();
            $table->text('details_work_required')->nullable();
            $table->string('is_safe_to_use', 50)->nullable();
            $table->string('have_labels_affixed', 50)->nullable();

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
        Schema::dropIfExists('gas_commission_decommission_record_appliances');
    }
};
