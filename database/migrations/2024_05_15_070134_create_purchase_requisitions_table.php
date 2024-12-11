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
        Schema::create('purchase_requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('pr_no')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('departments');
            $table->string('status')->nullable();
            $table->string('date')->nullable();
            $table->string('require_date')->nullable();
            $table->string('category')->nullable();
            $table->string('category_other')->nullable();
            $table->string('total')->nullable();
            $table->string('attachment')->nullable();
            $table->longText('remarks')->nullable();
            $table->string('current_status')->default('Requested')->comment('Requested,Verified,Approved,Declined,Cancelled')->nullable();
            $table->string('requested_by')->nullable();
            $table->string('verified_by')->nullable();
            $table->string('verified_by_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_requisitions');
    }
};
