<div class="flex justify-center mt-16">
    <div class="w-full max-w-6xl mx-auto">
        <!-- Encabezado -->
        <div class="bg-teal-600 text-white p-4 rounded-t-lg shadow-md">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold uppercase">{{ auth()->user()->empresa->nombre ?? 'METAMSUR S.A.C.' }}</h2>
                    <p class="text-sm">RUC: {{ auth()->user()->empresa->ruc ?? '20506666558' }}</p>
                </div>
                <div class="flex-1 text-center">
                    <h1 class="text-xl font-bold uppercase">MATRIZ DE MAQUINARIA</h1>
                </div>
<div>
                    <p class="text-sm">Fecha: {{ date('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="bg-white overflow-hidden shadow-xl rounded-b-lg p-5">
            
            <!-- Tabs para Partes Pagados y Pendientes -->
            <div x-data="{ activeTab: 'pendientes' }">
                <!-- Tabs -->
                <div class="flex border-b border-gray-200 mb-4">
                    <button @click="activeTab = 'pendientes'" :class="{ 'border-teal-500 text-teal-600': activeTab === 'pendientes', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'pendientes' }" class="py-2 px-4 font-medium border-b-2 transition-colors duration-200">
                        Partes Pendientes ({{ is_array($partesPendientes) ? count($partesPendientes) : 0 }})
                    </button>
                    <button @click="activeTab = 'pagados'" :class="{ 'border-teal-500 text-teal-600': activeTab === 'pagados', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'pagados' }" class="py-2 px-4 font-medium border-b-2 transition-colors duration-200">
                        Partes Pagados ({{ is_array($partesPagados) ? count($partesPagados) : 0 }})
                    </button>
                </div>

                <!-- Panel Partes Pagados -->
                <div x-show="activeTab === 'pagados'" x-transition>
                    <!-- Filtros para Pagados -->
                    <div class="mb-4 p-4 bg-gray-50 rounded-md border border-gray-200 shadow-sm">
                        <h3 class="text-lg font-medium text-gray-800 mb-3">Filtros de búsqueda</h3>
                        <div class="flex flex-wrap gap-4">
                            <div class="flex-1 min-w-[180px]">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Entidad</label>
                                <input type="text" wire:model.live="filtroPagadosEntidad" placeholder="Buscar entidad..." class="w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500">
                            </div>
                            <div class="flex-1 min-w-[180px]">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Serie-Número</label>
                                <input type="text" wire:model.live="filtroPagadosSerie" placeholder="Buscar..." class="w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500">
                            </div>
                            <div class="flex-1 min-w-[180px]">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha desde</label>
                                <input type="date" wire:model.live="filtroPagadosFechaDesde" class="w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500">
                            </div>
                            <div class="flex-1 min-w-[180px]">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha hasta</label>
                                <input type="date" wire:model.live="filtroPagadosFechaHasta" class="w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500">
                            </div>
                            <div class="flex items-end">
                                <button wire:click="limpiarFiltrosPagados" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md text-gray-700 transition-colors text-sm font-medium">
                                    Limpiar filtros
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr class="bg-gray-100 text-gray-700">
                                    <th class="py-2 px-4 border-b text-left">Entidad</th>
                                    <th class="py-2 px-4 border-b text-left">Serie-Número</th>
                                    <th class="py-2 px-4 border-b text-left">Fecha Emisión</th>
                                    <th class="py-2 px-4 border-b text-right">Precio</th>
                                    <th class="py-2 px-4 border-b text-right">Monto</th>
                                    <th class="py-2 px-4 border-b text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($partesPagados as $parte)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-2 px-4 border-b">{{ $parte->entidad_descripcion ?? 'N/A' }}</td>
                                        <td class="py-2 px-4 border-b">{{ $parte->serie }}-{{ $parte->numero }}</td>
                                        <td class="py-2 px-4 border-b">{{ date('d/m/Y', strtotime($parte->fechaEmi)) }}</td>
                                        <td class="py-2 px-4 border-b text-right">S/. {{ number_format($parte->precio, 2) }}</td>
                                        <td class="py-2 px-4 border-b text-right">S/. {{ number_format($parte->monto, 2) }}</td>
                                        <td class="py-2 px-4 border-b text-center text-green-600">
                                            <div>Pagado</div>
                                            <div class="text-xs text-gray-500">
                                                {{ $parte->fecha_pago ? date('d/m/Y', strtotime($parte->fecha_pago)) : 'Fecha no registrada' }}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-4 text-center text-gray-500">No hay documentos pagados registrados en este año.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-50 font-semibold">
                                    <td colspan="4" class="py-2 px-4 border-t text-right">Total:</td>
                                    <td class="py-2 px-4 border-t text-right">S/. {{ number_format(array_sum(array_column($partesPagados, 'monto')), 2) }}</td>
                                    <td class="py-2 px-4 border-t"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Panel Partes Pendientes -->
                <div x-show="activeTab === 'pendientes'" x-transition>
                    <!-- Filtros para Pendientes -->
                    <div class="mb-4 p-4 bg-gray-50 rounded-md border border-gray-200 shadow-sm">
                        <h3 class="text-lg font-medium text-gray-800 mb-3">Filtros de búsqueda</h3>
                        <div class="flex flex-wrap gap-4">
                            <div class="flex-1 min-w-[180px]">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Entidad</label>
                                <input type="text" wire:model.live="filtroPendientesEntidad" placeholder="Buscar entidad..." class="w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500">
                            </div>
                            <div class="flex-1 min-w-[180px]">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Serie-Número</label>
                                <input type="text" wire:model.live="filtroPendientesSerie" placeholder="Buscar..." class="w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500">
                            </div>
                            <div class="flex-1 min-w-[180px]">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha desde</label>
                                <input type="date" wire:model.live="filtroPendientesFechaDesde" class="w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500">
                            </div>
                            <div class="flex-1 min-w-[180px]">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha hasta</label>
                                <input type="date" wire:model.live="filtroPendientesFechaHasta" class="w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500">
                            </div>
                            <div class="flex-1 min-w-[180px]">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                                <select wire:model.live="filtroPendientesEstado" class="w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500">
                                    <option value="">Todos</option>
                                    <option value="vencido">Vencidos</option>
                                    <option value="urgente">Urgentes</option>
                                    <option value="proximo">Próximos</option>
                                    <option value="a_tiempo">A tiempo</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button wire:click="limpiarFiltrosPendientes" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md text-gray-700 transition-colors text-sm font-medium">
                                    Limpiar filtros
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr class="bg-gray-100 text-gray-700">
                                    <th class="py-2 px-4 border-b text-left">Entidad</th>
                                    <th class="py-2 px-4 border-b text-left">Serie-Número</th>
                                    <th class="py-2 px-4 border-b text-left">Fecha Emisión</th>
                                    <th class="py-2 px-4 border-b text-right">Precio</th>
                                    <th class="py-2 px-4 border-b text-right">Monto Pendiente</th>
                                    <th class="py-2 px-4 border-b text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($partesPendientes as $parte)
                                    @php
                                        // Calcular días transcurridos desde la emisión
                                        $diasDesdeEmision = \Carbon\Carbon::parse($parte->fechaEmi)->diffInDays(now());
                                        
                                        // Calcular días hasta/desde vencimiento
                                        $fechaVen = $parte->fechaVen ? \Carbon\Carbon::parse($parte->fechaVen) : null;
                                        $hoy = \Carbon\Carbon::now();
                                        
                                        // Determinar estado y clase CSS basado en días de vencimiento
                                        $estadoVencimiento = 'Normal';
                                        $claseDias = 'text-gray-700';
                                        
                                        // Verificar si el monto es nulo o cero, tratarlo como urgente
                                        if (is_null($parte->monto) || $parte->monto == 0) {
                                            $estadoVencimiento = 'Urgente (0 días)';
                                            $claseDias = 'text-orange-600 font-semibold';
                                        } else if ($fechaVen) {
                                            if ($hoy->gt($fechaVen)) {
                                                // Documento vencido
                                                $diasVencido = $fechaVen->diffInDays($hoy);
                                                // Si está vencido pero con 0 días, mostrarlo como Urgente
                                                if ($diasVencido == 0) {
                                                    $estadoVencimiento = 'Urgente (0 días)';
                                                    $claseDias = 'text-orange-600 font-semibold';
                                                } else {
                                                    $estadoVencimiento = 'Vencido (' . $diasVencido . ' días)';
                                                    $claseDias = 'text-red-600 font-bold';
                                                }
                                            } else {
                                                // Próximo a vencer
                                                $diasHastaVencimiento = $hoy->diffInDays($fechaVen);
                                                
                                                if ($diasHastaVencimiento <= 5) {
                                                    $estadoVencimiento = 'Urgente (' . $diasHastaVencimiento . ' días)';
                                                    $claseDias = 'text-orange-600 font-semibold';
                                                } elseif ($diasHastaVencimiento <= 15) {
                                                    $estadoVencimiento = 'Próximo (' . $diasHastaVencimiento . ' días)';
                                                    $claseDias = 'text-yellow-600';
                                                } else {
                                                    $estadoVencimiento = 'A tiempo (' . $diasHastaVencimiento . ' días)';
                                                    $claseDias = 'text-green-600';
                                                }
                                            }
                                        } else {
                                            // Sin fecha de vencimiento, usar días desde emisión como referencia
                                            if ($diasDesdeEmision > 30) {
                                                $estadoVencimiento = 'Pendiente prolongado';
                                                $claseDias = 'text-red-600 font-semibold';
                                            } elseif ($diasDesdeEmision > 15) {
                                                $estadoVencimiento = 'Pendiente medio';
                                                $claseDias = 'text-yellow-600';
                                            } else {
                                                $estadoVencimiento = 'Pendiente reciente';
                                                $claseDias = 'text-green-600';
                                            }
                                        }
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-2 px-4 border-b">{{ $parte->entidad_descripcion ?? 'N/A' }}</td>
                                        <td class="py-2 px-4 border-b">{{ $parte->serie }}-{{ $parte->numero }}</td>
                                        <td class="py-2 px-4 border-b">{{ date('d/m/Y', strtotime($parte->fechaEmi)) }}</td>
                                        <td class="py-2 px-4 border-b text-right">S/. {{ number_format($parte->precio, 2) }}</td>
                                        <td class="py-2 px-4 border-b text-right">S/. {{ number_format($parte->monto, 2) }}</td>
                                        <td class="py-2 px-4 border-b text-center {{ $claseDias }}">
                                            <div>{{ $estadoVencimiento }}</div>
                                            <div class="text-xs text-gray-500">
                                                @if($parte->fechaVen)
                                                    Vence: {{ date('d/m/Y', strtotime($parte->fechaVen)) }}
                                                @else
                                                    Emisión: {{ $diasDesdeEmision }} días atrás
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-4 text-center text-gray-500">No hay documentos pendientes registrados en este año.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-50 font-semibold">
                                    <td colspan="4" class="py-2 px-4 border-t text-right">Total:</td>
                                    <td class="py-2 px-4 border-t text-right">S/. {{ number_format(array_sum(array_column($partesPendientes, 'monto')), 2) }}</td>
                                    <td class="py-2 px-4 border-t"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
