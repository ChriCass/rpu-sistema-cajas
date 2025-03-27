<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <!-- Sistema de notificaciones usando flash messages de Laravel -->
    @if (session()->has('mensaje'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 5000)"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-x-full"
            x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-x-0"
            x-transition:leave-end="opacity-0 transform translate-x-full"
            class="fixed top-4 right-4 z-50 rounded-lg shadow-lg p-4 max-w-sm w-full flex items-start
                {{ session('tipo') == 'error' ? 'bg-red-50 border-l-4 border-red-500' : '' }}
                {{ session('tipo') == 'success' ? 'bg-green-50 border-l-4 border-green-500' : '' }}
                {{ session('tipo') == 'warning' ? 'bg-yellow-50 border-l-4 border-yellow-500' : '' }}
                {{ session('tipo') == 'info' ? 'bg-blue-50 border-l-4 border-blue-500' : '' }}"
        >
            <div class="flex-shrink-0 text-2xl mr-3">
                @if (session('tipo') == 'error')
                    😿
                @elseif (session('tipo') == 'success')
                    😺
                @elseif (session('tipo') == 'warning')
                    😾
                @else
                    😸
                @endif
            </div>
            <div class="flex-grow">
                <p class="text-sm font-medium
                    {{ session('tipo') == 'error' ? 'text-red-800' : '' }}
                    {{ session('tipo') == 'success' ? 'text-green-800' : '' }}
                    {{ session('tipo') == 'warning' ? 'text-yellow-800' : '' }}
                    {{ session('tipo') == 'info' ? 'text-blue-800' : '' }}"
                >
                    {{ session('mensaje') }}
                </p>
            </div>
            <button 
                @click="show = false"
                class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 inline-flex items-center justify-center h-8 w-8"
            >
                <span class="sr-only">Cerrar</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

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
            <h3 class="text-sm font-bold pb-2 mb-3 text-teal-600 bg-gray-50 p-2 rounded">BUSCAR ENTIDAD</h3>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" wire:model.debounce.300ms="busquedaParte" wire:keyup.debounce.300ms="buscarPartes"
                       class="w-full pl-10 py-2 px-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm" 
                       placeholder="Buscar por nombre o código de entidad...">
                
                @if($busquedaParte)
                    <button wire:click="limpiarParte" 
                            class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
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
                                        Vence: {{ $parte['fecha_vencimiento'] }} |
                                        <span class="
                                            {{ $parte['estado_fecha'] == 'Vencido' ? 'text-red-600 font-semibold' : 
                                              ($parte['estado_fecha'] == 'Urgente' ? 'text-yellow-600 font-semibold' : 'text-green-600') }}
                                        ">
                                            {{ $parte['estado_fecha'] }}
                                            {{ $parte['dias_restantes'] < 0 ? '(' . $parte['dias_restantes'] * -1 . ' días)' : 
                                               ($parte['dias_restantes'] > 0 ? '(' . $parte['dias_restantes'] . ' días)' : '(hoy)') }}
                                        </span>
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
                
                <div class="grid grid-cols-1 gap-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Monto a Pagar:</label>
                        <input type="number" step="0.01" min="0" 
                               class="w-full py-2 px-3 border border-gray-300 rounded focus:border-teal-500 focus:ring-1 focus:ring-teal-500" 
                               placeholder="Ingrese el monto a pagar">
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

            <!-- Botones de acción -->
            <div class="flex justify-end space-x-4 mt-6">
                <button type="button" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                    Cancelar
                </button>
                <button type="button" 
                        wire:click="registrarPago"
                        class="px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition-colors">
                    Registrar Pago
                </button>
            </div>
        @elseif($entidadSeleccionada)
            <!-- Vista de documentos por entidad seleccionada -->
            <div class="mb-6 sm:mb-8">
                <div class="flex justify-between items-center">
                    <h3 class="text-sm font-bold pb-2 mb-3 text-teal-600 bg-gray-50 p-2 rounded">
                        DOCUMENTOS PENDIENTES DE {{ mb_strtoupper($entidadesPendientes[array_search($entidadSeleccionada, array_column($entidadesPendientes, 'id'))]['nombre']) }}
                    </h3>
                    <div class="flex items-center space-x-2">
                        <button type="button" wire:click="seleccionarTodosDocumentos" 
                                class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                            </svg>
                            Seleccionar Todos
                        </button>
                        <button type="button" wire:click="deseleccionarTodosDocumentos" 
                                class="px-3 py-1 text-sm bg-gray-500 text-white rounded hover:bg-gray-600 transition flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Limpiar
                        </button>
                        <button type="button" wire:click="limpiarEntidadSeleccionada" 
                                class="px-3 py-1 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                            </svg>
                            Volver
                        </button>
                    </div>
                </div>
                
                <div class="overflow-x-auto bg-white border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nº Parte
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha Emisión
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha Venc.
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pagado
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pendiente
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    A Pagar
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if(count($documentosPorEntidad) > 0)
                                @foreach($documentosPorEntidad as $parte)
                                    <tr class="hover:bg-gray-50 {{ in_array($parte['id'], $documentosSeleccionados) ? 'bg-blue-50' : '' }}">
                                        <td class="px-2 py-4 whitespace-nowrap text-center">
                                            <input type="checkbox" 
                                                   wire:model.live="documentosSeleccionados" 
                                                   value="{{ $parte['id'] }}"
                                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $parte['numero_parte'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $parte['fecha_inicio'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $parte['fecha_vencimiento'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            S/ {{ $parte['importe_total_fmt'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">
                                            S/ {{ $parte['monto_pagado_fmt'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">
                                            S/ {{ $parte['monto_pendiente_fmt'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if(in_array($parte['id'], $documentosSeleccionados))
                                                <input type="number" 
                                                       value="{{ isset($montosPorDocumento[$parte['id']]) ? $montosPorDocumento[$parte['id']] : $parte['monto_pendiente'] }}"
                                                       wire:model.live.debounce.500ms="montosPorDocumento.{{ $parte['id'] }}" 
                                                       wire:input.debounce.500ms="actualizarMontoPago({{ $parte['id'] }}, $event.target.value)"
                                                       step="0.01" min="0" max="{{ $parte['monto_pendiente'] }}"
                                                       class="w-24 py-1 px-2 border border-gray-300 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm">
                                            @else
                                                <span class="text-sm text-gray-400">--</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $parte['estado_fecha'] == 'Vencido' ? 'bg-red-100 text-red-800' : 
                                                   ($parte['estado_fecha'] == 'Urgente' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                                {{ $parte['estado_fecha'] }}
                                                {{ $parte['dias_restantes'] < 0 ? '(' . $parte['dias_restantes'] * -1 . ' días)' : 
                                                   ($parte['dias_restantes'] > 0 ? '(' . $parte['dias_restantes'] . ' días)' : '(hoy)') }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No hay documentos pendientes para esta entidad.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                
                <!-- Panel de resumen y pago -->
                @if(count($documentosSeleccionados) > 0)
                    <div class="p-6 bg-white rounded-lg shadow-md">
                        <div id="resumen-pago" class="mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">RESUMEN DE PAGO</h3>
                            <p class="mb-1">Documentos seleccionados: {{ count($documentosSeleccionados) }}</p>
                            <p class="text-red-600 font-bold">Total a pagar: S/ {{ number_format($totalAPagar, 2) }}</p>
                        </div>

                        <div id="form-pago" class="grid grid-cols-1 gap-4">
                            <div class="flex justify-end mt-4">
                                <button wire:click="registrarPagoMultiple" class="bg-teal-600 text-white px-4 py-2 rounded-md hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition-colors">
                                    Registrar Pago
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @else
            <!-- Tabla de entidades con pagos pendientes -->
            <div class="mb-6 sm:mb-8">
                <h3 class="text-sm font-bold pb-2 mb-3 text-teal-600 bg-gray-50 p-2 rounded">ENTIDADES CON PAGOS PENDIENTES</h3>
                
                <div class="overflow-x-auto bg-white border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cliente
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Documentos
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total Pendiente
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acción
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if(count($entidadesPendientes) > 0)
                                @foreach($entidadesPendientes as $entidad)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $entidad['nombre'] }}</div>
                                            <div class="text-xs text-gray-500">Código: {{ $entidad['id'] }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $entidad['total_documentos'] }} documento(s)</div>
                                            <div class="flex space-x-2 mt-1">
                                                @if($entidad['documentos_vencidos'] > 0)
                                                    <span class="px-2 py-1 text-xs leading-4 rounded-full bg-red-100 text-red-800">
                                                        {{ $entidad['documentos_vencidos'] }} vencido(s)
                                                    </span>
                                                @endif
                                                @if($entidad['documentos_urgentes'] > 0)
                                                    <span class="px-2 py-1 text-xs leading-4 rounded-full bg-yellow-100 text-yellow-800">
                                                        {{ $entidad['documentos_urgentes'] }} urgente(s)
                                                    </span>
                                                @endif
                                                @if($entidad['documentos_pendientes'] > 0)
                                                    <span class="px-2 py-1 text-xs leading-4 rounded-full bg-green-100 text-green-800">
                                                        {{ $entidad['documentos_pendientes'] }} pendiente(s)
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-red-600">S/ {{ $entidad['monto_pendiente_fmt'] }}</div>
                                            <div class="text-xs text-gray-500">Monto total: S/ {{ $entidad['monto_total_fmt'] }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $entidad['documentos_vencidos'] > 0 ? 'bg-red-100 text-red-800' : 
                                                   ($entidad['documentos_urgentes'] > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                                {{ $entidad['documentos_vencidos'] > 0 ? 'Con documentos vencidos' : 
                                                   ($entidad['documentos_urgentes'] > 0 ? 'Con documentos urgentes' : 'Pendiente') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button wire:click="seleccionarEntidad({{ $entidad['id'] }})" 
                                                    class="text-teal-600 hover:text-teal-900 bg-teal-50 px-3 py-1 rounded-md">
                                                Ver Documentos
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No hay entidades con pagos pendientes.
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
