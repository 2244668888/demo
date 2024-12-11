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
        Schema::create('outgoings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('orders');
            $table->unsignedBigInteger('sr_id')->nullable();
            $table->foreign('sr_id')->references('id')->on('sales_returns');
            $table->unsignedBigInteger('pr_id')->nullable();
            $table->foreign('pr_id')->references('id')->on('purchase_returns');
            $table->string('category')->nullable();
            $table->string('ref_no')->nullable();
            $table->string('date')->nullable();
            $table->string('acc_no')->nullable();
            $table->string('payment_term')->nullable();
            $table->string('mode')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outgoings');
    }
};
