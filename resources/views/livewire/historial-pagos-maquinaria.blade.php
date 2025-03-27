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
                <h2 class="text-xl font-bold mb-2">HISTORIAL DE PAGOS DE MAQUINARIA</h2>
                <div class="flex items-center space-x-3">
                    <div class="bg-white text-teal-600 rounded px-3 py-1 inline-block">
                        <span class="font-bold">Registros: {{ count($historialPagos) }}</span>
                    </div>
                    <a href="{{ route('pagos-maquinaria') }}" class="bg-white text-teal-600 hover:bg-teal-100 transition-colors border border-white hover:border-teal-600 rounded px-3 py-1 inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span class="font-medium">Nuevo Voucher</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido principal con padding -->
    <div class="p-2 sm:p-6">
        <!-- Filtros -->
        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
            <h3 class="text-sm font-bold mb-4 text-teal-600 border-b border-gray-300 pb-2">FILTROS DE BÚSQUEDA</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Fecha Inicio -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Fecha Inicio:</label>
                    <input type="date" wire:model="fechaInicio" class="w-full border border-gray-300 rounded py-1 px-2 text-sm focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                </div>
                
                <!-- Fecha Fin -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Fecha Fin:</label>
                    <input type="date" wire:model="fechaFin" class="w-full border border-gray-300 rounded py-1 px-2 text-sm focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                </div>
                
                <!-- Entidad -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Cliente/Entidad:</label>
                    <select wire:model="entidadId" class="w-full border border-gray-300 rounded py-1 px-2 text-sm focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                        <option value="">Todos los clientes</option>
                        @foreach($entidades as $entidad)
                            <option value="{{ $entidad->id }}">{{ $entidad->descripcion }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Número de Parte -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Nº Parte:</label>
                    <input type="text" wire:model.debounce.300ms="numeroParte" class="w-full border border-gray-300 rounded py-1 px-2 text-sm focus:border-teal-500 focus:ring-1 focus:ring-teal-500" placeholder="Ingrese nº parte...">
                </div>
            </div>
            
            <!-- Botones de acción -->
            <div class="flex justify-end mt-4 space-x-2">
                <button wire:click="limpiarFiltros" class="px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-sm transition flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Limpiar
                </button>
                <button wire:click="buscarHistorial" class="px-3 py-1 bg-teal-600 text-white rounded hover:bg-teal-700 text-sm transition flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Buscar
                </button>
            </div>
        </div>
        
        <!-- Tabla de resultados -->
        <div class="overflow-x-auto bg-white rounded-lg border border-gray-200 mb-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nº Mov.</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documentos</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto Total</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($historialPagos as $pago)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $pago->fecha_pago_fmt }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $pago->mov }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $pago->cliente_nombre }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <div class="flex items-center">
                                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2 py-1 rounded mr-2">
                                        {{ $pago->cantidad_documentos }}
                                    </span>
                                    <span class="truncate max-w-[200px]" title="{{ $pago->documentos_relacionados }}">
                                        {{ $pago->documentos_relacionados }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                S/ {{ $pago->monto_fmt }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <button wire:click="verDetallePago({{ $pago->mov }}, {{ $pago->id_apertura }})" class="text-teal-600 hover:text-teal-800 transition flex items-center">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 12s5-8 10-8 10 8 10 8-5 8-10 8-10-8-10-8z" />
                                    </svg>
                                    Ver detalle
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-10 h-10 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-gray-500 mb-1">No se encontraron registros de pagos.</p>
                                    <p class="text-gray-400 text-xs">Prueba con otros criterios de búsqueda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Modal de detalles del pago -->
    @if($mostrarDetalle && $detallesPago)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-5xl w-full max-h-[90vh] overflow-y-auto">
                <div class="bg-teal-600 text-white p-4 flex justify-between items-center">
                    <h3 class="text-lg font-bold">Detalle del Voucher de Pago #{{ $detallesPago->mov }}</h3>
                    <button wire:click="cerrarDetalle" class="text-white hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="p-6">
                    <!-- Información general del voucher -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
                        <h4 class="text-sm font-bold mb-4 text-teal-600 border-b border-gray-300 pb-2">INFORMACIÓN GENERAL DEL VOUCHER</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Nº Movimiento:</label>
                                <div class="text-sm font-medium">{{ $detallesPago->mov }}</div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Fecha de Pago:</label>
                                <div class="text-sm">{{ $detallesPago->fecha_pago_fmt }}</div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Monto Total:</label>
                                <div class="text-sm font-bold">S/ {{ $detallesPago->monto_fmt }}</div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Cliente:</label>
                                <div class="text-sm">{{ $detallesPago->cliente_nombre }}</div>
                                <div class="text-xs text-gray-500">Código: {{ $detallesPago->entidad_id }}</div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Tipo de Caja:</label>
                                <div class="text-sm">{{ $detallesPago->tipo_caja }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tabla de documentos relacionados -->
                    <div class="mb-6">
                        <h4 class="text-sm font-bold mb-4 text-teal-600 border-b border-gray-300 pb-2">DOCUMENTOS INCLUIDOS EN EL PAGO</h4>
                        
                        <div class="overflow-x-auto bg-white rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nº Parte</th>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Emitido</th>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vencimiento</th>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto Pagado</th>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Observación</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($detallesPago->documentos as $documento)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700 font-medium">
                                            {{ $documento->numero_documento }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                            {{ $documento->fecha_emision_fmt ?? 'No disponible' }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                            {{ $documento->fecha_vencimiento_fmt ?? 'No disponible' }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                            S/ {{ $documento->monto_fmt }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $documento->estado_clase }}">
                                                {{ $documento->estado }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-500 max-w-[250px] truncate" title="{{ $documento->glosa }}">
                                            {{ $documento->glosa ?: 'Sin observaciones' }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500">
                                            No hay documentos asociados a este voucher.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Botones de acción -->
                    <div class="flex justify-end mt-6 space-x-3">
                        <button wire:click="confirmarEliminarVoucher({{ $detallesPago->mov }}, {{ $detallesPago->apertura_id }})" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Eliminar Voucher
                        </button>
                        <button onclick="window.print()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4" />
                            </svg>
                            Imprimir Detalle
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal de confirmación para eliminar voucher -->
    @if($mostrarModalConfirmacion)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                <div class="mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Confirmar eliminación</h3>
                    <p class="text-sm text-gray-600 mt-2">
                        ¿Estás seguro de que deseas eliminar este voucher de pago? Esta acción no se puede deshacer y podría afectar a la información contable.
                    </p>
                </div>
                
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                Eliminar un voucher afectará los saldos de los documentos relacionados.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button wire:click="cancelarEliminarVoucher" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                        Cancelar
                    </button>
                    <button wire:click="eliminarVoucher" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                        Sí, eliminar voucher
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
