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
        Schema::table('payroll_setups', function (Blueprint $table) {
            // CATEGORY 3  (Base Salary < 5k)
            $table->string('kwsp_category_3_employee_per')->nullable();
            $table->string('kwsp_category_3_employer_per')->nullable();
            // CATEGORY 4  (Base Salary > 5k)
            $table->string('kwsp_category_4_employee_per')->nullable();
            $table->string('kwsp_category_4_employer_per')->nullable();

            $table->string('eve_employee')->nullable();
            $table->string('eve_employee_per')->nullable();

            $table->string('eve_employer')->nullable();
            $table->string('eve_employer_per')->nullable();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payroll_setups', function (Blueprint $table) {
            // CATEGORY 3  (Base Salary < 5k)
            $table->string('kwsp_category_3_employee_per')->nullable();
            $table->string('kwsp_category_3_employer_per')->nullable();
            // CATEGORY 4  (Base Salary > 5k)
            $table->string('kwsp_category_4_employee_per')->nullable();
            $table->string('kwsp_category_4_employer_per')->nullable();

            $table->string('eve_employee')->nullable();
            $table->string('eve_employee_per')->nullable();

            $table->string('eve_employer')->nullable();
            $table->string('eve_employer_per')->nullable();
        });
    }
};
