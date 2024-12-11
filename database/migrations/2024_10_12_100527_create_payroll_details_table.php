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
        Schema::create('payroll_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger(column: 'payroll_id')->nullable();
            $table->foreign('payroll_id')->references('id')->on('payrolls')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->nullable();
            $table->string('net_salary')->nullable();
            $table->string('gross_salary')->nullable();
            $table->string('total_deduction')->nullable();
            $table->string('company_contribution')->nullable();
            $table->string('ref_no')->nullable();
            // INCLUDE IN PAYSLIP
            $table->string('hrdf')->nullable();
            $table->string('kwsp')->nullable();
            $table->string('socso')->nullable();
            $table->string('eis')->nullable();

            $table->string('date')->nullable();

            $table->string('attachment')->nullable();
            $table->longText('remarks')->nullable();



            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_details');
    }
};
