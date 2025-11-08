<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Patient; // <-- IMPORTANTE: Precisamos do modelo Paciente
use App\Models\VitalsHistory;
use Illuminate\Http\Request;

class VitalSignController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validar os dados
        $validatedData = $request->validate([
            'unique_device_id' => 'required|string',
            'heart_rate' => 'nullable|numeric|gte:0', // gte:0 permite '0'
            'spo2' => 'nullable|numeric',
            'temperature' => 'nullable|numeric',
            'finger_detected' => 'nullable|boolean',
            'systolic_pressure' => 'nullable|numeric',
            'diastolic_pressure' => 'nullable|numeric',
        ]);

        // 2. Encontrar o dispositivo e paciente
        $device = Device::where('unique_device_id', $validatedData['unique_device_id'])->first();

        if (!$device || !$device->patient_id) {
            return response()->json(['message' => 'Device not found or not linked.'], 404);
        }

        // Carregamos o paciente para checar seu último status
        $patient = Patient::find($device->patient_id); 
        if (!$patient) {
            return response()->json(['message' => 'Patient not found.'], 404);
        }

        // *** INÍCIO DA NOVA LÓGICA DE FILTRAGEM ***

        // Padronizamos "0" para "null" para facilitar a checagem
        $isDeviceRemoved = is_null($validatedData['heart_rate']) || $validatedData['heart_rate'] == 0;
        
        if ($isDeviceRemoved) {
            $validatedData['heart_rate'] = null; // Força 'null' se for 0

            // 3. Checar o último dado salvo para este paciente
            $latestVital = $patient->latestVitals; // (Assume que você tem a relação latestVitals)

            // Se o último dado salvo TAMBÉM for 'null', nós não salvamos de novo.
            if ($latestVital && (is_null($latestVital->heart_rate) || $latestVital->heart_rate == 0)) {
                
                // Retornamos 200 (OK), pois não é um erro, 
                // apenas uma duplicata que decidimos ignorar.
                return response()->json([
                    'message' => 'Redundant "device removed" signal. Record not saved.',
                    'data_validated' => $validatedData
                ], 200);
            }
        }
        // *** FIM DA NOVA LÓGICA ***

        // 4. Salvar no banco (se for um dado novo ou a *primeira* vez que 'null' é registrado)
        try {
            $vitalsHistory = VitalsHistory::create([
                'patient_id' => $patient->id,
                'device_id' => $device->id,
                'heart_rate' => $validatedData['heart_rate'] ?? null,
                'temperature' => $validatedData['temperature'] ?? null,
                'systolic_pressure' => $validatedData['systolic_pressure'] ?? null,
                'diastolic_pressure' => $validatedData['diastolic_pressure'] ?? null,
                'spo2' => $validatedData['spo2'] ?? null,
                'finger_detected' => $validatedData['finger_detected'] ?? null,
            ]);

            return response()->json([
                'message' => 'Vital signs saved successfully.',
                'json_received' => $request->all(),
                'data_validated' => $validatedData,
                'data_saved' => $vitalsHistory
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to save vital signs.', 'error' => $e->getMessage()], 500);
        }
    }
}