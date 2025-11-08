<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MonitoringDataController extends Controller
{
    public function getLatestData(): JsonResponse
    {
        $patientsData = Patient::whereHas('devices')
                            ->with(['latestVitals' => function ($query) {
                                $query->select('vitals_history.patient_id', 'heart_rate', 'temperature', 'systolic_pressure', 'diastolic_pressure', 'created_at'); 
                            }])
                            ->select('id', 'name', 'room', 
                                     'normal_heart_rate', 'normal_temperature', 
                                     'normal_systolic_pressure', 'normal_diastolic_pressure') // <-- Adicionado
                            ->orderBy('name')
                            ->get()
                            ->mapWithKeys(function ($patient) {
                                
                                // ATUALIZAÇÃO: Calcula o status do paciente
                                $status = $this->getVitalsStatus($patient->latestVitals, $patient);

                                return [$patient->id => [
                                    'id' => $patient->id,
                                    'name' => $patient->name,
                                    'room' => $patient->room ?? 'N/A',
                                    'status' => $status, // <-- Envia o status para o front-end
                                    'latestVitals' => $patient->latestVitals ? [
                                        'heart_rate' => $patient->latestVitals->heart_rate,
                                        'temperature_formatted' => number_format($patient->latestVitals->temperature, 1, ',', '.'),
                                        'systolic' => $patient->latestVitals->systolic_pressure,
                                        'diastolic' => $patient->latestVitals->diastolic_pressure,
                                        'timestamp_relative' => $patient->latestVitals->created_at->diffForHumans(),
                                        'timestamp_full' => $patient->latestVitals->created_at->format('d/m/Y H:i:s'),
                                    ] : null, 
                                    'show_url' => route('patients.show', $patient->id), 
                                ]];
                            });

        return response()->json($patientsData);
    }

    public function getCriticalAlerts(): JsonResponse
    {
        $criticalPatients = [];
        
        // Pega todos os pacientes com dispositivos e seus dados de status
        $patients = Patient::whereHas('devices')
            ->with(['latestVitals'])
            ->select('id', 'name', 'room', 'normal_heart_rate', 'normal_temperature', 'normal_systolic_pressure', 'normal_diastolic_pressure')
            ->get();

        foreach ($patients as $patient) {
            // Reutilizamos a sua lógica de status (que retorna uma string)
            $status = $this->getVitalsStatus($patient->latestVitals, $patient);

            // *** ESTA É A CORREÇÃO ***
            // Trocamos if ($status['overall'] === 'high')
            // por:
            if ($status === 'high') {
                $criticalPatients[] = [
                    'id' => $patient->id,
                    'name' => $patient->name,
                    'room' => $patient->room ?? 'N/A', // Adicionado 'room' para o toast
                    'status' => $status, // Agora envia a string 'high'
                    'show_url' => route('patients.show', $patient->id),
                ];
            }
        }

        return response()->json($criticalPatients);
    }

    private function getVitalsStatus($latestVitals, $patient)
    {
        $status = [
            'overall' => 'normal', 'bpm' => 'normal', 
            'temp' => 'normal', 'pressure' => 'normal',
            'spo2' => 'normal' // Adicionando spo2 se você quiser monitorá-lo
        ];

        if (!$latestVitals) {
            return array_fill_keys(array_keys($status), 'no_data');
        }

        // *** INÍCIO DA NOVA LÓGICA ***
        // Se o BPM for null ou 0, o dispositivo foi removido.
        if (is_null($latestVitals->heart_rate) || $latestVitals->heart_rate == 0) {
            // Preenchemos todos os status com 'device_removed'
            return array_fill_keys(array_keys($status), 'device_removed');
        }

        $level = 0; // 0 = normal, 1 = moderate (amarelo), 2 = high (vermelho)

        // --- 1. Checagem de Temperatura ---
        // Padrão: +/- 1.0°C (mod), +/- 2.0°C (alto)
        $tempDiff = abs($latestVitals->temperature - $patient->normal_temperature);
        if ($tempDiff >= 2.0) {
            $level = 2; // Alto
        } elseif ($tempDiff >= 1.0) {
            $level = max($level, 1); // Moderado
        }

        // --- 2. Checagem de Batimentos Cardíacos ---
        // Padrão: +/- 20% (mod), +/- 40% (alto)
        $bpmDiff = abs($latestVitals->heart_rate - $patient->normal_heart_rate);
        if ($bpmDiff >= ($patient->normal_heart_rate * 0.40)) {
            $level = 2; // Alto
        } elseif ($bpmDiff >= ($patient->normal_heart_rate * 0.20)) {
            $level = max($level, 1); // Moderado
        }

        // --- 3. Checagem de Pressão Sistólica ---
        // Padrão: +/- 15% (mod), +/- 25% (alto)
        if ($latestVitals->systolic_pressure) {
            $sysDiff = abs($latestVitals->systolic_pressure - $patient->normal_systolic_pressure);
            if ($sysDiff >= ($patient->normal_systolic_pressure * 0.25)) {
                $level = 2; // Alto
            } elseif ($sysDiff >= ($patient->normal_systolic_pressure * 0.15)) {
                $level = max($level, 1); // Moderado
            }
        }
        
        // Retorna o status final
        if ($level === 2) $status['overall'] = 'high';
        if ($level === 1) $status['overall'] = 'moderate';
        return $status;
    }
}