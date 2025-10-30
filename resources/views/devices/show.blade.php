<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes do Dispositivo') }}
            </h2>

             <div class="flex space-x-4">
                <a href="{{ route('devices.edit', ['device' => $device->id, 'redirect_to' => url()->current()]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Editar') }}
                </a>
                <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Voltar') }}
                </a>
            </div>
            </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informações do Dispositivo</h3>
                    <dl class="space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Nome</dt>
                            <dd class="text-sm text-gray-900 font-semibold">{{ $device->device_name }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">ID Único (MAC/Serial)</dt>
                            <dd class="text-sm text-gray-900">{{ $device->unique_device_id }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Paciente Associado</h3>
                    @if ($device->patient)
                        <dl class="space-y-2">
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Nome</dt>
                                <dd class="text-sm text-gray-900 font-semibold">{{ $device->patient->name }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Quarto/Leito</dt>
                                <dd class="text-sm text-gray-900">{{ $device->patient->room_number ?? 'N/A' }}</dd>
                            </div>
                        </dl>
                    @else
                        <p class="text-sm text-gray-500">Este dispositivo não está associado a nenhum paciente no momento.</p>
                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Últimos 10 Registros</h3>
                    <table class="w-full table-fixed divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Data/Hora</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Batimentos</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Temperatura</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($device->vitalsHistory as $history)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-500 text-center">{{ $history->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 text-center">{{ $history->heart_rate }} bpm</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 text-center">{{ $history->temperature }} °C</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-sm text-gray-500 text-center">Nenhum registro de sinal vital para este dispositivo.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>