<?php

namespace Database\Factories;

use App\Models\EmergencyContact;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(), // Gera um nome falso
            'room' => $this->faker->optional(0.9)->bothify('???-##'), // Gera um quarto (90% de chance)
            'birth_date' => $this->faker->dateTimeBetween('-80 years', '-18 years')->format('Y-m-d'), // Gera data de nascimento
        ];
    }

    /**
     * ATUALIZAÇÃO: Configura o estado do modelo após a criação.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function configure()
    {
        return $this->afterCreating(function (Patient $patient) {
            // Para cada paciente criado pela factory, crie um contato de emergência associado.
            EmergencyContact::factory()->create(['patient_id' => $patient->id]);
        });
    }
}