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
        Schema::create('discrepancies', function (Blueprint $table) {
            $table->id();
            $table->string('product_id')->nullable();
            $table->string('ref_no')->nullable();
            $table->string('mrf_tr_id')->nullable();
            $table->string('order_no')->nullable();
            $table->string('issue_qty')->nullable();
            $table->string('rcv_qty')->nullable();
            $table->string('date')->nullable();
            $table->string('remarks')->nullable();
            $table->string('status')->default('Pending')->comment('Pending,Issuer,Reciever');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discrepancies');
    }
};
