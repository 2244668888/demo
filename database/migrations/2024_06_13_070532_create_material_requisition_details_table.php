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
        Schema::create('material_requisition_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('material_requisition_id')->nullable();
            $table->foreign('material_requisition_id')->references('id')->on('material_requisitions');
            $table->string('product_id')->nullable();
            $table->string('request_qty')->nullable();
            $table->string('issue_qty')->nullable();
            $table->string('issue_remarks')->nullable();
            $table->string('rcv_qty')->nullable();
            $table->string('rcv_remarks')->nullable();
            $table->string('remarks')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_requisition_details');
    }
};
