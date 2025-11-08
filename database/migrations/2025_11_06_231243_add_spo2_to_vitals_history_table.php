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
            $table->float('spo2')->nullable()->after('diastolic_pressure');
            $table->boolean('finger_detected')->nullable()->after('spo2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vitals_history', function (Blueprint $table) {
            $table->dropColumn(['spo2', 'finger_detected']);
        });
    }
};
