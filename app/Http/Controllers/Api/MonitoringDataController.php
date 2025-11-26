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
            ->with([
                'devices',
                'latestVitals' => function ($query) {
                    // 1. ADICIONADO: spo2 e finger_detected no select
                    $query->select('vitals_history.patient_id', 'heart_rate', 'temperature', 'systolic_pressure', 'diastolic_pressure', 'spo2', 'finger_detected', 'created_at'); 
                }
            ])
            ->select('id', 'name', 'room', 
                     'normal_heart_rate', 'normal_temperature', 
                     'normal_systolic_pressure', 'normal_diastolic_pressure')
            ->orderBy('name')
            ->get()
            ->mapWithKeys(function ($patient) {
                
                $status = $this->getVitalsStatus($patient->latestVitals, $patient);

                return [$patient->id => [
                    'id' => $patient->id,
                    'name' => $patient->name,
                    'room' => $patient->room ?? 'N/A',
                    'status' => $status,
                    'deviceName' => $patient->devices->first()->name ?? null,
                    
                    // 2. ATUALIZADO: Mapeamento dos novos campos
                    'latestVitals' => $patient->latestVitals ? [
                        'heart_rate' => $patient->latestVitals->heart_rate,
                        'temperature_formatted' => number_format($patient->latestVitals->temperature, 1, ',', '.'),
                        'systolic' => $patient->latestVitals->systolic_pressure,
                        'diastolic' => $patient->latestVitals->diastolic_pressure,
                        
                        // Novos Campos:
                        'spo2' => $patient->latestVitals->spo2,
                        'finger_detected' => $patient->latestVitals->finger_detected, // true, false ou null
                        
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
            'spo2' => 'normal' 
        ];

        if (!$latestVitals) {
            return array_fill_keys(array_keys($status), 'no_data');
        }

        // Checagem de dispositivo removido (BPM nulo ou 0)
        if (is_null($latestVitals->heart_rate) || $latestVitals->heart_rate == 0) {
            return array_fill_keys(array_keys($status), 'device_removed');
        }

        $overallLevel = 0; // 0=normal, 1=moderate, 2=high

        // --- 1. Temperatura ---
        $tempDiff = abs($latestVitals->temperature - $patient->normal_temperature);
        if ($tempDiff >= 2.0) {
            $status['temp'] = 'high';
            $overallLevel = 2;
        } elseif ($tempDiff >= 1.0) {
            $status['temp'] = 'moderate';
            $overallLevel = max($overallLevel, 1);
        }

        // --- 2. Batimentos ---
        $bpmDiff = abs($latestVitals->heart_rate - $patient->normal_heart_rate);
        if ($bpmDiff >= ($patient->normal_heart_rate * 0.40)) {
            $status['bpm'] = 'high';
            $overallLevel = 2;
        } elseif ($bpmDiff >= ($patient->normal_heart_rate * 0.20)) {
            $status['bpm'] = 'moderate';
            $overallLevel = max($overallLevel, 1);
        }

        // --- 3. Pressão ---
        if ($latestVitals->systolic_pressure) {
            $sysDiff = abs($latestVitals->systolic_pressure - $patient->normal_systolic_pressure);
            if ($sysDiff >= ($patient->normal_systolic_pressure * 0.25)) {
                $status['pressure'] = 'high';
                $overallLevel = 2;
            } elseif ($sysDiff >= ($patient->normal_systolic_pressure * 0.15)) {
                $status['pressure'] = 'moderate';
                $overallLevel = max($overallLevel, 1);
            }
        }

        // --- 4. SpO2 (Saturação de Oxigênio) ---
        // Lógica: < 90% é Crítico, < 95% é Atenção
        if (!is_null($latestVitals->spo2)) {
            if ($latestVitals->spo2 < 90) {
                $status['spo2'] = 'high';
                $overallLevel = 2; // Alto Risco
            } elseif ($latestVitals->spo2 < 95) {
                $status['spo2'] = 'moderate';
                $overallLevel = max($overallLevel, 1); // Atenção
            }
        }
        
        // Define o status 'overall' final
        if ($overallLevel === 2) $status['overall'] = 'high';
        if ($overallLevel === 1) $status['overall'] = 'moderate';

        return $status;
    }
}