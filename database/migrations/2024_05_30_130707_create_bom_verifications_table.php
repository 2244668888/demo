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
        Schema::create('bom_verifications', function (Blueprint $table) {
            $table->id();
            $table->string('bom_id')->nullable();
            $table->string('status')->nullable();
            $table->string('date')->nullable();
            $table->string('approved_by')->nullable();
            $table->string('department_id')->nullable();
            $table->string('reason')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bom_verifications');
    }
};
