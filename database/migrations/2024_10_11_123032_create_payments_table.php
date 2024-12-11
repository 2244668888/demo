<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_order_id')->nullable();
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->unsignedBigInteger('payroll_id')->nullable();
            $table->decimal('total_amount', 15, 2);
            $table->decimal('paying_amount', 15, 2);
            $table->decimal('balance', 15, 2);
            $table->string('payment_method');
            $table->unsignedBigInteger('account_id');
            $table->text('payment_note')->nullable();
            $table->timestamps();
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('accounts');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};

