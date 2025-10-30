<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Patient; // Importar Patient
use App\Models\Device;  // Importar Device

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VitalsHistory>
 */
class VitalsHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // IDs serão definidos ao chamar a factory
            'patient_id' => Patient::factory(), // Valor padrão, será sobrescrito
            'device_id' => Device::factory(),   // Valor padrão, será sobrescrito

            // Sinais Vitais Aleatórios
            'heart_rate' => $this->faker->numberBetween(60, 100), // Batimentos normais
            'temperature' => $this->faker->randomFloat(1, 36.0, 37.5), // Temperatura normal
            'systolic_pressure' => $this->faker->optional(0.8)->numberBetween(110, 130), // Pressão sistólica (80% de chance de ter valor)
            'diastolic_pressure' => function (array $attributes) {
                // Garante que diastólica seja menor que sistólica, se sistólica existir
                return $attributes['systolic_pressure'] ? $this->faker->numberBetween(70, 90) : null;
            },

            // Data/Hora Aleatória (últimas 24 horas)
            'created_at' => $this->faker->dateTimeBetween('-24 hours', 'now'),
            'updated_at' => fn (array $attributes) => $attributes['created_at'], // Mesma data/hora
        ];
    }
}