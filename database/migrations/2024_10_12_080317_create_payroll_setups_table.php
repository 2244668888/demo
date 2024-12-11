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
        Schema::create('payroll_setups', function (Blueprint $table) {
            $table->id();

            $table->string('hrdf')->nullable();
            $table->string('hrdf_per')->nullable();

            $table->string('paysilp')->nullable();
            $table->string('paysilp_remarks')->nullable();


            $table->string('kwsp')->nullable();
            // CATEGORY 1  (Base Salary < 5k)
            $table->string('kwsp_category_1_employee_per')->nullable();
            $table->string('kwsp_category_1_employer_per')->nullable();
            // CATEGORY 2  (Base Salary > 5k)
            $table->string('kwsp_category_2_employee_per')->nullable();
            $table->string('kwsp_category_2_employer_per')->nullable();

            $table->string('socso')->nullable();
            $table->string('socso_employee_per')->nullable();
            $table->string('socso_employer_per')->nullable();

            $table->string('eis')->nullable();
            $table->string('eis_employee_per')->nullable();
            $table->string('eis_employer_per')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_setups');
    }
};
