<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" x-data='{
        criticalAlerts: [], 
        knownCriticalIDs: [], 

        initGlobalPoller() {
            this.fetchCriticalData();
            setInterval(() => {
                this.fetchCriticalData();
            }, 20000); 
        },

        fetchCriticalData() {
            // *** CORREÇÃO AQUI: Usando aspas invertidas (`) ***
            const token = document.querySelector(`meta[name="csrf-token"]`).getAttribute(`content`);
            
            fetch(`{{ route('monitoring.critical_alerts') }}`, {
                headers: {
                    // *** E AQUI ***
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": token
                }
            })
            .then(res => res.json())
            .then(data => {
                let newAlerts = [];
                let currentlyCriticalIDs = [];

                for (const patient of data) {
                    currentlyCriticalIDs.push(patient.id);
                    if (!this.knownCriticalIDs.includes(patient.id)) {
                        newAlerts.push(patient);
                    }
                }

                this.knownCriticalIDs = currentlyCriticalIDs;
                this.criticalAlerts = [...this.criticalAlerts, ...newAlerts];
            })
            .catch(err => console.error(`Erro no poller global:`, err));
        },

        dismissAlert(patientId) {
            this.criticalAlerts = this.criticalAlerts.filter(p => p.id !== patientId);
        }
    }' x-init="initGlobalPoller()">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <div 
            class="fixed top-5 right-5 z-50 w-full max-w-sm space-y-3"
            aria-live="polite"
        >
            <template x-for="patient in criticalAlerts" :key="patient.id">
                <div
                    x-data="{ show: false, timer: null }"
                    x-init="
                        show = true; 
                        timer = setTimeout(() => { show = false; setTimeout(() => dismissAlert(patient.id), 500) }, 10000);
                    "
                    x-show="show"
                    x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="opacity-0 translate-x-full"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-500 transform"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 translate-x-full"
                    @click="clearTimeout(timer); show = false; setTimeout(() => dismissAlert(patient.id), 500)"
                    class="relative w-full p-4 border-r-4 rounded-md shadow-lg cursor-pointer bg-white border-red-500"
                >
                    <div class="flex items-start">
                        <div class="flex-shrink-0 pt-0.5">
                            <svg class="h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        
                        <div class="ml-3 w-0 flex-1">
                            <p class="text-sm font-semibold text-gray-900">
                                Alerta Crítico de Paciente!
                            </p>
                            <p class="mt-1 text-sm text-gray-700">
                                Paciente <strong x-text="patient.name"></strong> (Quarto: <span x-text="patient.room ?? 'N/A'"></span>) apresenta sinais vitais em estado crítico.
                            </p>
                            <div class="mt-2 flex space-x-4">
                                <a :href="'{{ url('dashboard') }}'" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                    Ver Painel
                                </a>
                                <button @click.stop="show = false; setTimeout(() => dismissAlert(patient.id), 500)" class="text-sm font-medium text-gray-700 hover:text-gray-500">
                                    Dispensar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </body>
</html>
