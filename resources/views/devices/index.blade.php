<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dispositivos Cadastrados') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- ATUALIZAÇÃO 1: 'x-data' movido para cá e novas variáveis adicionadas -->
            <div x-data="{ confirmingDeviceDeletion: false, deviceToDelete: null }" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="flex justify-end mb-4">
                        <a href="{{ route('devices.create', ['redirect_to' => url()->current()]) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Cadastrar Novo Dispositivo
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full table-fixed divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="w-1/3 px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nome do Dispositivo</th>
                                    <th class="w-1/4 px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">ID Único</th>
                                    <th class="w-1/4 px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Paciente Associado</th>
                                    <th class="w-auto px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <!-- 'x-data' foi removido daqui -->
                            <tbody class="bg-white divide-y divide-gray-200">
                                
                                @forelse ($devices as $device)
                                    <tr @click="window.location='{{ route('devices.show', $device->id) }}'" class="cursor-pointer hover:bg-gray-50">
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 truncate text-center">
                                            <a href="{{ route('devices.show', $device->id) }}" class="text-current">
                                                {{ $device->device_name }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            {{ $device->unique_device_id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            @if ($device->patient)
                                                {{ $device->patient->name }}
                                            @else
                                                <a href="{{ route('devices.edit', $device->id) }}" class="inline-flex items-center px-3 py-1 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    Atribuir
                                                </a>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <div @click.stop class="flex justify-center space-x-6">
                                                <a href="{{ route('devices.edit', ['device' => $device->id, 'redirect_to' => url()->current()]) }}" class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                    </svg>
                                                </a>
                                                
                                                <!-- ATUALIZAÇÃO 2: Botão de Apagar -->
                                                <button @click.prevent="deviceToDelete = {{ $device->id }}; confirmingDeviceDeletion = true" 
                                                        class="text-red-600 hover:text-red-900" title="Apagar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Nenhum dispositivo cadastrado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ATUALIZAÇÃO 3: Modal de Confirmação de Exclusão -->
                <div x-show="confirmingDeviceDeletion" 
                     class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center" 
                     style="display: none;">
                    
                    <!-- Fundo do Modal -->
                    <div x-show="confirmingDeviceDeletion"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                         @click="confirmingDeviceDeletion = false"></div>

                    <!-- Conteúdo do Modal -->
                    <div x-show="confirmingDeviceDeletion"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         class="relative bg-white rounded-lg shadow-xl overflow-hidden transform transition-all sm:my-8 sm:max-w-lg sm:w-full">
                        
                        <!-- Formulário de Exclusão -->
                        <!-- O :action será preenchido dinamicamente pelo Alpine com o ID do dispositivo -->
                        <form method="POST" :action="`/devices/${deviceToDelete}`">
                            @csrf
                            @method('DELETE')

                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <!-- Ícone de Alerta -->
                                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div class="mt-3 text-center sm:mt-0 sm:ms-4 sm:text-left">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                            Excluir Dispositivo
                                        </h3>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500">
                                                Você tem certeza que deseja excluir este dispositivo? Esta ação é irreversível.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <!-- Botão de Excluir (Vermelho) -->
                                <button type'submit'
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ms-3 sm:w-auto sm:text-sm">
                                    Excluir
                                </button>
                                <!-- Botão de Cancelar (Branco) -->
                                <button type="button" 
                                        @click="confirmingDeviceDeletion = false"
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- FIM: Modal de Confirmação -->

            </div>
        </div>
    </div>
</x-app-layout>