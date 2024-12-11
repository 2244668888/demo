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
        Schema::create('purchase_return_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_return_id')->nullable();
            $table->foreign('purchase_return_id')->references('id')->on('purchase_returns')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products');
            $table->string('qty')->nullable();
            $table->string('return_qty')->nullable();
            $table->string('reject_qty')->nullable();
            $table->string('receive_qty')->nullable();
            $table->string('to_receive')->nullable();
            $table->longText('reason')->nullable();
            $table->string('reject_remarks')->nullable();
            $table->string('receive_remarks')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_return_products');
    }
};
