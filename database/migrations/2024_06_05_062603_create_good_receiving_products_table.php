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
        Schema::create('good_receiving_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gr_id')->nullable();
            $table->foreign('gr_id')->references('id')->on('good_receivings')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->nullable();
            $table->string('incoming_qty')->nullable();
            $table->string('received_qty')->nullable();
            $table->string('rejected_qty')->nullable();
            $table->string('accepted_qty')->nullable();
            $table->longText('remarks')->nullable();
            $table->string('status')->nullable();
            $table->longText('allocation_remarks')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('good_receiving_products');
    }
};
