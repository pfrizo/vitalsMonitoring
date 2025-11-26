<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                <h1 class="text-4xl font-bold text-red-600 mb-4">403 | Acesso Negado</h1>
                
                <p class="text-gray-600 text-lg mb-6">
                    Você não tem permissão para acessar esta página.
                </p>

                <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:underline">
                    Voltar para o Dashboard
                </a>
            </div>
        </div>
    </div>
</x-app-layout>