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
        Schema::table('production_output_traceabilities', function (Blueprint $table) {
            $table->string('leader_name')->nullable()->before('operator');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_output_traceabilities', function (Blueprint $table) {
            $table->dropColumn('leader_name');
        });
    }
};
