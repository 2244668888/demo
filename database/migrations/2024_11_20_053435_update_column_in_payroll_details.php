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
        Schema::table('payroll_details', function (Blueprint $table) {
            $table->string('eve_employer')->nullable();
            $table->string('eve_employee')->nullable();
            $table->string('eve_ot')->nullable();
            $table->string('eve_ot_allowance')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payroll_details', function (Blueprint $table) {
            $table->string('eve_employer')->nullable();
            $table->string('eve_employee')->nullable();
            $table->string('eve_ot')->nullable();
            $table->string('eve_ot_allowance')->nullable();
        });
    }
};
