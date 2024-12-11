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
            $table->string('payment_voucher_no')->after('payment_status')->nullable();
            $table->string('issue_date')->after('payment_voucher_no')->nullable();
            $table->string('issued_by')->after('issue_date')->nullable();
            $table->string('pv_status')->after('issued_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['payment_voucher_no', 'issue_date', 'issued_by', 'pv_status']);
        });
    }
};
