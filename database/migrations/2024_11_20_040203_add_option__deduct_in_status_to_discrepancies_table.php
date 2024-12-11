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
        Schema::table('discrepancies', function (Blueprint $table) {
            $table->string('status')
                    ->default('Pending')
                    ->comment('Pending,Issuer,Receiver,Deduct')
                    ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('discrepancies', function (Blueprint $table) {
            $table->string('status')
            ->default('Pending')
            ->comment('Pending,Issuer,Receiver')
            ->change();
        });
    }
};
