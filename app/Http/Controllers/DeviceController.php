<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $devices = Device::with('patient')->get();
        return view('devices.index', compact('devices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = Patient::orderBy('name')->get();
        return view('devices.create', compact('patients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validação
        $validatedData = $request->validate([
            'device_name' => 'required|string|max:255',
            'unique_device_id' => 'required|string|max:255|unique:devices,unique_device_id',
            'patient_id' => 'nullable|exists:patients,id', // <-- CORRIGIDO
        ]);

        Device::create($validatedData);

        return redirect($request->input('redirect_to', route('devices.index')))
           ->with('success', 'Dispositivo cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Buscamos manualmente o dispositivo pelo ID
        $device = Device::findOrFail($id);

        // E então carregamos os relacionamentos
        $device->load([
            'patient', 
            'vitalsHistory' => function ($query) {
                $query->orderBy('created_at', 'desc')->limit(10);
            }
        ]);

        return view('devices.show', compact('device'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $device = Device::findOrFail($id);
        
        // 2. Busca os pacientes para o dropdown
        $patients = Patient::orderBy('name')->get();

        return view('devices.edit', compact('device', 'patients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $device = Device::findOrFail($id);

        // 2. Validação (agora podemos usar $device->id com segurança)
        $validatedData = $request->validate([
            'device_name' => 'required|string|max:255',
            'unique_device_id' => 'required|string|max:255|unique:devices,unique_device_id,' . $device->id,
            'patient_id' => 'nullable|exists:patients,id',
        ]);

        try {
            $device->update($validatedData);
        } catch (\Exception $e) {
            // ATUALIZAÇÃO: Adicionado try-catch e redirect()->back() para erros
            return redirect()->back()->with('error', 'Erro ao atualizar o dispositivo: ' . $e->getMessage())->withInput();
        }

        return redirect($request->input('redirect_to', route('devices.index')))
           ->with('success', 'Dispositivo atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        try {
            // 1. Busca manual pelo ID
            $device = Device::findOrFail($id);
            
            // 2. Exclui o dispositivo
            $device->delete();

        } catch (\Exception $e) {
            // Se houver um erro (ex: chave estrangeira no VitalsHistory)
            return redirect()->route('devices.index')->with('error', 'Não foi possível excluir o dispositivo. Verifique se ele possui históricos vinculados.');
        }

        // 3. Redireciona de volta com sucesso
        return redirect()->route('devices.index')->with('success', 'Dispositivo excluído com sucesso!');
    }
}
