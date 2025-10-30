<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\VitalsHistory;
use Illuminate\Http\Request;

class VitalSignController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validate the incoming data from the device.
        $validatedData = $request->validate([
            'unique_device_id' => 'required|string',
            'heart_rate' => 'required|numeric',
            'temperature' => 'required|numeric',
            'systolic_pressure' => 'nullable|numeric',
            'diastolic_pressure' => 'nullable|numeric',
        ]);

        // 2. Find the device using the unique ID provided.
        // The middleware 'auth:sanctum' already handles token authentication.
        // We find the device here to ensure a valid relationship exists.
        $device = Device::where('unique_device_id', $validatedData['unique_device_id'])->first();

        // Check if the device exists and is linked to a patient
        if (!$device) {
            return response()->json(['message' => 'Device not found.'], 404);
        }

        if (!$device->patient_id) {
            return response()->json(['message' => 'Device not linked to a patient.'], 400);
        }

        try {
            // 3. Create a new record in the vitals_history table.
            $vitalsHistory = VitalsHistory::create([
                'patient_id' => $device->patient_id,
                'device_id' => $device->id,
                'heart_rate' => $validatedData['heart_rate'],
                'temperature' => $validatedData['temperature'],
                'systolic_pressure' => $validatedData['systolic_pressure'] ?? null,
                'diastolic_pressure' => $validatedData['diastolic_pressure'] ?? null,
            ]);

            // 4. Return a successful response.
            return response()->json([
                'message' => 'Vital signs saved successfully.',
                'data' => $vitalsHistory
            ], 201);
        } catch (\Exception $e) {
            // 5. Handle any errors during the creation process.
            return response()->json(['message' => 'Failed to save vital signs.', 'error' => $e->getMessage()], 500);
        }
    }
}
