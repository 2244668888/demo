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
        Schema::create('purchase_order_verification_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_order_id')->nullable();
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders');
            $table->string('date')->nullable();
            $table->string('action_by')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_verification_histories');
    }
};
