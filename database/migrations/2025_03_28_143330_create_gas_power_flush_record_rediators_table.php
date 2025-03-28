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
        Schema::create('gas_power_flush_record_rediators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gas_power_flush_record_id')->index('fkgpfrc_ind')->constrained('gas_power_flush_records', 'id', 'fkgpfrc_id')->cascadeOnDelete();
            
            $table->string('rediator_location')->nullable();
            $table->string('tmp_b_top', 50)->nullable();
            $table->string('tmp_b_bottom', 50)->nullable();
            $table->string('tmp_b_left', 50)->nullable();
            $table->string('tmp_b_right', 50)->nullable();
            $table->string('tmp_a_top', 50)->nullable();
            $table->string('tmp_a_bottom', 50)->nullable();
            $table->string('tmp_a_left', 50)->nullable();
            $table->string('tmp_a_right', 50)->nullable();

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
        Schema::dropIfExists('gas_power_flush_record_rediators');
    }
};
