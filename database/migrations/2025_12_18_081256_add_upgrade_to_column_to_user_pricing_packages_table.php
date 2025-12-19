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
        Schema::table('user_pricing_packages', function (Blueprint $table) {
            $table->foreignId('upgrade_to')->nullable()->after('requested_at')->index()->constrained('pricing_packages', 'id')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_pricing_packages', function (Blueprint $table) {
            //
        });
    }
};
