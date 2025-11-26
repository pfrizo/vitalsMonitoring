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
        Schema::table('devices', function (Blueprint $table) {
            
            $table->dropForeign(['patient_id']);

            // 3. Criamos a nova chave com a regra SET NULL
            $table->foreign('patient_id')
                  ->references('id')->on('patients')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);

            $table->foreign('patient_id')
                  ->references('id')->on('patients');
        });
    }
};
