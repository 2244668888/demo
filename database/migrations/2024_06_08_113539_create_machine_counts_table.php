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
        Schema::create('machine_counts', function (Blueprint $table) {
            $table->id();
            $table->string('production_id')->nullable();
            $table->string('datetime')->nullable();
            $table->string('mc_no')->nullable();
            $table->string('count')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machine_counts');
    }
};
