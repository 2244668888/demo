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
        Schema::create('material_plannings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dppd_id')->nullable();
            $table->foreign('dppd_id')->references('id')->on('daily_production_planning_details');
            $table->string('product_id')->nullable();
            $table->string('request_date')->nullable();
            $table->string('ref_no')->nullable();
            $table->string('department_id')->nullable();
            $table->string('process')->nullable();
            $table->string('plan_date')->nullable();
            $table->string('total_planned_qty')->nullable();
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
        Schema::dropIfExists('material_plannings');
    }
};
