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
        Schema::create('gas_warning_notice_appliances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gas_warning_notice_id')->index()->constrained('gas_warning_notices', 'id')->cascadeOnDelete();
            $table->integer('appliance_serial')->nullable();
            $table->foreignId('appliance_location_id')->nullable()->index()->constrained('appliance_locations', 'id')->cascadeOnDelete();
            $table->foreignId('boiler_brand_id')->nullable()->index()->constrained('boiler_brands', 'id')->cascadeOnDelete();
            $table->string('model')->nullable();
            $table->foreignId('appliance_type_id')->nullable()->index()->constrained('appliance_types', 'id')->cascadeOnDelete();
            $table->string('gc_no')->nullable();
            $table->string('serial_no')->nullable();
            $table->foreignId('gas_warning_classification_id')->nullable()->index('fk_gwna_gwc')->constrained('gas_warning_classifications', 'id', 'fk_gwna_gwc')->cascadeOnDelete();
            $table->string('gas_escape_issue')->nullable();
            $table->string('pipework_issue', 50)->nullable();
            $table->string('ventilation_issue', 50)->nullable();
            $table->string('meter_issue', 50)->nullable();
            $table->string('chimeny_issue', 100)->nullable();
            $table->text('fault_details')->nullable();
            $table->text('action_taken')->nullable();
            $table->text('actions_required')->nullable();
            $table->string('reported_to_hse', 50)->nullable();
            $table->string('reported_to_hde', 50)->nullable();
            $table->string('left_on_premisies', 50)->nullable();

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
        Schema::dropIfExists('gas_warning_notice_appliances');
    }
};
