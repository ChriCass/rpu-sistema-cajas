<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Matriz de Pagos
        </h2>
    </x-slot>
    <div class="container mx-auto p-4">
        <div class="p-4 sm:p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Matriz de Pagos</h2>
            
            <!-- Filtros -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <div class="flex flex-col space-y-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Estado del movimiento</h3>
                        <div class="flex flex-wrap gap-4">
                            <label class="inline-flex items-center">
                                <input type="radio" wire:model.live="tempFiltroStatus" value="pendiente" class="form-radio h-5 w-5 text-blue-600">
                                <span class="ml-2 text-gray-700">Pendiente</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" wire:model.live="tempFiltroStatus" value="pagado" class="form-radio h-5 w-5 text-blue-600">
                                <span class="ml-2 text-gray-700">Pagado</span>
                            </label>
                        </div>
                    </div>

                    <!-- Selector de Empresas - Aparece solo cuando se selecciona "pagado" -->
                    @if($mostrarSelectorEmpresas)
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 mb-4" x-data="{ isOpen: false }">
                        <h3 class="text-lg font-medium text-blue-800 mb-2">Seleccione una empresa</h3>
                        <p class="text-sm text-blue-600 mb-3">Para optimizar el rendimiento, seleccione la empresa cuyos pagos desea consultar.</p>
                        
                        <div class="relative mb-3">
                            <input
                                type="text"
                                wire:model.live.debounce.300ms="searchTerm"
                                placeholder="Buscar por nombre o ID..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                @focus="isOpen = true"
                                @click.away="isOpen = false"
                                @keydown.escape.window="isOpen = false"
                            >
                            
                            @if(count($this->filtrarEmpresas()) > 0 && strlen($searchTerm) > 0)
                            <div x-show="isOpen" class="absolute z-10 w-full bg-white mt-1 border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto">
                                @foreach($this->filtrarEmpresas() as $empresa)
                                <div 
                                    wire:key="empresa-{{ $empresa['id'] }}"
                                    wire:click="seleccionarEmpresa({{ $empresa['id'] }})"
                                    class="px-4 py-2 cursor-pointer hover:bg-gray-100 flex justify-between items-center border-b last:border-b-0"
                                >
                                    <div>
                                        <div class="font-medium">{{ $empresa['descripcion'] }}</div>
                                        <div class="text-xs text-gray-500">ID: {{ $empresa['id'] }}</div>
                                    </div>
                                    <div>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        
                        @if($empresaSeleccionada)
                            @php
                                $empresaInfo = collect($empresas)->firstWhere('id', $empresaSeleccionada);
                            @endphp
                            <div class="mt-3 p-3 bg-white rounded-md border border-green-200 flex justify-between items-center">
                                <div>
                                    <span class="text-sm font-semibold text-gray-700">Empresa seleccionada:</span>
                                    <span class="ml-2 text-sm text-gray-600">{{ $empresaInfo['descripcion'] }}</span>
                                    <span class="ml-2 text-xs text-gray-500">(ID: {{ $empresaInfo['id'] }})</span>
                                </div>
                                <button 
                                    wire:click="seleccionarEmpresa(null)"
                                    class="text-xs text-red-600 hover:text-red-800 transition-colors duration-200"
                                >
                                    Cambiar empresa
                                </button>
                            </div>
                        @endif
                    </div>
                    @endif

                    <div class="flex justify-end">
                        <button wire:click="procesar" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-600 disabled:opacity-25 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Procesar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros mejorados y tabla con cabeceras -->
        <div class="overflow-x-auto mb-6">
            <div class="bg-white p-4 rounded-lg shadow-md mb-4">
                <div class="mb-3">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                        </svg>
                        Filtros de búsqueda
                    </h3>
                    <p class="text-sm text-gray-500">Filtra la matriz de pagos usando los siguientes campos. Todos los filtros se aplican en tiempo real.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="space-y-1">
                        <label for="filter-id" class="text-xs font-medium text-gray-700">ID</label>
                        <x-maskable id="filter-id" mask="########" wire:model.live="filters.id" placeholder="Ej: 12345678" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            :disabled="empty($movimientos)" 
                            title="Filtrar por número de ID" />
                    </div>
                    
                    <div class="space-y-1">
                        <label for="filter-tdoc" class="text-xs font-medium text-gray-700">Tipo Documento</label>
                        <x-input id="filter-tdoc" wire:model.live="filters.tdoc" placeholder="Ej: F001" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            :disabled="empty($movimientos)" 
                            title="Filtrar por tipo de documento" />
                    </div>
                    
                    <div class="space-y-1">
                        <label for="filter-doc" class="text-xs font-medium text-gray-700">Documento</label>
                        <x-input id="filter-doc" wire:model.live="filters.Doc" placeholder="Ej: 00123" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            :disabled="empty($movimientos)" 
                            title="Filtrar por número de documento" />
                    </div>
                    
                    <div class="space-y-1">
                        <label for="filter-id-entidades" class="text-xs font-medium text-gray-700">ID Entidad</label>
                        <x-maskable id="filter-id-entidades" mask="###########" wire:model.live="filters.id_entidades" placeholder="Ej: 20123456789" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            :disabled="empty($movimientos)" 
                            title="Filtrar por ID de entidad (RUC)" />
                    </div>
                    
                    <div class="space-y-1">
                        <label for="filter-entidad" class="text-xs font-medium text-gray-700">Entidad</label>
                        <x-input id="filter-entidad" wire:model.live="filters.Deski" placeholder="Nombre de entidad" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            :disabled="empty($movimientos)" 
                            title="Filtrar por nombre de entidad" />
                    </div>
                    
                    <div class="space-y-1">
                        <label for="filter-usuario" class="text-xs font-medium text-gray-700">Usuario</label>
                        <x-input id="filter-usuario" wire:model.live="filters.name" placeholder="Nombre de usuario" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            :disabled="empty($movimientos)" 
                            title="Filtrar por nombre de usuario" />
                    </div>
                    
                    <div class="space-y-1">
                        <label for="filter-moneda" class="text-xs font-medium text-gray-700">Moneda</label>
                        <x-input id="filter-moneda" wire:model.live="filters.id_t04tipmon" placeholder="Ej: PEN, USD" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            :disabled="empty($movimientos)" 
                            title="Filtrar por tipo de moneda (PEN, USD)" />
                    </div>
                    
                    <div class="space-y-1">
                        <label for="filter-estado" class="text-xs font-medium text-gray-700">Estado</label>
                        <x-input id="filter-estado" wire:model.live="filters.estadoMon" placeholder="Ej: PENDIENTE, URGENTE" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            :disabled="empty($movimientos)" 
                            title="Filtrar por estado (PENDIENTE, URGENTE, VENCIDO, PAGADO)" />
                    </div>
                </div>
                
                <div class="flex justify-between items-center mt-4">
                    <div class="text-xs text-gray-500">
                        Use los filtros para refinar la búsqueda
                    </div>
                    
                    <button 
                        wire:click="resetFilters" 
                        class="text-sm text-gray-600 hover:text-red-600 flex items-center transition-colors duration-200"
                        :disabled="empty($movimientos)">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Limpiar filtros
                    </button>
                </div>
            </div>

            @if (!empty($movimientos))
                <div class="bg-white p-3 mb-3 rounded-md shadow-md">
                    <div class="flex justify-between items-center mb-3">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                            </svg>
                            <span class="font-medium text-gray-700">Resultados</span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center">
                                <label for="per-page" class="text-xs font-medium text-gray-700 mr-2">Mostrar:</label>
                                <select wire:model.live="perPage" wire:change="changePerPage($event.target.value)" class="px-4 py-2 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-28">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="0">Todo</option>
                                </select>
                            </div>
                            <span class="text-sm bg-blue-100 text-blue-800 py-1 px-3 rounded-full font-medium">
                                {{ $totalRegistros }} {{ $totalRegistros == 1 ? 'registro encontrado' : 'registros encontrados' }}
                            </span>
                        </div>
                    </div>
                
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-300 rounded-md shadow-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-3 px-4 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">ID</th>
                                    <th class="py-3 px-4 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">T. DOC</th>
                                    <th class="py-3 px-4 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">DOC</th>
                                    <th class="py-3 px-4 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">FECHA EMI</th>
                                    <th class="py-3 px-4 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">ID ENTIDAD</th>
                                    <th class="py-3 px-4 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">ENTIDAD</th>
                                    <th class="py-3 px-4 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">USUARIO</th>
                                    <th class="py-3 px-4 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">MONEDA</th>
                                    <th class="py-3 px-4 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">FACTURADO</th>
                                    <th class="py-3 px-4 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">MONTO</th>
                                    <th class="py-3 px-4 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">MONTO K</th>
                                    <th class="py-3 px-4 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">ESTADO</th>
                                    <th class="py-3 px-4 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">DIAS</th>
                                    <th class="py-3 px-4 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">FEC VEN</th>
                                    <th class="py-3 px-4 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">DETRACCION</th>
                                    <th class="py-3 px-4 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">ESTADO DETR</th>
                                    <th class="py-3 px-4 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">VEN DETRACCION</th>
                                    <th class="py-3 px-4 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">OBSERVACION</th>
                                    <th class="py-3 px-4 border-b font-medium text-sm text-gray-700 uppercase tracking-wider">PAGOS</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($paginatedMovimientos as $index => $movimiento)
                                    <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100 transition-colors duration-150">
                                        <td class="px-4 py-2 border-b">{{ $movimiento->id }}</td>
                                        <td class="px-4 py-2 border-b">{{ $movimiento->tdoc }}</td>
                                        <td class="px-4 py-2 border-b">{{ $movimiento->Doc }}</td>
                                        <td class="px-4 py-2 border-b">{{ $movimiento->fechaemi }}</td>
                                        <td class="px-4 py-2 border-b">{{ $movimiento->id_entidades }}</td>
                                        <td class="px-4 py-2 border-b">{{ $movimiento->Deski }}</td>
                                        <td class="px-4 py-2 border-b">{{ $movimiento->name }}</td>
                                        <td class="px-4 py-2 border-b">{{ $movimiento->id_t04tipmon }}</td>
                                        <td class="px-4 py-2 border-b">{{ $movimiento->Facturado }}</td>
                                        <td class="px-4 py-2 border-b">{{ $movimiento->monto }}</td>
                                        <td class="px-4 py-2 border-b">{{ $movimiento->montoK }}</td>
                                        <td class="px-4 py-2 border-b">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                                {{ $movimiento->estadoMon == 'URGENTE' ? 'bg-yellow-100 text-yellow-800' : 
                                                   ($movimiento->estadoMon == 'VENCIDO' ? 'bg-red-100 text-red-800' : 
                                                   ($movimiento->estadoMon == 'PENDIENTE' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800')) }}">
                                                {{ $movimiento->estadoMon }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 border-b">{{ $movimiento->dias ?? '-' }}</td>
                                        <td class="px-4 py-2 border-b">{{ $movimiento->fechaVen }}</td>
                                        <td class="px-4 py-2 border-b">{{ $movimiento->detraccion ?? '-' }}</td>
                                        <td class="px-4 py-2 border-b">
                                            @if(!empty($movimiento->EstadoDetr))
                                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                                    {{ $movimiento->EstadoDetr == 'URGENTE' ? 'bg-yellow-100 text-yellow-800' : 
                                                       ($movimiento->EstadoDetr == 'VENCIDO' ? 'bg-red-100 text-red-800' : 
                                                       ($movimiento->EstadoDetr == 'PENDIENTE' ? 'bg-green-100 text-green-800' : 
                                                       ($movimiento->EstadoDetr == 'PAGADO' ? 'bg-blue-100 text-blue-800' : ''))) }}">
                                                    {{ $movimiento->EstadoDetr }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 border-b">{{ $movimiento->VenDetraccion ?? '-' }}</td>
                                        <td class="px-4 py-2 border-b">{{ $movimiento->observaciones ?? '-' }}</td>
                                        <td class="px-4 py-2 border-b">{{ $movimiento->Num ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                
                    <!-- Paginación -->
                    @if($perPage > 0)
                    <div class="flex items-center justify-between mt-4">
                        <div class="text-sm text-gray-700">
                            Mostrando
                            <span class="font-medium">{{ min(($currentPage - 1) * $perPage + 1, $totalRegistros) }}</span>
                            a
                            <span class="font-medium">{{ min($currentPage * $perPage, $totalRegistros) }}</span>
                            de
                            <span class="font-medium">{{ $totalRegistros }}</span>
                            resultados
                        </div>

                        <div class="flex space-x-1">
                            <!-- Botón Anterior -->
                            <button wire:click="setPage({{ max($currentPage - 1, 1) }})" {{ $currentPage <= 1 ? 'disabled' : '' }} class="px-3 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md {{ $currentPage <= 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50' }}">
                                Anterior
                            </button>

                            <!-- Números de página -->
                            <div class="flex space-x-1">
                                @php
                                    $totalPages = $this->getTotalPages();
                                    $range = 2; // Mostrar 2 páginas antes y después de la actual
                                    $startPage = max(1, $currentPage - $range);
                                    $endPage = min($totalPages, $currentPage + $range);
                                @endphp

                                @if($startPage > 1)
                                    <button wire:click="setPage(1)" class="px-3 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                        1
                                    </button>
                                    @if($startPage > 2)
                                        <span class="px-3 py-1 text-sm font-medium text-gray-700">...</span>
                                    @endif
                                @endif

                                @for($i = $startPage; $i <= $endPage; $i++)
                                    <button wire:click="setPage({{ $i }})" class="px-3 py-1 text-sm font-medium {{ $i == $currentPage ? 'text-blue-600 bg-blue-50 border-blue-500' : 'text-gray-700 bg-white border-gray-300 hover:bg-gray-50' }} border rounded-md">
                                        {{ $i }}
                                    </button>
                                @endfor

                                @if($endPage < $totalPages)
                                    @if($endPage < $totalPages - 1)
                                        <span class="px-3 py-1 text-sm font-medium text-gray-700">...</span>
                                    @endif
                                    <button wire:click="setPage({{ $totalPages }})" class="px-3 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                        {{ $totalPages }}
                                    </button>
                                @endif
                            </div>

                            <!-- Botón Siguiente -->
                            <button wire:click="setPage({{ min($currentPage + 1, $totalPages) }})" {{ $currentPage >= $totalPages ? 'disabled' : '' }} class="px-3 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md {{ $currentPage >= $totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50' }}">
                                Siguiente
                            </button>
                        </div>
                    </div>
                    @else
                    <div class="mt-4 text-sm text-gray-700">
                        Mostrando todos los {{ $totalRegistros }} resultados
                    </div>
                    @endif
                </div>
            @else
                <div
                    class="px-6 py-4 rounded-lg shadow-lg text-center
                        @if ($hasFiltered) bg-red-100 text-red-800 @else bg-yellow-100 text-yellow-800 @endif">
                    @if ($hasFiltered)
                        <h2 class="text-xl font-bold mb-2">No hay registros con los filtros aplicados</h2>
                        <p>Prueba con otros filtros o revisa los datos ingresados.</p>
                    @else
                        <h2 class="text-xl font-bold mb-2">Nada que mostrar</h2>
                        <p>Prueba procesando alguno para ver resultados aquí.</p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Flecha de regresar -->
        <div class="mt-6 flex justify-start">
            <a href="{{ route('dashboard') }}" wire:navigate class="group flex items-center space-x-2">
                <div
                    class="bg-yellow-600 p-3 rounded-full shadow-md transition-all duration-300 transform group-hover:bg-yellow-700 group-hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </div>
                <span class="text-yellow-600 font-bold group-hover:text-yellow-700">Regresar</span>
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('scrollToTop', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });
    </script>
</div>
