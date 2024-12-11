<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarryForwardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carry_forwards', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('year')->unique(); // Year for the carryforward
            $table->decimal('balance', 15, 2); // Balance amount
            $table->enum('status', ['profit', 'loss']); // Status indicating if it's profit or loss
            $table->timestamps(); // Timestamps for created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carry_forwards');
    }
}
