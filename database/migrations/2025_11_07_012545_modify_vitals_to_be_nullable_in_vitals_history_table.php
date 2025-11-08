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
        Schema::table('vitals_history', function (Blueprint $table) {
            $table->float('heart_rate')->nullable()->change();
            $table->float('temperature')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vitals_history', function (Blueprint $table) {
            $table->float('heart_rate')->nullable(false)->change();
            $table->float('temperature')->nullable(false)->change();
        });
    }
};
