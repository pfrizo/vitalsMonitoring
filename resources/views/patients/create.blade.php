<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cadastrar Novo Paciente') }}
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
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded" role="alert">
                            <strong class="font-bold">Erro!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('patients.store') }}">
                        @csrf

                        <input type="hidden" name="redirect_to" value="{{ request()->get('redirect_to', route('patients.index')) }}">

                        <h3 class="text-lg font-medium text-gray-900 mb-4">Dados do Paciente</h3>
                        <div class="mt-4">
                            <x-input-label for="name" :value="__('Nome')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="room" :value="__('Quarto / Leito')" />
                            <x-text-input id="room" class="block mt-1 w-full" type="text" name="room" :value="old('room')" />
                            <x-input-error :messages="$errors->get('room')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="birth_date" :value="__('Data de Nascimento')" />
                            <x-text-input id="birth_date" class="block mt-1 w-full" type="date" name="birth_date" :value="old('birth_date')" />
                            <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                        </div>

                        <h4 class="text-sm font-medium text-gray-700 mt-6 mb-2">Valores Padrão (Opcional - Padrão: 70bpm, 36.5°C, 120/80mmHg)</h4>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <x-input-label for="normal_heart_rate" :value="__('BPM Normal')" />
                                <x-text-input id="normal_heart_rate" class="block mt-1 w-full" type="number" name="normal_heart_rate" :value="old('normal_heart_rate')" placeholder="Ex: 70" />
                                <x-input-error :messages="$errors->get('normal_heart_rate')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="normal_temperature" :value="__('Temp. Normal (°C)')" />
                                <x-text-input id="normal_temperature" class="block mt-1 w-full" type="number" name="normal_temperature" :value="old('normal_temperature')" placeholder="Ex: 36.5" step="0.1" />
                                <x-input-error :messages="$errors->get('normal_temperature')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="normal_systolic_pressure" :value="__('Pressão Sis. Normal')" />
                                <x-text-input id="normal_systolic_pressure" class="block mt-1 w-full" type="number" name="normal_systolic_pressure" :value="old('normal_systolic_pressure')" placeholder="Ex: 120" />
                                <x-input-error :messages="$errors->get('normal_systolic_pressure')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="normal_diastolic_pressure" :value="__('Pressão Dia. Normal')" />
                                <x-text-input id="normal_diastolic_pressure" class="block mt-1 w-full" type="number" name="normal_diastolic_pressure" :value="old('normal_diastolic_pressure')" placeholder="Ex: 80" />
                                <x-input-error :messages="$errors->get('normal_diastolic_pressure')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="relative flex py-4 items-center">
                                <div class="flex-grow border-t border-gray-300"></div>
                                <span class="flex-shrink mx-4 text-sm text-gray-500 font-medium">Contato de Emergência</span>
                                <div class="flex-grow border-t border-gray-300"></div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="contact_name" :value="__('Nome do Contato')" />
                            <x-text-input id="contact_name" class="block mt-1 w-full" type="text" name="contact_name" :value="old('contact_name')" required />
                            <x-input-error :messages="$errors->get('contact_name')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="phone_number" :value="__('Telefone do Contato')" />
                            <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="old('phone_number')" required />
                            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="relationship" :value="__('Grau de Parentesco')" />
                            <x-text-input id="relationship" class="block mt-1 w-full" type="text" name="relationship" :value="old('relationship')" />
                            <x-input-error :messages="$errors->get('relationship')" class="mt-2" />
                        </div>
                        <div class="flex items-center justify-end mt-6 space-x-4">
                            <a href="{{ request()->get('redirect_to', route('patients.index')) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Voltar') }}
                            </a>
                            <x-primary-button>
                                {{ __('Salvar Paciente') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>