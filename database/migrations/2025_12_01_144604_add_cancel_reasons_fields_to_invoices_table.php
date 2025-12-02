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
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('invoice_cancel_reason_id')->nullable()->after('pay_status')->index()->constrained('invoice_cancel_reasons', 'id', 'fkicr_id')->nullOnDelete();
            $table->text('cancel_reason_note')->nullable()->after('invoice_cancel_reason_id');
            $table->foreignId('cancelled_by')->nullable()->after('cancel_reason_note')->index()->constrained('users')->nullOnDelete();
            $table->dateTime('cancelled_at')->nullable()->after('cancelled_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            //
        });
    }
};
