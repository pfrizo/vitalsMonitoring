<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Patient; // Importar
use App\Models\Device;  // Importar
use App\Models\VitalsHistory; // Importar
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Importar Hash

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Chama o UserSeeder para criar o admin
        $this->call([
            UserSeeder::class,
        ]);

        // 2. Cria 3 Pacientes (se não existirem)
        // O '->has(Device::factory())' cria um dispositivo associado a cada paciente
        Patient::factory()
            ->count(5) // Criar 5 pacientes
            ->state(function (array $attributes) {
                // Define valores de baseline aleatórios para cada paciente
                return [
                    'normal_heart_rate' => fake()->numberBetween(60, 80),
                    'normal_temperature' => fake()->randomFloat(1, 36.0, 37.0),
                    'normal_systolic_pressure' => fake()->numberBetween(110, 130),
                    'normal_diastolic_pressure' => fake()->numberBetween(70, 90),
                ];
            })
            // Associa um dispositivo a cada paciente
            ->has(Device::factory()) 
            // Cria os pacientes. A factory automaticamente cuidará de criar
            // os contatos de emergência (graças ao método configure() que adicionamos)
            ->create() 
            // Para cada paciente criado, agora vamos gerar o histórico de sinais vitais
            ->each(function (Patient $patient) { 
                VitalsHistory::factory()
                    ->count(30)
                    ->state(function (array $attributes) use ($patient) {
                        // Lógica para gerar sinais vitais baseados no baseline do paciente
                        $heartRate = fake()->numberBetween(
                            max(45, $patient->normal_heart_rate - 15),
                            min(115, $patient->normal_heart_rate + 15)
                        );
                        $temperature = fake()->randomFloat(1,
                            max(35.0, $patient->normal_temperature - 0.8),
                            min(38.5, $patient->normal_temperature + 0.8) 
                        );
                        $systolic = fake()->optional(0.9)->numberBetween(
                            max(90, $patient->normal_systolic_pressure - 20),
                            min(150, $patient->normal_systolic_pressure + 20)
                        );
                        $diastolic = $systolic ? fake()->numberBetween(
                            max(60, $patient->normal_diastolic_pressure - 15),
                            min(100, $patient->normal_diastolic_pressure + 15)
                        ) : null;
                        if ($diastolic && $diastolic >= $systolic) {
                            $diastolic = $systolic - fake()->numberBetween(10, 30);
                        }

                        return [
                            'patient_id' => $patient->id,
                            'device_id' => $patient->devices->first()->id, 
                            'heart_rate' => $heartRate,
                            'temperature' => $temperature,
                            'systolic_pressure' => $systolic,
                            'diastolic_pressure' => $diastolic,
                            'created_at' => fake()->dateTimeBetween('-72 hours', 'now'),
                        ];
                    })
                    ->create();
            });
    }
}