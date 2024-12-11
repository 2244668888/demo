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
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('po_id')->nullable();
            $table->foreign('po_id')->references('id')->on('purchase_orders');
            $table->string('grd_no')->nullable();
            $table->string('date')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->string('for_office')->nullable();
            $table->string('attachment')->nullable();
            $table->string('qty')->nullable();
            $table->string('status')->nullable();
            $table->longText('remarks')->nullable();
            $table->string('checked_by_time')->nullable();
            $table->string('received_by_time')->nullable();
            $table->unsignedBigInteger('checked_by')->nullable();
            $table->foreign('checked_by')->references('id')->on('users');
            $table->unsignedBigInteger('received_by')->nullable();
            $table->foreign('received_by')->references('id')->on('users');
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
        Schema::dropIfExists('purchase_returns');
    }
};
