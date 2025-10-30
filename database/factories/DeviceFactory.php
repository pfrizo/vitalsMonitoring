<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Patient; // Necessário para o valor padrão de patient_id

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Device>
 */
class DeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Gera um ID único no formato DEV-ABCDEF1234
            'unique_device_id' => 'DEV-' . strtoupper($this->faker->lexify('??????')) . $this->faker->numerify('####'),
            // Gera um nome descritivo para o dispositivo
            'device_name' => $this->faker->randomElement(['Pulseira', 'Monitor Cardíaco', 'Sensor de Leito']) . ' ' . $this->faker->randomNumber(3),
            // Define um patient_id padrão (será sobrescrito se usado com ->has() no seeder)
            'patient_id' => null, // Começa como nulo por padrão
        ];
    }
}