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
        // 1. Validar os dados recebidos
        $validatedData = $request->validate([
            'unique_device_id' => 'required|string',
            'heart_rate' => 'nullable|numeric|gte:0',
            'spo2' => 'nullable|numeric',
            'temperature' => 'nullable|numeric',
            'finger_detected' => 'nullable|boolean',
            'systolic_pressure' => 'nullable|numeric',
            'diastolic_pressure' => 'nullable|numeric',
        ]);

        // 2. Encontrar o dispositivo
        $device = Device::where('unique_device_id', $validatedData['unique_device_id'])->first();

        // Verificações básicas de existência e vínculo
        if (!$device) {
            return response()->json(['message' => 'Device not found.'], 404);
        }

        if (!$device->patient_id) {
            return response()->json(['message' => 'Device not linked to a patient.'], 400);
        }
        
        if (isset($validatedData['heart_rate']) && $validatedData['heart_rate'] == 0) {
            $validatedData['heart_rate'] = null;
        }

        try {
            // 3. Criar o registro (Sempre cria, independente do valor anterior)
            $vitalsHistory = VitalsHistory::create([
                'patient_id' => $device->patient_id,
                'device_id' => $device->id,
                
                'heart_rate' => $validatedData['heart_rate'] ?? null,
                'temperature' => $validatedData['temperature'] ?? null,
                'systolic_pressure' => $validatedData['systolic_pressure'] ?? null,
                'diastolic_pressure' => $validatedData['diastolic_pressure'] ?? null,
                
                'spo2' => $validatedData['spo2'] ?? null,
                'finger_detected' => $validatedData['finger_detected'] ?? null,
            ]);

            // 4. Retornar sucesso
            return response()->json([
                'message' => 'Vital signs saved successfully.',
                'json_received' => $request->all(),
                'data_saved' => $vitalsHistory
            ], 201);

        } catch (\Exception $e) {
            // 5. Tratamento de erro
            return response()->json(['message' => 'Failed to save vital signs.', 'error' => $e->getMessage()], 500);
        }
    }
}