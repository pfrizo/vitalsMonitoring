<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Monitoramento de Pacientes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div>
                <div x-show="isLoading" class="text-center py-10 text-gray-500">
                    Carregando dados...
                </div>

                <div x-show="!isLoading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                    <template x-for="(patient, id) in patientsData" :key="id">
                        
                        <div class="overflow-hidden shadow-sm sm:rounded-lg border"
                             :class="{
                                 'bg-white border-blue-500': patient.status.overall === 'normal',
                                 'bg-yellow-50 border-yellow-400': patient.status.overall === 'moderate',
                                 'bg-red-50 border-red-500': patient.status.overall === 'high',
                                 'bg-gray-100 border-gray-400': patient.status.overall === 'device_removed',
                                 'bg-gray-50 border-gray-300': patient.status.overall === 'no_data'
                             }">
                            <div class="p-6 text-gray-900">

                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <h3 class="text-lg font-semibold text-gray-800" x-text="patient.name"></h3>
                                            
                                            <template x-if="patient.status.overall === 'high'">
                                                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </template>
                                            <template x-if="patient.status.overall === 'moderate'">
                                                <svg class="h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM10 13a1 1 0 110-2 1 1 0 010 2zm-1-8a1 1 0 011 1v3a1 1 0 11-2 0V6a1 1 0 011-1z" clip-rule="evenodd" />
                                                </svg>
                                            </template>
                                        </div>
                                        <p class="text-sm text-gray-500">
                                            Quarto: <span x-text="patient.room"></span>
                                        </p>
                                        <template x-if="patient.deviceName">
                                            <p class="text-xs text-gray-500 italic mt-1">
                                                Dispositivo: <span x-text="patient.deviceName"></span>
                                            </p>
                                        </template>
                                    </div>
                                    <a :href="patient.show_url" class="text-xs text-indigo-600 hover:text-indigo-900" title="Ver Detalhes">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </div>

                                
                                <template x-if="patient.latestVitals && patient.status.overall !== 'device_removed'">
                                    <div class="space-y-2">
                                        
                                        <template x-if="patient.latestVitals.heart_rate !== null">
                                            <div class="flex justify-between items-center border-t pt-2">
                                                <span class="text-sm font-medium text-gray-500">Batimentos</span>
                                                <span class="text-lg font-bold"
                                                      :class="{
                                                          'text-gray-900': patient.status.bpm === 'normal' || patient.status.bpm === 'no_data',
                                                          'text-yellow-600': patient.status.bpm === 'moderate',
                                                          'text-red-600': patient.status.bpm === 'high'
                                                      }">
                                                    <span x-text="patient.latestVitals.heart_rate"></span>
                                                    <span class="text-xs font-normal">bpm</span>
                                                </span>
                                            </div>
                                        </template>

                                        <template x-if="patient.latestVitals.spo2 !== null">
                                            <div class="flex justify-between items-center border-t pt-2">
                                                <span class="text-sm font-medium text-gray-500">SpO2</span>
                                                <span class="text-lg font-bold"
                                                    :class="{
                                                        /* AGORA USA O STATUS ESPECÍFICO DO SPO2 */
                                                        'text-gray-900': patient.status.spo2 === 'normal',
                                                        'text-yellow-600': patient.status.spo2 === 'moderate',
                                                        'text-red-600': patient.status.spo2 === 'high'
                                                    }">
                                                    <span x-text="patient.latestVitals.spo2"></span>
                                                    <span class="text-xs font-normal">%</span>
                                                </span>
                                            </div>
                                        </template>

                                        <template x-if="patient.latestVitals.temperature_formatted !== null">
                                            <div class="flex justify-between items-center border-t pt-2">
                                                <span class="text-sm font-medium text-gray-500">Temperatura</span>
                                                 <span class="text-lg font-bold"
                                                      :class="{
                                                          'text-gray-900': patient.status.temp === 'normal' || patient.status.temp === 'no_data',
                                                          'text-yellow-600': patient.status.temp === 'moderate',
                                                          'text-red-600': patient.status.temp === 'high'
                                                      }">
                                                    <span x-text="patient.latestVitals.temperature_formatted"></span>
                                                    <span class="text-xs font-normal">°C</span>
                                                </span>
                                            </div>
                                        </template>

                                        <template x-if="patient.latestVitals.systolic && patient.latestVitals.diastolic">
                                            <div class="flex justify-between items-center border-t pt-2">
                                                <span class="text-sm font-medium text-gray-500">Pressão</span>
                                                <span class="text-lg font-bold"
                                                      :class="{
                                                          'text-gray-900': patient.status.pressure === 'normal' || patient.status.pressure === 'no_data',
                                                          'text-yellow-600': patient.status.pressure === 'moderate',
                                                          'text-red-600': patient.status.pressure === 'high'
                                                      }">
                                                    <span x-text="patient.latestVitals.systolic"></span>/<span x-text="patient.latestVitals.diastolic"></span>
                                                    <span class="text-xs font-normal">mmHg</span>
                                                </span>
                                            </div>
                                        </template>

                                        <template x-if="patient.latestVitals.finger_detected !== null">
                                            <div class="flex justify-between items-center border-t pt-2">
                                                <span class="text-sm font-medium text-gray-500">Sensor (Dedo)</span>
                                                <span class="text-sm font-bold"
                                                      :class="patient.latestVitals.finger_detected ? 'text-green-600' : 'text-orange-500'">
                                                    <span x-text="patient.latestVitals.finger_detected ? 'Detectado' : 'Não Detectado'"></span>
                                                </span>
                                            </div>
                                        </template>

                                        <div class="mt-4 pt-2 border-t border-dashed border-gray-300 text-right">
                                             <p class="text-xs text-gray-500">
                                                 Última leitura:
                                                 <span x-text="patient.latestVitals.timestamp_full"></span>
                                                 (<span x-text="patient.latestVitals.timestamp_relative"></span>)
                                             </p>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="patient.status.overall === 'device_removed'">
                                    <div class="text-center py-4 space-y-2 border-t">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                           <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.01" />
                                        </svg>
                                        <p class="text-sm font-semibold text-gray-700">Dispositivo Removido</p>
                                        <p class="text-xs text-gray-500">
                                            (Última leitura: <span x-text="patient.latestVitals.timestamp_relative"></span>)
                                        </p>
                                    </div>
                                </template>

                                <template x-if="!patient.latestVitals && patient.status.overall !== 'device_removed'">
                                    <p class="text-sm text-gray-500 text-center py-4 border-t">Aguardando dados...</p>
                                </template>

                            </div>
                        </div>
                    </template>
                    
                    <template x-if="!isLoading && Object.keys(patientsData).length === 0">
                        <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-10">
                            <p class="text-lg text-gray-600">Nenhum paciente está sendo monitorado no momento.</p>
                            <p class="text-sm text-gray-500 mt-2">Verifique se há dispositivos associados e enviando dados.</p>
                        </div>
                    </template>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>