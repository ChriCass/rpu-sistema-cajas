<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Resultado por centro de costos
        </h2>
    </x-slot>
    <div class="container mx-auto p-4">
        <x-card>
            <!-- Flecha de regresar en la esquina superior derecha -->
            <div class="flex justify-end mb-6">
                <a href="{{ route('dashboard') }}" wire:navigate class="group flex items-center space-x-2">
                    <div class="bg-teal-500 p-3 rounded-full shadow-md transition-all duration-300 transform group-hover:bg-teal-600 group-hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                    <span class="text-teal-600 font-bold transition-colors duration-300 group-hover:text-teal-700">
                        Regresar
                    </span>
                </a>
            </div>

            <!-- Filtros mejorados -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <div class="flex flex-col space-y-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">Filtros de búsqueda</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Selector de Año -->
                            <div class="space-y-2">
                                <x-select label="Año" placeholder="Selecc." :options="$años" wire:model="año" class="w-full" />
                            </div>
                            
                            <!-- Selector de Centro de Costos -->
                            <div class="space-y-2">
                                <x-select label="Centro de Costos" placeholder="Selecc." :options="$CC" wire:model="centroDeCosto" option-label="descripcion" option-value="id" class="w-full" />
                            </div>

                            <!-- Botón de Procesar -->
                            <div class="flex items-end space-x-4">
                                <button wire:click="procesarReporte" class="w-full bg-teal-500 hover:bg-teal-600 text-white font-bold py-3 px-8 rounded-lg shadow-lg transform hover:scale-105 transition-all duration-300 ease-in-out flex items-center justify-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Procesar</span>
                                </button>

                                <!-- Botón de Exportar PDF -->
                                <button wire:click="exportarPDF" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-8 rounded-lg shadow-lg transform hover:scale-105 transition-all duration-300 ease-in-out flex items-center justify-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Exportar PDF</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mensajes de alerta -->
            <div class="mt-4">
                @if (session()->has('message'))
                    <x-alert title="¡Éxito!" positive class="mb-3">
                        {{ session('message') }}
                    </x-alert>
                @endif

                @if (session()->has('error'))
                    <x-alert title="¡Error!" negative>
                        <x-slot name="slot" class="italic mb-5">
                            {{ session('error') }}
                        </x-slot>
                    </x-alert>
                @endif
            </div>

            <!-- Tabla de resultados -->
            <div class="mt-6 overflow-x-auto">
                <div class="bg-white p-3 rounded-lg shadow-md">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-300">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">FAMILIA</th>
                                    <th class="px-4 py-3 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">SUBFAMILIA</th>
                                    <th class="px-4 py-3 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">DETALLE</th>
                                    <th class="px-4 py-3 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">ENERO</th>
                                    <th class="px-4 py-3 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">FEBRERO</th>
                                    <th class="px-4 py-3 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">MARZO</th>
                                    <th class="px-4 py-3 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">ABRIL</th>
                                    <th class="px-4 py-3 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">MAYO</th>
                                    <th class="px-4 py-3 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">JUNIO</th>
                                    <th class="px-4 py-3 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">JULIO</th>
                                    <th class="px-4 py-3 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">AGOSTO</th>
                                    <th class="px-4 py-3 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">SETIEMBRE</th>
                                    <th class="px-4 py-3 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">OCTUBRE</th>
                                    <th class="px-4 py-3 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">NOVIEMBRE</th>
                                    <th class="px-4 py-3 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">DICIEMBRE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($movimientos) && $movimientos->count())
                                    @foreach ($movimientos as $movimiento)
                                        <tr class="hover:bg-gray-100 transition-colors duration-150">
                                            <td class="px-4 py-2 border-b">{{ $movimiento->familia_descripcion }}</td>
                                            <td class="px-4 py-2 border-b">{{ $movimiento->subfamilia_descripcion }}</td>
                                            <td class="px-4 py-2 border-b">{{ $movimiento->detalle_descripcion }}</td>
                                            <td class="px-4 py-2 border-b text-right">{{ number_format($movimiento->enero ?? 0, 2, '.', ',') }}</td>
                                            <td class="px-4 py-2 border-b text-right">{{ number_format($movimiento->febrero ?? 0, 2, '.', ',') }}</td>
                                            <td class="px-4 py-2 border-b text-right">{{ number_format($movimiento->marzo ?? 0, 2, '.', ',') }}</td>
                                            <td class="px-4 py-2 border-b text-right">{{ number_format($movimiento->abril ?? 0, 2, '.', ',') }}</td>
                                            <td class="px-4 py-2 border-b text-right">{{ number_format($movimiento->mayo ?? 0, 2, '.', ',') }}</td>
                                            <td class="px-4 py-2 border-b text-right">{{ number_format($movimiento->junio ?? 0, 2, '.', ',') }}</td>
                                            <td class="px-4 py-2 border-b text-right">{{ number_format($movimiento->julio ?? 0, 2, '.', ',') }}</td>
                                            <td class="px-4 py-2 border-b text-right">{{ number_format($movimiento->agosto ?? 0, 2, '.', ',') }}</td>
                                            <td class="px-4 py-2 border-b text-right">{{ number_format($movimiento->septiembre ?? 0, 2, '.', ',') }}</td>
                                            <td class="px-4 py-2 border-b text-right">{{ number_format($movimiento->octubre ?? 0, 2, '.', ',') }}</td>
                                            <td class="px-4 py-2 border-b text-right">{{ number_format($movimiento->noviembre ?? 0, 2, '.', ',') }}</td>
                                            <td class="px-4 py-2 border-b text-right">{{ number_format($movimiento->diciembre ?? 0, 2, '.', ',') }}</td>
                                        </tr>
                                    @endforeach
                                    <!-- Total Ingresos -->
                                    <tr class="bg-gray-50">
                                        <td class="px-4 py-2 border-b"></td>
                                        <td class="px-4 py-2 border-b"></td>
                                        <td class="px-4 py-2 border-b font-bold">TOTAL INGRESOS</td>
                                        <td class="px-4 py-2 border-b font-bold text-right">{{ number_format($totalesIngresos['enero'] ?? 0, 2, '.', ',') }}</td>
                                        <td class="px-4 py-2 border-b font-bold text-right">{{ number_format($totalesIngresos['febrero'] ?? 0, 2, '.', ',') }}</td>
                                        <td class="px-4 py-2 border-b font-bold text-right">{{ number_format($totalesIngresos['marzo'] ?? 0, 2, '.', ',') }}</td>
                                        <td class="px-4 py-2 border-b font-bold text-right">{{ number_format($totalesIngresos['abril'] ?? 0, 2, '.', ',') }}</td>
                                        <td class="px-4 py-2 border-b font-bold text-right">{{ number_format($totalesIngresos['mayo'] ?? 0, 2, '.', ',') }}</td>
                                        <td class="px-4 py-2 border-b font-bold text-right">{{ number_format($totalesIngresos['junio'] ?? 0, 2, '.', ',') }}</td>
                                        <td class="px-4 py-2 border-b font-bold text-right">{{ number_format($totalesIngresos['julio'] ?? 0, 2, '.', ',') }}</td>
                                        <td class="px-4 py-2 border-b font-bold text-right">{{ number_format($totalesIngresos['agosto'] ?? 0, 2, '.', ',') }}</td>
                                        <td class="px-4 py-2 border-b font-bold text-right">{{ number_format($totalesIngresos['septiembre'] ?? 0, 2, '.', ',') }}</td>
                                        <td class="px-4 py-2 border-b font-bold text-right">{{ number_format($totalesIngresos['octubre'] ?? 0, 2, '.', ',') }}</td>
                                        <td class="px-4 py-2 border-b font-bold text-right">{{ number_format($totalesIngresos['noviembre'] ?? 0, 2, '.', ',') }}</td>
                                        <td class="px-4 py-2 border-b font-bold text-right">{{ number_format($totalesIngresos['diciembre'] ?? 0, 2, '.', ',') }}</td>
                                    </tr>

                                    <!-- Movimientos de Salida -->
                                    @foreach ($movimientos1 as $movimiento)
                                        <tr class="hover:bg-gray-100 transition-colors duration-150">
                                            <td class="px-4 py-2 border-b">{{ $movimiento->familia_descripcion }}</td>
                                            <td class="px-4 py-2 border-b">{{ $movimiento->subfamilia_descripcion }}</td>
                                            <td class="px-4 py-2 border-b">{{ $movimiento->detalle_descripcion }}</td>
                                            <td class="px-4 py-2 border-b text-right">{{ number_format($movimiento->enero ?? 0, 2, '.', ',') }}</td>
                                            <td class="px-4 py-2 border-b text-right">{{ number_format($movimiento->febrero ?? 0, 2, '.', ',') }}</td>
                                            <td class="px-4 py-2 border-b text-right">{{ number_format($movimiento->marzo ?? 0, 2, '.', ',') }}</td>
                                            <td class="px-4 py-2 border-b text-right">{{ number_format($movimiento->abril ?? 0, 2, '.', ',') }}</td>
                                            <td class="px-4 py-2 border-b text-right">{{ number_format($movimiento->mayo ?? 0, 2, '.', ',') }}</td>
                                            <td class="px-4 py-2 border-b text-right">{{ number_format($movimiento->junio ?? 0, 2, '.', ',') }}</td>
                                            <td class="px-4 py-2 border-b text-right">{{ number_format($movimiento->julio ?? 0, 2, '.', ',') }}</td>
                                            <td class="px-4 py-2 border-b text-right">{{ number_format($movimiento->agosto ?? 0, 2, '.', ',') }}</td>
                                            <td class="px-4 py-2 border-b text-right">{{ number_format($movimiento->septiembre ?? 0, 2, '.', ',') }}</td>
                                            <td class="px-4 py-2 border-b text-right">{{ number_format($movimiento->octubre ?? 0, 2, '.', ',') }}</td>
                                            <td class="px-4 py-2 border-b text-right">{{ number_format($movimiento->noviembre ?? 0, 2, '.', ',') }}</td>
                                            <td class="px-4 py-2 border-b text-right">{{ number_format($movimiento->diciembre ?? 0, 2, '.', ',') }}</td>
                                        </tr>
                                    @endforeach

                                    <!-- Total Salidas -->
                                    <tr class="bg-gray-50">
                                        <td class="px-4 py-2 border-b"></td>
                                        <td class="px-4 py-2 border-b"></td>
                                        <td class="px-4 py-2 border-b font-bold">TOTAL SALIDAS</td>
                                        <td class="px-4 py-2 border-b font-bold text-right">{{ number_format($totalesEgresos['enero'] ?? 0, 2, '.', ',') }}</td>
                                        <td class="px-4 py-2 border-b font-bold text-right">{{ number_format($totalesEgresos['febrero'] ?? 0, 2, '.', ',') }}</td>
                                        <td class="px-4 py-2 border-b font-bold text-right">{{ number_format($totalesEgresos['marzo'] ?? 0, 2, '.', ',') }}</td>
                                        <td class="px-4 py-2 border-b font-bold text-right">{{ number_format($totalesEgresos['abril'] ?? 0, 2, '.', ',') }}</td>
                                        <td class="px-4 py-2 border-b font-bold text-right">{{ number_format($totalesEgresos['mayo'] ?? 0, 2, '.', ',') }}</td>
                                        <td class="px-4 py-2 border-b font-bold text-right">{{ number_format($totalesEgresos['junio'] ?? 0, 2, '.', ',') }}</td>
                                        <td class="px-4 py-2 border-b font-bold text-right">{{ number_format($totalesEgresos['julio'] ?? 0, 2, '.', ',') }}</td>
                                        <td class="px-4 py-2 border-b font-bold text-right">{{ number_format($totalesEgresos['agosto'] ?? 0, 2, '.', ',') }}</td>
                                        <td class="px-4 py-2 border-b font-bold text-right">{{ number_format($totalesEgresos['septiembre'] ?? 0, 2, '.', ',') }}</td>
                                        <td class="px-4 py-2 border-b font-bold text-right">{{ number_format($totalesEgresos['octubre'] ?? 0, 2, '.', ',') }}</td>
                                        <td class="px-4 py-2 border-b font-bold text-right">{{ number_format($totalesEgresos['noviembre'] ?? 0, 2, '.', ',') }}</td>
                                        <td class="px-4 py-2 border-b font-bold text-right">{{ number_format($totalesEgresos['diciembre'] ?? 0, 2, '.', ',') }}</td>
                                    </tr>

                                    <!-- Utilidad/Pérdida -->
                                    <tr class="bg-teal-50">
                                        <td class="px-4 py-2 border-b"></td>
                                        <td class="px-4 py-2 border-b"></td>
                                        <td class="px-4 py-2 border-b font-bold text-teal-700">UTILIDAD/PÉRDIDA</td>
                                        <td class="px-4 py-2 border-b font-bold text-right {{ ($totalesIngresos['enero'] ?? 0) + ($totalesEgresos['enero'] ?? 0) < 0 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ number_format(($totalesIngresos['enero'] ?? 0) + ($totalesEgresos['enero'] ?? 0), 2, '.', ',') }}
                                        </td>
                                        <td class="px-4 py-2 border-b font-bold text-right {{ ($totalesIngresos['febrero'] ?? 0) + ($totalesEgresos['febrero'] ?? 0) < 0 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ number_format(($totalesIngresos['febrero'] ?? 0) + ($totalesEgresos['febrero'] ?? 0), 2, '.', ',') }}
                                        </td>
                                        <td class="px-4 py-2 border-b font-bold text-right {{ ($totalesIngresos['marzo'] ?? 0) + ($totalesEgresos['marzo'] ?? 0) < 0 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ number_format(($totalesIngresos['marzo'] ?? 0) + ($totalesEgresos['marzo'] ?? 0), 2, '.', ',') }}
                                        </td>
                                        <td class="px-4 py-2 border-b font-bold text-right {{ ($totalesIngresos['abril'] ?? 0) + ($totalesEgresos['abril'] ?? 0) < 0 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ number_format(($totalesIngresos['abril'] ?? 0) + ($totalesEgresos['abril'] ?? 0), 2, '.', ',') }}
                                        </td>
                                        <td class="px-4 py-2 border-b font-bold text-right {{ ($totalesIngresos['mayo'] ?? 0) + ($totalesEgresos['mayo'] ?? 0) < 0 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ number_format(($totalesIngresos['mayo'] ?? 0) + ($totalesEgresos['mayo'] ?? 0), 2, '.', ',') }}
                                        </td>
                                        <td class="px-4 py-2 border-b font-bold text-right {{ ($totalesIngresos['junio'] ?? 0) + ($totalesEgresos['junio'] ?? 0) < 0 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ number_format(($totalesIngresos['junio'] ?? 0) + ($totalesEgresos['junio'] ?? 0), 2, '.', ',') }}
                                        </td>
                                        <td class="px-4 py-2 border-b font-bold text-right {{ ($totalesIngresos['julio'] ?? 0) + ($totalesEgresos['julio'] ?? 0) < 0 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ number_format(($totalesIngresos['julio'] ?? 0) + ($totalesEgresos['julio'] ?? 0), 2, '.', ',') }}
                                        </td>
                                        <td class="px-4 py-2 border-b font-bold text-right {{ ($totalesIngresos['agosto'] ?? 0) + ($totalesEgresos['agosto'] ?? 0) < 0 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ number_format(($totalesIngresos['agosto'] ?? 0) + ($totalesEgresos['agosto'] ?? 0), 2, '.', ',') }}
                                        </td>
                                        <td class="px-4 py-2 border-b font-bold text-right {{ ($totalesIngresos['septiembre'] ?? 0) + ($totalesEgresos['septiembre'] ?? 0) < 0 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ number_format(($totalesIngresos['septiembre'] ?? 0) + ($totalesEgresos['septiembre'] ?? 0), 2, '.', ',') }}
                                        </td>
                                        <td class="px-4 py-2 border-b font-bold text-right {{ ($totalesIngresos['octubre'] ?? 0) + ($totalesEgresos['octubre'] ?? 0) < 0 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ number_format(($totalesIngresos['octubre'] ?? 0) + ($totalesEgresos['octubre'] ?? 0), 2, '.', ',') }}
                                        </td>
                                        <td class="px-4 py-2 border-b font-bold text-right {{ ($totalesIngresos['noviembre'] ?? 0) + ($totalesEgresos['noviembre'] ?? 0) < 0 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ number_format(($totalesIngresos['noviembre'] ?? 0) + ($totalesEgresos['noviembre'] ?? 0), 2, '.', ',') }}
                                        </td>
                                        <td class="px-4 py-2 border-b font-bold text-right {{ ($totalesIngresos['diciembre'] ?? 0) + ($totalesEgresos['diciembre'] ?? 0) < 0 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ number_format(($totalesIngresos['diciembre'] ?? 0) + ($totalesEgresos['diciembre'] ?? 0), 2, '.', ',') }}
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="15" class="px-4 py-2 border-b text-center text-gray-500">No hay movimientos disponibles</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </x-card>
    </div>
</div>
