<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <!-- Encabezado con fondo teal -->
    <div class="bg-teal-600 text-white p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold tracking-wide">METAMSUR S.A.C.</h1>
                <p class="text-sm mt-1">RUC: 20606566558</p>
                <p class="text-sm mt-1">📱 959898721</p>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-bold mb-2">REGISTRO DE PAGOS DE MAQUINARIA</h2>
                <div class="bg-white text-teal-600 rounded px-3 py-1 inline-block">
                    <span class="font-bold">Nº {{ $numeroComprobante ?: '--' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido principal con padding -->
    <div class="p-2 sm:p-6">
        <!-- Fecha de pago -->
        <div class="mb-4 sm:mb-6">
            <h3 class="text-sm font-bold pb-2 mb-3 text-teal-600 bg-gray-50 p-2 rounded">DATOS DEL PAGO</h3>
            <div class="flex flex-col sm:flex-row justify-end items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
                <div class="flex items-center w-full sm:w-auto">
                    <label class="text-sm font-medium mr-2 whitespace-nowrap">Fecha de Pago:</label>
                    <input type="date" wire:model.live.debounce.300ms="fecha" 
                           class="flex-1 sm:w-auto border border-gray-300 rounded py-1 px-2 text-sm focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                </div>
            </div>
        </div>

        <!-- Buscador de parte diario -->
        <div class="mb-6 sm:mb-8">
            <h3 class="text-sm font-bold pb-2 mb-2 text-teal-600 bg-gray-50 p-2 rounded">BUSCAR PARTE DIARIO</h3>
            <div class="relative">
                <input type="text" wire:model="busquedaParte" wire:keyup="buscarPartes"
                       class="w-full py-2 px-3 border border-gray-300 rounded focus:border-teal-500 focus:ring-1 focus:ring-teal-500" 
                       placeholder="Ingrese número de parte o nombre de cliente...">
                
                @if($busquedaParte)
                    <button wire:click="limpiarParte" 
                            class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif
            </div>
            
            @if($mostrarResultados && count($resultadosBusqueda) > 0)
                <div class="mt-2 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto z-10">
                    @foreach($resultadosBusqueda as $parte)
                        <div wire:click="seleccionarParte({{ $parte['id'] }})"
                             class="px-4 py-3 hover:bg-teal-50 cursor-pointer border-b border-gray-200 last:border-b-0">
                            <div class="flex justify-between items-center">
                                <div>
                                    <div class="flex items-center space-x-2">
                                        <span class="bg-teal-100 text-teal-800 text-xs px-2 py-1 rounded">Parte #{{ $parte['numero_parte'] }}</span>
                                        <span class="text-sm font-medium">{{ $parte['cliente_nombre'] }}</span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        Fecha: {{ $parte['fecha_inicio'] }} | 
                                        Total: S/ {{ $parte['importe_total'] }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium">
                                        Pendiente: S/ {{ $parte['monto_pendiente'] }}
                                    </div>
                                    <div class="text-xs mt-1 bg-{{ $parte['estado_pago'] == '0' ? 'orange' : 'blue' }}-100 text-{{ $parte['estado_pago'] == '0' ? 'orange' : 'blue' }}-800 px-2 py-1 rounded inline-block">
                                        {{ $parte['estado_pago'] == '0' ? 'Pendiente' : 'Pago Parcial' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @elseif($mostrarResultados && count($resultadosBusqueda) == 0)
                <div class="mt-2 bg-red-50 text-red-600 p-3 rounded-md">
                    No se encontraron resultados para su búsqueda.
                </div>
            @endif
        </div>

        <!-- Datos del parte seleccionado -->
        @if($parteId)
            <div class="mb-6 sm:mb-8 bg-gray-50 p-4 rounded-lg border border-gray-200">
                <h3 class="text-sm font-bold pb-2 mb-3 text-teal-600 border-b border-gray-300">PARTE DIARIO SELECCIONADO</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Nº Parte:</label>
                            <div class="text-sm font-medium">{{ $numeroParte }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Cliente:</label>
                            <div class="text-sm">{{ $clienteNombre }}</div>
                            <div class="text-xs text-gray-500">Código: {{ $clienteCodigo }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Periodo:</label>
                            <div class="text-sm">{{ \Carbon\Carbon::parse($fechaInicioParte)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($fechaFinParte)->format('d/m/Y') }}</div>
                        </div>
                    </div>
                    <div>
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Importe Total:</label>
                            <div class="text-sm font-bold">S/ {{ $importeTotal }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Monto Pagado:</label>
                            <div class="text-sm text-green-600">S/ {{ $montoPagado }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Monto Pendiente:</label>
                            <div class="text-sm font-bold text-red-600">S/ {{ $montoPendiente }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario de pago -->
            <div class="mb-6 sm:mb-8">
                <h3 class="text-sm font-bold pb-2 mb-3 text-teal-600 bg-gray-50 p-2 rounded">DATOS DEL NUEVO PAGO</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Monto a Pagar:</label>
                            <input type="number" step="0.01" min="0" 
                                   class="w-full py-2 px-3 border border-gray-300 rounded focus:border-teal-500 focus:ring-1 focus:ring-teal-500" 
                                   placeholder="Ingrese el monto a pagar">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Número de Operación:</label>
                            <input type="text" 
                                   class="w-full py-2 px-3 border border-gray-300 rounded focus:border-teal-500 focus:ring-1 focus:ring-teal-500" 
                                   placeholder="Ingrese el número de operación (opcional)">
                        </div>
                    </div>
                    <div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Forma de Pago:</label>
                            <select class="w-full py-2 px-3 border border-gray-300 rounded focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                                <option value="">Seleccione</option>
                                <option value="efectivo">Efectivo</option>
                                <option value="transferencia">Transferencia</option>
                                <option value="deposito">Depósito</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Observaciones:</label>
                            <textarea wire:model="observaciones" 
                                      class="w-full py-2 px-3 border border-gray-300 rounded focus:border-teal-500 focus:ring-1 focus:ring-teal-500" 
                                      rows="2" 
                                      placeholder="Ingrese observaciones sobre el pago (opcional)"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex justify-end space-x-4 mt-6">
                <button type="button" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                    Cancelar
                </button>
                <button type="button" 
                        wire:click="registrarPago"
                        class="px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700 transition">
                    Registrar Pago
                </button>
            </div>
        @else
            <!-- Tabla de partes pendientes cuando no hay parte seleccionado -->
            <div class="mb-6 sm:mb-8">
                <h3 class="text-sm font-bold pb-2 mb-3 text-teal-600 bg-gray-50 p-2 rounded">LISTADO DE PARTES PENDIENTES DE PAGO</h3>
                
                <div class="overflow-x-auto bg-white border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nº Parte
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cliente
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pendiente
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acción
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if(count($partesPendientes) > 0)
                                @foreach($partesPendientes as $parte)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $parte['numero_parte'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $parte['fecha_inicio'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $parte['cliente_nombre'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            S/ {{ $parte['importe_total'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $parte['estado_pago'] == '0' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ $parte['estado_pago'] == '0' ? 'Pendiente' : 'Pago Parcial' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">
                                            S/ {{ $parte['monto_pendiente'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button wire:click="seleccionarParte({{ $parte['id'] }})" 
                                                    class="text-teal-600 hover:text-teal-900 bg-teal-50 px-3 py-1 rounded-md">
                                                Pagar
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No hay partes diarios pendientes de pago.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
