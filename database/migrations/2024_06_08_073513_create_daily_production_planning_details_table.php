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
        Schema::create('daily_production_planning_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dpp_id')->nullable();
            $table->foreign('dpp_id')->references('id')->on('daily_production_plannings');
            $table->string('product_id')->nullable();
            $table->string('pro_order_no')->nullable();
            $table->string('planned_date')->nullable();
            $table->string('op_name')->nullable();
            $table->string('shift')->nullable();
            $table->string('spec_break')->nullable();
            $table->string('planned_qty')->nullable();
            $table->string('machine')->nullable();
            $table->string('tonnage')->nullable();
            $table->string('cavity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_production_planning_details');
    }
};
