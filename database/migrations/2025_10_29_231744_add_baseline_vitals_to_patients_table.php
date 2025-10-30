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
        Schema::table('patients', function (Blueprint $table) {
            // Adiciona os valores de baseline após a coluna 'birth_date'
            // Definimos como NOT nullable e com um DEFAULT
            $table->integer('normal_heart_rate')->default(70)->after('birth_date');
            $table->decimal('normal_temperature', 4, 1)->default(36.5)->after('normal_heart_rate'); // Ex: 36.5
            $table->integer('normal_systolic_pressure')->default(120)->after('normal_temperature');
            $table->integer('normal_diastolic_pressure')->default(80)->after('normal_systolic_pressure');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Permite reverter a migration
            $table->dropColumn([
                'normal_heart_rate',
                'normal_temperature',
                'normal_systolic_pressure',
                'normal_diastolic_pressure'
            ]);
        });
    }
};
