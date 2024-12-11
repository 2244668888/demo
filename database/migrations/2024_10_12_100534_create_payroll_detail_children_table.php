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
        Schema::create('payroll_detail_children', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger(column: 'payroll_detail_id')->nullable();
            $table->foreign('payroll_detail_id')->references('id')->on('payroll_details')->nullable();

            $table->string('particular')->nullable();
            $table->string('value')->nullable();
            $table->string('checkbox')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_detail_children');
    }
};
