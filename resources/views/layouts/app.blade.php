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
        liveCriticalAlerts: [], 
        dismissedAlertIDs: [], 

        initGlobalPoller() {
            this.fetchCriticalData();
            setInterval(() => {
                this.fetchCriticalData();
            }, 20000); // Pode diminuir para 10000 (10s) se quiser mais responsivo
        },

        fetchCriticalData() {
            const token = document.querySelector(`meta[name="csrf-token"]`).getAttribute(`content`);
            
            fetch(`{{ route('monitoring.critical_alerts') }}`, {
                headers: {
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": token
                }
            })
            .then(res => res.json())
            .then(data => {
                // LÓGICA ATUALIZADA:
                // Simplesmente define a lista de alertas para ser o que a API diz.
                // Se um paciente melhorar, ele desaparecerá da data e o Alpine
                // o removerá da tela automaticamente.
                this.liveCriticalAlerts = data; 
            })
            .catch(err => console.error(`Erro no poller global:`, err));
        },

        dismissAlert(patientId) {
            // LÓGICA ATUALIZADA:
            // Adiciona o ID à lista de "dispensados" para que o x-for o filtre,
            // mesmo que ele continue vindo da API.
            if (!this.dismissedAlertIDs.includes(patientId)) {
                this.dismissedAlertIDs.push(patientId);
            }
        }
    }'
    x-init="initGlobalPoller()">
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
            <template x-for="patient in liveCriticalAlerts.filter(p => !dismissedAlertIDs.includes(p.id))" :key="patient.id">
                <div
                    x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="opacity-0 translate-x-full"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-500 transform"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 translate-x-full"
                    class="relative w-full p-4 rounded-md shadow-lg bg-white border-2 border-red-500"
                >
                    <div class="flex items-start">
                        <div class="flex-shrink-0 pt-0.5">
                            <svg class="h-6 w-6 text-red-500 animate-pulse" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        
                        <div class="ml-3 w-0 flex-1">
                            <p class="text-base font-semibold text-gray-900">
                                ALERTA CRÍTICO DE PACIENTE!
                            </p>
                            <p class="mt-1 text-sm text-gray-700">
                                Paciente <strong x-text="patient.name"></strong> (Quarto: <span x-text="patient.room ?? 'N/A'"></span>) requer atenção imediata.
                            </p>
                            <a :href="'{{ url('monitoring.index') }}'" class="mt-2 inline-block text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                Ir para o Painel de Monitoramento
                            </a>
                        </div>

                        <div class="ml-4 flex-shrink-0 flex">
                            <button @click.stop="dismissAlert(patient.id)" class="inline-flex text-gray-400 bg-white rounded-md hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <span class="sr-only">Fechar</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </body>
</html>
