<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\EmergencyContact; // <-- Importe
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- Importe
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PatientController extends Controller
{
    // ... (outros métodos como index, show, etc.)
    public function index(): View
    {
        // Carrega os pacientes E seus respectivos contatos de emergência
        $patients = Patient::orderBy('name')->get();
        return view('patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('patients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. ATUALIZAÇÃO: Adiciona validação para os novos campos
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'room' => 'nullable|string|max:50',
            'birth_date' => 'nullable|date',
            'contact_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'relationship' => 'nullable|string|max:50',
            // Validação dos novos campos (opcionais, numéricos)
            'normal_heart_rate' => 'nullable|integer|min:1',
            'normal_temperature' => 'nullable|numeric|min:1',
            'normal_systolic_pressure' => 'nullable|integer|min:1',
            'normal_diastolic_pressure' => 'nullable|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($validatedData) {
                
                // 2. ATUALIZAÇÃO: Prepara os dados do paciente
                $patientData = [
                    'name' => $validatedData['name'],
                    'room' => $validatedData['room'],
                    'birth_date' => $validatedData['birth_date'],
                ];

                // Adiciona os valores padrão APENAS se eles foram preenchidos
                // Se o campo estiver vazio/null, o DB usará o DEFAULT
                if (!empty($validatedData['normal_heart_rate'])) {
                    $patientData['normal_heart_rate'] = $validatedData['normal_heart_rate'];
                }
                if (!empty($validatedData['normal_temperature'])) {
                    $patientData['normal_temperature'] = $validatedData['normal_temperature'];
                }
                if (!empty($validatedData['normal_systolic_pressure'])) {
                    $patientData['normal_systolic_pressure'] = $validatedData['normal_systolic_pressure'];
                }
                if (!empty($validatedData['normal_diastolic_pressure'])) {
                    $patientData['normal_diastolic_pressure'] = $validatedData['normal_diastolic_pressure'];
                }
                
                // 3. Cria o Paciente com os dados preparados
                $patient = Patient::create($patientData);

                // Cria o contato (sem alteração)
                $patient->emergencyContacts()->create([
                    'name' => $validatedData['contact_name'],
                    'phone_number' => $validatedData['phone_number'],
                    'relationship' => $validatedData['relationship'],
                ]);
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao salvar o paciente: ' . $e->getMessage())->withInput();
        }

        return redirect($request->input('redirect_to', route('patients.index')))
               ->with('success', 'Paciente cadastrado com sucesso!');
    }

    public function show(string $id): View
    {
        // Busca manual por ID e carrega os relacionamentos
        $patient = Patient::with(['emergencyContacts', 'vitalsHistory' => function ($query) {
            $query->orderBy('created_at', 'desc')->limit(10);
        }])->findOrFail($id);

        return view('patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        // Busca manual e carrega o primeiro contato para preencher o form
        $patient = Patient::with('emergencyContacts')->findOrFail($id);
        
        // Pega o primeiro contato (ou um novo, se não existir)
        $contact = $patient->emergencyContacts->first() ?? new EmergencyContact();

        return view('patients.edit', compact('patient', 'contact'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        // 1. ATUALIZAÇÃO: Adiciona validação
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'room' => 'nullable|string|max:50',
            'birth_date' => 'nullable|date',
            'contact_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'relationship' => 'nullable|string|max:50',
            'normal_heart_rate' => 'nullable|integer|min:1',
            'normal_temperature' => 'nullable|numeric|min:1',
            'normal_systolic_pressure' => 'nullable|integer|min:1',
            'normal_diastolic_pressure' => 'nullable|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($validatedData, $id) {
                $patient = Patient::findOrFail($id);
                
                // 2. ATUALIZAÇÃO: Prepara os dados do paciente
                $patientData = [
                    'name' => $validatedData['name'],
                    'room' => $validatedData['room'],
                    'birth_date' => $validatedData['birth_date'],
                ];
                
                // A lógica é ligeiramente diferente para update:
                // Usamos o operador '??' (null coalescing) para manter o valor atual se nada for enviado
                // ou usar o valor do request se ele for enviado (mesmo que seja null/vazio)
                // O `?:` (operador ternário curto) é melhor aqui para que strings vazias caiam para o valor padrão.
                // Atualização: A lógica de não enviar o campo é mais limpa.

                if (!empty($validatedData['normal_heart_rate'])) {
                    $patientData['normal_heart_rate'] = $validatedData['normal_heart_rate'];
                }
                if (!empty($validatedData['normal_temperature'])) {
                    $patientData['normal_temperature'] = $validatedData['normal_temperature'];
                }
                if (!empty($validatedData['normal_systolic_pressure'])) {
                    $patientData['normal_systolic_pressure'] = $validatedData['normal_systolic_pressure'];
                }
                if (!empty($validatedData['normal_diastolic_pressure'])) {
                    $patientData['normal_diastolic_pressure'] = $validatedData['normal_diastolic_pressure'];
                }

                $patient->update($patientData);

                // ... (lógica do contato)
            });
        } catch (\Exception $e) {
            // ... (catch)
        }

        return redirect($request->input('redirect_to', route('patients.index')))
               ->with('success', 'Paciente atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        try {
            DB::transaction(function () use ($id) {
                $patient = Patient::findOrFail($id);
                
                // 1. Soft Delete os contatos
                $patient->emergencyContacts()->delete();
                
                // 2. Soft Delete o paciente
                $patient->delete();
            });

        } catch (\Exception $e) {
            return redirect()->route('patients.index')->with('error', 'Não foi possível excluir o paciente.');
        }

        return redirect()->route('patients.index')->with('success', 'Paciente excluído com sucesso!');
    }
}