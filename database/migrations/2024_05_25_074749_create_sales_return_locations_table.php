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
        Schema::create('sales_return_locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_return_id')->nullable();
            $table->foreign('sales_return_id')->references('id')->on('sales_returns')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->nullable();
            $table->unsignedBigInteger('area_id')->nullable();
            $table->foreign('area_id')->references('id')->on('areas')->nullable();
            $table->unsignedBigInteger('rack_id')->nullable();
            $table->foreign('rack_id')->references('id')->on('area_racks')->nullable();
            $table->unsignedBigInteger('level_id')->nullable();
            $table->foreign('level_id')->references('id')->on('area_levels')->nullable();
            $table->string('lot_no')->nullable();
            $table->string('qty')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_return_locations');
    }
};
