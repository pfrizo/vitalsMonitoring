<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // --- Métricas Principais (Widgets) ---
        $patientsCount = Patient::count();
        $devicesCount = Device::count();
        $devicesInUse = Device::whereNotNull('patient_id')->count();

        // --- Listas de Atividade Recente ---
        $recentPatients = Patient::latest()->take(5)->get(); 
        
        // ATUALIZAÇÃO: Adicionado 'with('patient')' para carregar o relacionamento
        $recentDevices = Device::with('patient')->latest()->take(5)->get();

        return view('dashboard', [
            'patientsCount' => $patientsCount,
            'devicesInUse' => $devicesInUse,
            'devicesAvailable' => $devicesCount - $devicesInUse,
            'recentPatients' => $recentPatients,
            'recentDevices' => $recentDevices,
        ]);
    }
}