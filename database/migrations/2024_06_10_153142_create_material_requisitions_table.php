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
        Schema::create('material_requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('pro_order_no')->nullable();
            $table->string('ref_no')->nullable();
            $table->string('request_date')->nullable();
            $table->string('plan_date')->nullable();
            $table->string('request_from')->nullable();
            $table->string('request_to')->nullable();
            $table->string('shift')->nullable();
            $table->string('description')->nullable();
            $table->string('machine')->nullable();
            $table->string('issue_by')->nullable();
            $table->string('issue_date')->nullable();
            $table->string('issue_remarks')->nullable();
            $table->string('issue_time')->nullable();
            $table->string('rcv_by')->nullable();
            $table->string('rcv_date')->nullable();
            $table->string('rcv_remarks')->nullable();
            $table->string('rcv_time')->nullable();
            $table->string('status')->default('Requested')->comment('Requested,Issued,Received')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_requisitions');
    }
};
