<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Painel de Controle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total de Pacientes</h3>
                        <p class="text-3xl font-semibold text-gray-800">{{ $patientsCount }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Dispositivos em Uso</h3>
                        <p class="text-3xl font-semibold text-indigo-600">{{ $devicesInUse }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Dispositivos Disponíveis</h3>
                        <p class="text-3xl font-semibold text-green-600">{{ $devicesAvailable }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Pacientes Adicionados Recentemente</h3>
                        <ul class="divide-y divide-gray-200">
                            @forelse ($recentPatients as $patient)
                                <li class="py-3 flex justify-between items-center">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $patient->name }}</p>
                                        <p class="text-sm text-gray-500">Quarto: {{ $patient->room ?? 'N/A' }}</p>
                                    </div>
                                    <a href="{{ route('patients.show', $patient->id) }}" class="text-sm text-indigo-600 hover:text-indigo-900">Ver</a>
                                </li>
                            @empty
                                <li class="py-3 text-sm text-gray-500">Nenhum paciente recente.</li>
                            @endforelse
                        </ul>
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <a href="{{ route('patients.create', ['redirect_to' => url()->current()]) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out">
                                + Cadastrar Novo Paciente
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Dispositivos Adicionados Recentemente</h3>
                        <ul class="divide-y divide-gray-200">
                            @forelse ($recentDevices as $device)
                                <li class="py-3 flex justify-between items-center">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $device->device_name }}</p>
                                        
                                        @if ($device->patient)
                                            <p class="text-sm text-green-600 font-semibold">
                                                Associado a: {{ $device->patient->name }}
                                            </p>
                                        @else
                                            <p class="text-sm text-gray-500">
                                                ID: {{ $device->unique_device_id }}
                                            </p>
                                        @endif
                                        </div>
                                    <a href="{{ route('devices.show', $device->id) }}" class="text-sm text-indigo-600 hover:text-indigo-900">Ver</a>
                                </li>
                            @empty
                                <li class="py-3 text-sm text-gray-500">Nenhum dispositivo recente.</li>
                            @endforelse
                        </ul>
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <a href="{{ route('devices.create', ['redirect_to' => url()->current()]) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out">
                                + Cadastrar Novo Dispositivo
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>