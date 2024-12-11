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
        Schema::create('material_planning_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('planning_id')->nullable();
            $table->foreign('planning_id')->references('id')->on('material_plannings');
            $table->string('product_id')->nullable();
            $table->string('required_qty')->nullable();
            $table->string('inventory_qty')->nullable();
            $table->string('request_qty')->nullable();
            $table->string('difference')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_planning_details');
    }
};
