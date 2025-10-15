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
        Schema::table('customer_properties', function (Blueprint $table) {
            $table->smallInteger('has_occupants')->default(0)->after('note');
            $table->dropColumn(['occupant_name', 'occupant_email', 'occupant_phone', 'due_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_properties', function (Blueprint $table) {
            //
        });
    }
};
