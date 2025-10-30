<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Dispositivo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded" role="alert">
                            <strong class="font-bold">Sucesso!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    {{-- Mensagem de Erro (vermelho) --}}
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded" role="alert">
                            <strong class="font-bold">Erro!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('devices.update', $device->id) }}">
                        @csrf
                        @method('PUT') 

                        <input type="hidden" name="redirect_to" value="{{ request()->get('redirect_to', route('devices.index')) }}">

                        <div class="mt-4">
                            <x-input-label for="device_name" :value="__('Nome do Dispositivo')" />
                            <x-text-input id="device_name" class="block mt-1 w-full" type="text" name="device_name" :value="old('device_name', $device->device_name)" required autofocus />
                            <x-input-error :messages="$errors->get('device_name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="unique_device_id" :value="__('ID Único do Dispositivo')" />
                            <x-text-input id="unique_device_id" class="block mt-1 w-full" type="text" name="unique_device_id" :value="old('unique_device_id', $device->unique_device_id)" required />
                            <x-input-error :messages="$errors->get('unique_device_id')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="patient_id" :value="__('Associar ao Paciente (Opcional)')" />
                            <select name="patient_id" id="patient_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Sem paciente associado</option>
                                @foreach ($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patient_id', $device->patient_id) == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('patient_id')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-4">
                            <a href="{{ route('devices.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Voltar') }}
                            </a>
                
                            <x-primary-button>
                                {{ __('Salvar Alterações') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>