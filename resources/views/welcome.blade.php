<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'NeoVita') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-50 text-gray-900 font-sans">

        <nav class="flex items-center justify-between px-6 py-4 bg-white shadow-sm">
            <div class="flex items-center">
                <a href="/" class="flex items-center gap-2">
                    
                    <img src="{{ asset('img/Logo NeoVita.png') }}" 
                         alt="Logo NeoVita" 
                         class="h-12 w-auto" />

                    <span class="text-xl font-bold text-gray-800">NeoVita</span>
                </a>
            </div>

            <div class="flex items-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-blue-600 transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-blue-600 transition">Entrar</a>
                    @endauth
                @endif
            </div>
        </nav>

        <header class="relative overflow-hidden bg-white">
            <div class="max-w-7xl mx-auto px-6 py-16 lg:py-24 grid lg:grid-cols-2 gap-12 items-center">
                
                <div class="space-y-6">
                    <h1 class="text-4xl lg:text-6xl font-extrabold tracking-tight text-gray-900 leading-tight">
                        Inteligência Conectada em <span class="text-lime-600">Saúde Digital.</span>
                    </h1>
                    <p class="text-lg text-gray-600">
                        Monitoramento preciso de sinais vitais em tempo real. Unindo tecnologia e cuidado para garantir o bem-estar do paciente.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-8 py-3 text-center text-lg font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                                Acessar Painel
                            </a>
                        @endauth
                    </div>
                </div>

                <div class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-500 to-lime-500 rounded-2xl blur opacity-30 group-hover:opacity-60 transition duration-1000 group-hover:duration-200"></div>
                    
                    <div class="relative bg-white rounded-2xl shadow-2xl border border-gray-100 p-6">
                        <div class="flex justify-between items-start mb-6">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-xl border border-gray-200">👤</div>
                                <div>
                                    <div class="h-4 w-32 bg-gray-800 rounded mb-2"></div>
                                    <div class="h-3 w-20 bg-gray-400 rounded"></div>
                                </div>
                            </div>
                            <div class="h-8 w-8 bg-red-100 text-red-500 rounded-full flex items-center justify-center animate-pulse">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex justify-between border-t pt-3">
                                <span class="text-gray-500">Batimentos</span>
                                <span class="font-bold text-red-600">115 <span class="text-xs font-normal">bpm</span></span>
                            </div>
                            <div class="flex justify-between border-t pt-3">
                                <span class="text-gray-500">SpO2</span>
                                <span class="font-bold text-gray-900">98 <span class="text-xs font-normal">%</span></span>
                            </div>
                            <div class="flex justify-between border-t pt-3">
                                <span class="text-gray-500">Temperatura</span>
                                <span class="font-bold text-blue-600">36,5 <span class="text-xs font-normal">°C</span></span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </header>

        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900">Recursos do Sistema</h2>
                    <p class="mt-4 text-gray-600">Tecnologia avançada para o cuidado com o paciente.</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-md transition border border-gray-100">
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Tempo Real</h3>
                        <p class="text-gray-600">Atualização constante dos dados vitais através de dispositivos IoT conectados.</p>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-md transition border border-gray-100">
                        <div class="w-12 h-12 bg-red-100 text-red-600 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Alertas Críticos</h3>
                        <p class="text-gray-600">Notificações visuais imediatas quando os sinais vitais ultrapassam os limites seguros.</p>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-md transition border border-gray-100">
                        <div class="w-12 h-12 bg-lime-100 text-lime-600 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Histórico Seguro</h3>
                        <p class="text-gray-600">Todos os dados são armazenados com segurança para análise médica posterior.</p>
                    </div>
                </div>
            </div>
        </section>

        <footer class="bg-white border-t py-12">
            <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <span class="text-xl font-bold text-gray-800">NeoVita</span>
                    <p class="text-sm text-gray-500 mt-1">&copy; {{ date('Y') }} Todos os direitos reservados.</p>
                </div>
                <div class="flex gap-6">
                    <a href="#" class="text-gray-500 hover:text-blue-600">Sobre</a>
                    <a href="#" class="text-gray-500 hover:text-blue-600">Privacidade</a>
                    <a href="#" class="text-gray-500 hover:text-blue-600">Termos</a>
                </div>
            </div>
        </footer>
    </body>
</html>