<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MonitoringController extends Controller
{
    public function index(): View
    {
        // 1. Busca pacientes que TÊM algum histórico de sinais vitais
        // 2. Carrega ('with') o relacionamento 'latestVitals' que definimos
        $patientsBeingMonitored = Patient::whereHas('vitalsHistory') 
                                        ->with('latestVitals') 
                                        ->orderBy('name')
                                        ->get();

        return view('monitoring.index', compact('patientsBeingMonitored'));
    }
}
