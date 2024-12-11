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
        Schema::create('transfer_request_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transfer_request_id')->nullable();
            $table->foreign('transfer_request_id')->references('id')->on('transfer_requests');
            $table->string('product_id')->nullable();
            $table->string('request_qty')->nullable();
            $table->longText('request_remarks')->nullable();
            $table->string('issue_qty')->nullable();
            $table->longText('issue_remarks')->nullable();
            $table->string('rcv_qty')->nullable();
            $table->longText('rcv_remarks')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_request_details');
    }
};
