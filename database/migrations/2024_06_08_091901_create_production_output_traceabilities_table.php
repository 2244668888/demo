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
        Schema::create('production_output_traceabilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dpp_id')->nullable();
            $table->foreign('dpp_id')->references('id')->on('daily_production_plannings')->nullable();
            $table->string('planned_date')->nullable();
            $table->string('operator')->nullable();
            $table->string('shift')->nullable();
            $table->string('spec_break')->nullable();
            $table->string('planned_qty')->nullable();
            $table->unsignedBigInteger('machine_id')->nullable();
            $table->foreign('machine_id')->references('id')->on('machines')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->nullable();
            $table->string('cavity')->nullable();
            $table->string('po_no')->nullable();
            $table->string('total_produced')->nullable();
            $table->string('total_rejected_qty')->nullable();
            $table->string('total_good_qty')->nullable();
            $table->string('qc_produced')->nullable();
            $table->string('qc_rejected_qty')->nullable();
            $table->string('qc_good_qty')->nullable();
            $table->string('process')->nullable();
            $table->string('shift_length')->nullable();
            $table->string('purging_weight')->nullable();
            $table->string('report_qty')->nullable();
            $table->string('remaining_qty')->nullable();
            $table->string('planned_cycle_time')->nullable();
            $table->string('actual_cycle_time')->nullable();
            $table->string('planned_qty_hr')->nullable();
            $table->string('actual_qty_hr')->nullable();
            $table->string('status')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_output_traceabilities');
    }
};
