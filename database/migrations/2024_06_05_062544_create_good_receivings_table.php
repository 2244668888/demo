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
        Schema::create('good_receivings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('po_id')->nullable();
            $table->foreign('po_id')->references('id')->on('purchase_orders');
            $table->unsignedBigInteger('pr_id')->nullable();
            $table->foreign('pr_id')->references('id')->on('purchase_returns');
            $table->string('po_pr')->nullable();
            $table->string('ref_no')->nullable();
            $table->string('date')->nullable();
            $table->string('attachment')->nullable();
            $table->string('incoming_qty')->nullable();
            $table->string('received_qty')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('good_receivings');
    }
};
