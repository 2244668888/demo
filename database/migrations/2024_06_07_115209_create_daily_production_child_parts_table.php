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
        Schema::create('daily_production_child_parts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dpp_id')->nullable();
            $table->foreign('dpp_id')->references('id')->on('daily_production_plannings');
            $table->string('parent_part_id')->nullable();
            $table->string('product_id')->nullable();
            $table->string('subpart_qty')->nullable();
            $table->string('inventory_qty')->nullable();
            $table->string('total_required_qty')->nullable();
            $table->string('est_plan_qty')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_production_child_parts');
    }
};
