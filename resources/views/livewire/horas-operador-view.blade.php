<div>
    <div class="mt-8 mx-auto w-3/4">
        <div class="bg-gray-50 border border-gray-200 rounded-xl shadow-lg p-6" id="reporteImprimir">
            <!-- Encabezado -->
            <div class="bg-teal-600 text-white p-4 rounded-t-lg shadow-md mb-6 print:bg-teal-600 print:text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold uppercase">{{ auth()->user()->empresa->nombre ?? 'METAMSUR S.A.C.' }}</h2>
                        <p class="text-sm">RUC: {{ auth()->user()->empresa->ruc ?? '20506666558' }}</p>
                    </div>
                    <div class="flex-1 text-center">
                        <h1 class="text-xl font-bold uppercase">REPORTE DE HORAS TRABAJADAS POR OPERADOR</h1>
                    </div>
                    <!-- Botón de impresión -->
                    <div class="print:hidden">
                        <button onclick="window.print()" class="bg-white text-teal-700 px-4 py-2 rounded shadow hover:bg-gray-100 transition-colors duration-200 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Imprimir
                        </button>
                    </div>
                </div>
            </div>
        
            <!-- Filtros -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6 print:mb-4 print:shadow-none">
                <div class="text-lg font-semibold text-gray-700 mb-4">Filtros de búsqueda</div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Fecha desde -->
                    <div>
                        <label for="fechaDesde" class="block text-sm font-medium text-gray-700 mb-1">Fecha desde</label>
                        <input type="date" wire:model="fechaDesde" id="fechaDesde" class="w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50">
                    </div>
                    
                    <!-- Fecha hasta -->
                    <div>
                        <label for="fechaHasta" class="block text-sm font-medium text-gray-700 mb-1">Fecha hasta</label>
                        <input type="date" wire:model="fechaHasta" id="fechaHasta" class="w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50">
                    </div>
                    
                    <!-- Operador -->
                    <div>
                        <label for="operadorId" class="block text-sm font-medium text-gray-700 mb-1">Operador</label>
                        <select wire:model="operadorId" id="operadorId" class="w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50">
                            <option value="">Todos los operadores</option>
                            @foreach ($operadores as $operador)
                                <option value="{{ $operador['id'] }}">{{ $operador['nombre'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="mt-4 flex justify-end print:hidden">
                    <button wire:click="generarReporte" class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-colors duration-200">
                        Generar Reporte
                    </button>
                </div>
            </div>
            
            @if($mostrarTabla)
            <!-- Tabla de resultados -->
            <div class="bg-white rounded-lg shadow-md p-6 print:shadow-none">
                <div class="text-lg font-semibold text-gray-700 mb-4">Detalle de partes diarios</div>
                
                @if(count($partesDiarios) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        N° Parte
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Operador
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Unidad
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cliente
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total Horas
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($partesDiarios as $parte)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($parte->fecha_inicio)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $parte->numero_parte }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $parte->operador->nombre ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $parte->unidad->numero ?? 'N/A' }} - {{ $parte->unidad->descripcion ?? '' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $parte->entidad->descripcion ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ number_format($parte->total_horas, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-right font-bold text-gray-700">Total de Horas:</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700">
                                        {{ number_format($partesDiarios->sum('total_horas'), 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="mt-4 print:hidden">
                        {{ $partesDiarios->links() }}
                    </div>
                @else
                    <div class="text-center py-8 text-gray-600">
                        <p>No hay datos disponibles para mostrar en la tabla.</p>
                    </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Estilos específicos para impresión -->
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            
            #reporteImprimir, #reporteImprimir * {
                visibility: visible;
            }
            
            #reporteImprimir {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: auto;
                margin: 0;
                padding: 15px;
                box-shadow: none !important;
                background-color: white !important;
            }
            
            .print\:bg-teal-600 {
                background-color: #0d9488 !important;
            }
            
            .print\:text-white {
                color: white !important;
            }
            
            .print\:hidden {
                display: none !important;
            }
            
            .print\:shadow-none {
                box-shadow: none !important;
            }
            
            .print\:mb-4 {
                margin-bottom: 1rem !important;
            }
            
            /* Evitar que la tabla se corte */
            table {
                width: 100% !important;
                font-size: 11px !important;
                page-break-inside: auto !important;
                border-collapse: collapse !important;
            }
            
            tr {
                page-break-inside: avoid !important;
                page-break-after: auto !important;
            }
            
            td, th {
                page-break-inside: avoid !important;
                padding: 5px !important;
            }
            
            thead {
                display: table-header-group !important;
            }
            
            tfoot {
                display: table-footer-group !important;
            }
            
            /* Ajustes generales para impresión */
            @page {
                size: portrait;
                margin: 1cm;
            }
            
            /* Asegurar que no se corta el contenido */
            .overflow-x-auto {
                overflow: visible !important;
            }
            
            /* Ajustar el tamaño del texto para caber mejor */
            .text-sm {
                font-size: 10px !important;
            }
            
            /* Quitar efectos que puedan afectar a la impresión */
            .rounded-lg, .rounded-t-lg, .rounded-xl, .shadow-lg, .shadow-md {
                border-radius: 0 !important;
                box-shadow: none !important;
            }
            
            /* Ajustar espaciado */
            .p-6, .p-4, .py-4, .py-3, .px-6 {
                padding: 4px !important;
            }
            
            /* Forzar el color de fondo en blanco para todas las celdas */
            td, th, tbody tr, thead tr {
                background-color: white !important;
            }
        }
    </style>
</div> 