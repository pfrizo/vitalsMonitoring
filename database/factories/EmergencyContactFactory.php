<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EmergencyContactFactory extends Factory
{
    public function definition(): array
    {
        return [
            // patient_id será definido ao chamar a factory
            'name' => $this->faker->name(),
            'phone_number' => $this->faker->phoneNumber(),
            'relationship' => $this->faker->randomElement(['Pai', 'Mãe', 'Filho(a)', 'Cônjuge', 'Amigo(a)']),
        ];
    }
}