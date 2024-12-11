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
        Schema::create('purchase_planning_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_planning_id')->nullable();
            $table->foreign('purchase_planning_id')->references('id')->on('purchase_plannings')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->nullable();
            $table->string('product_qty')->nullable();
            $table->string('total_qty')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_planning_products');
    }
};
