<div>
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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Nuevo encabezado con estilo verde -->
            <div class="bg-teal-600 text-white p-4 rounded-t-lg shadow-md">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold uppercase">{{ $parteActual->empresa->nombre ?? 'METAMSUR S.A.C.' }}</h2>
                        <p class="text-sm">RUC: {{ $parteActual->empresa->ruc ?? '20506666558' }}</p>
                    </div>
                    <div class="flex-1 text-center">
                        <h1 class="text-xl font-bold uppercase">MOVIMIENTOS DE MAQUINARIA</h1>
                    </div>
                    <div class="flex items-center">
                        <div class="text-white rounded-md px-3 py-2 mr-2">
                            <span class="font-semibold">Registros: {{ $partesDiarios->total() }}</span>
                        </div>
                        <a href="{{ route('parte-diario', ['origen' => 'nuevo']) }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-white text-teal-600 border border-transparent rounded-md font-semibold text-sm uppercase tracking-widest hover:bg-gray-100 active:bg-white focus:outline-none focus:border-white focus:ring focus:ring-white/30 disabled:opacity-25 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Nuevo Parte
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-b-lg p-6">
                <!-- Filtros y búsqueda -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6 border border-gray-200">
                    <h3 class="text-lg font-medium text-gray-700 mb-3">FILTROS DE BÚSQUEDA</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="fechaDesde" class="block text-sm font-medium text-gray-700">Fecha Inicio:</label>
                            <input type="date" id="fechaDesde" wire:model.live="filters.fechaDesde" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="fechaHasta" class="block text-sm font-medium text-gray-700">Fecha Fin:</label>
                            <input type="date" id="fechaHasta" wire:model.live="filters.fechaHasta" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="cliente" class="block text-sm font-medium text-gray-700">Cliente/Entidad:</label>
                            <select id="cliente" wire:model.live="filters.cliente" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                                <option value="">Todos los clientes</option>
                                <!-- Opciones de clientes si están disponibles -->
                            </select>
                        </div>
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">N° Parte:</label>
                            <input type="text" id="search" wire:model.live.debounce.300ms="search" placeholder="Ingrese n° parte..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                        </div>
                    </div>
                    
                    <div class="flex justify-end mt-4">
                        <button wire:click="resetFilters" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-medium text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                            </svg>
                            Limpiar
                        </button>
                        <button class="inline-flex items-center px-4 py-2 bg-teal-600 border border-transparent rounded-md font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                            Buscar
                        </button>
                    </div>
                </div>

                <!-- Tabla de resultados con nuevo estilo -->
                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    FECHA
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    N° MOV.
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    CLIENTE
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    MONTO TOTAL
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ACCIONES
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($partesDiarios as $parte)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($parte->fecha_inicio)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $parte->numero_parte }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $parte->entidad->descripcion ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700">
                                        S/ {{ number_format($parte->importe_cobrar, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button wire:click="verDocumento({{ $parte->id }})" class="text-teal-600 hover:text-teal-900 inline-flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                            </svg>
                                            Ver detalle
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        No se encontraron partes diarios
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="mt-4">
                    {{ $partesDiarios->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación para eliminar -->
    @if($confirmarEliminacion)
    <div class="fixed z-[60] inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Confirmar eliminación
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    ¿Está seguro de que desea eliminar este parte diario? Esta acción no se puede deshacer.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="eliminar" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Eliminar
                    </button>
                    <button wire:click="cancelarEliminar" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal para mostrar el documento -->
    @if($documentoModal && $documentoActual)
    <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full">
                <!-- Cabecera del documento - Formato oficial -->
                <div class="bg-teal-600 text-white px-6 py-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-xl font-bold uppercase">{{ $parteActual->empresa->nombre ?? 'METAMSUR S.A.C.' }}</h2>
                            <p class="text-sm">RUC: {{ $parteActual->empresa->ruc ?? '20506666558' }}</p>
                        </div>
                        <div class="text-center">
                            <h1 class="text-xl font-bold uppercase">PARTE DIARIO DE MAQUINARIA</h1>
                            <div class="mt-2 border-2 border-white p-2 rounded">
                                <p class="text-lg font-bold">N° {{ $parteActual->numero_parte }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6">
                    <!-- Periodo de trabajo -->
                    <div class="border border-gray-300 rounded-lg overflow-hidden mb-4">
                        <div class="bg-gray-100 px-4 py-2 border-b border-gray-300">
                            <h3 class="font-bold text-gray-700 uppercase">PERIODO DE TRABAJO</h3>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Fecha de Inicio:</label>
                                    <p class="mt-1 font-semibold">{{ \Carbon\Carbon::parse($parteActual->fecha_inicio)->format('d/m/Y') }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Fecha de Fin:</label>
                                    <p class="mt-1 font-semibold">{{ \Carbon\Carbon::parse($parteActual->fecha_fin)->format('d/m/Y') }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Duración:</label>
                                    <p class="mt-1 font-semibold">
                                        @php
                                            $inicio = \Carbon\Carbon::parse($parteActual->fecha_inicio);
                                            $fin = \Carbon\Carbon::parse($parteActual->fecha_fin);
                                            $duracion = $inicio->diffInDays($fin) + 1; // +1 para incluir el día final
                                        @endphp
                                        {{ $duracion }} día(s)
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Operador y Cliente - En fila -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- Información del operador -->
                        <div class="border border-gray-300 rounded-lg overflow-hidden">
                            <div class="bg-gray-100 px-4 py-2 border-b border-gray-300">
                                <h3 class="font-bold text-gray-700 uppercase">INFORMACIÓN DEL OPERADOR</h3>
                            </div>
                            <div class="p-4">
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700">Operador:</label>
                                    <p class="mt-1 font-semibold">{{ $parteActual->operador->nombre ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Unidad:</label>
                                    <p class="mt-1 font-semibold">{{ $parteActual->unidad->descripcion ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Información del cliente -->
                        <div class="border border-gray-300 rounded-lg overflow-hidden">
                            <div class="bg-gray-100 px-4 py-2 border-b border-gray-300">
                                <h3 class="font-bold text-gray-700 uppercase">INFORMACIÓN DEL CLIENTE</h3>
                            </div>
                            <div class="p-4">
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700">Cliente:</label>
                                    <p class="mt-1 font-semibold">{{ $parteActual->entidad->descripcion ?? 'N/A' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700">Dirección:</label>
                                    <p class="mt-1 font-semibold">{{ $parteActual->entidad->direccion ?? 'Sin dirección registrada' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lugar de trabajo -->
                    <div class="border border-gray-300 rounded-lg overflow-hidden mb-4">
                        <div class="bg-gray-100 px-4 py-2 border-b border-gray-300">
                            <h3 class="font-bold text-gray-700 uppercase">LUGAR DE TRABAJO</h3>
                        </div>
                        <div class="p-4">
                            <p class="font-semibold">{{ $parteActual->ubicacion ?? 'No especificado' }}</p>
                        </div>
                    </div>

                    <!-- Control de horas -->
                    <div class="border border-gray-300 rounded-lg overflow-hidden mb-4 control-horas-section">
                        <div class="bg-gray-100 px-4 py-2 border-b border-gray-300">
                            <h3 class="font-bold text-gray-700 uppercase">CONTROL DE HORAS</h3>
                        </div>
                        <div class="p-4">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 border">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th rowspan="2" class="px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center border-r">CONTROL</th>
                                            <th colspan="3" class="px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center border-r border-b">MAÑANA</th>
                                            <th colspan="3" class="px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center border-r border-b">TARDE</th>
                                            <th rowspan="2" class="px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">TOTAL HORAS</th>
                                        </tr>
                                        <tr class="bg-gray-50">
                                            <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center border-r">INICIO</th>
                                            <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center border-r">TÉRMINO</th>
                                            <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center border-r">T. HORAS</th>
                                            <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center border-r">INICIO</th>
                                            <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center border-r">TÉRMINO</th>
                                            <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center border-r">T. HORAS</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr>
                                            <td class="px-3 py-2 text-sm text-gray-500 text-center border-r font-medium">HORAS DE TRABAJO</td>
                                            <td class="px-3 py-3 text-sm text-gray-500 text-center border-r">
                                                {{ $parteActual->hora_inicio_manana ? \Carbon\Carbon::parse($parteActual->hora_inicio_manana)->format('H:i') : '--:--' }}
                                            </td>
                                            <td class="px-3 py-3 text-sm text-gray-500 text-center border-r">
                                                {{ $parteActual->hora_fin_manana ? \Carbon\Carbon::parse($parteActual->hora_fin_manana)->format('H:i') : '--:--' }}
                                            </td>
                                            <td class="px-3 py-3 text-sm text-gray-500 text-center border-r bg-green-50">{{ isset($parteActual->total_horas_manana) ? number_format($parteActual->total_horas_manana, 2) : '0.00' }}</td>
                                            <td class="px-3 py-3 text-sm text-gray-500 text-center border-r">
                                                {{ $parteActual->hora_inicio_tarde ? \Carbon\Carbon::parse($parteActual->hora_inicio_tarde)->format('H:i') : '--:--' }}
                                            </td>
                                            <td class="px-3 py-3 text-sm text-gray-500 text-center border-r">
                                                {{ $parteActual->hora_fin_tarde ? \Carbon\Carbon::parse($parteActual->hora_fin_tarde)->format('H:i') : '--:--' }}
                                            </td>
                                            <td class="px-3 py-3 text-sm text-gray-500 text-center border-r bg-green-50">{{ isset($parteActual->total_horas_tarde) ? number_format($parteActual->total_horas_tarde, 2) : '0.00' }}</td>
                                            <td class="px-3 py-3 text-sm text-gray-700 text-center font-bold bg-green-50">{{ isset($parteActual->total_horas) ? number_format($parteActual->total_horas, 2) : '0.00' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-2 text-sm text-gray-500 text-center border-r font-medium">HORÓMETRO</td>
                                            <td class="px-3 py-3 text-sm text-gray-500 text-center border-r">{{ isset($parteActual->horometro_inicial) ? number_format($parteActual->horometro_inicial, 2) : '0.00' }}</td>
                                            <td class="px-3 py-3 text-sm text-gray-500 text-center border-r">{{ isset($parteActual->horometro_inicial) ? number_format($parteActual->horometro_inicial, 2) : '0.00' }}</td>
                                            <td class="px-3 py-3 text-sm text-gray-500 text-center border-r bg-green-50">0.00</td>
                                            <td class="px-3 py-3 text-sm text-gray-500 text-center border-r">{{ isset($parteActual->horometro_final) ? number_format($parteActual->horometro_final, 2) : '0.00' }}</td>
                                            <td class="px-3 py-3 text-sm text-gray-500 text-center border-r">{{ isset($parteActual->horometro_final) ? number_format($parteActual->horometro_final, 2) : '0.00' }}</td>
                                            <td class="px-3 py-3 text-sm text-gray-500 text-center border-r bg-green-50">0.00</td>
                                            <td class="px-3 py-3 text-sm text-gray-700 text-center font-bold bg-green-50">
                                                @if(isset($parteActual->horometro_final) && isset($parteActual->horometro_inicial))
                                                    {{ number_format($parteActual->horometro_final - $parteActual->horometro_inicial, 2) }}
                                                @else
                                                    0.00
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-2 text-sm text-gray-500 text-center border-r font-medium">INTERRUPCIONES</td>
                                            <td colspan="7" class="px-3 py-2 text-sm text-gray-500 text-center">DESCRIPCIÓN DE INTERRUPCIONES (si las hubiera)</td>
                                        </tr>
                                        @if(isset($parteActual->interrupciones) && !empty($parteActual->interrupciones))
                                        <tr>
                                            <td class="border-r"></td>
                                            <td colspan="7" class="px-3 py-2 text-sm text-gray-500">{{ $parteActual->interrupciones }}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Valorización -->
                    <div class="border border-gray-300 rounded-lg overflow-hidden mb-4 valorization-section">
                        <div class="bg-gray-100 px-4 py-2 border-b border-gray-300">
                            <h3 class="font-bold text-gray-700 uppercase">VALORIZACIÓN</h3>
                        </div>
                        <div class="p-4">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 border">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center border-r">HORAS TRABAJADAS</th>
                                            <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center border-r">PRECIO/H</th>
                                            <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">IMPORTE A COBRAR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="px-3 py-3 text-sm text-gray-700 text-center border-r font-medium">{{ isset($parteActual->total_horas) ? number_format($parteActual->total_horas, 2) : '0.00' }}</td>
                                            <td class="px-3 py-3 text-sm text-gray-700 text-center border-r font-medium">S/ {{ isset($parteActual->precio_hora) ? number_format($parteActual->precio_hora, 2) : '0.00' }}</td>
                                            <td class="px-3 py-3 text-sm text-gray-700 text-center font-bold">S/ {{ isset($parteActual->importe_cobrar) ? number_format($parteActual->importe_cobrar, 2) : '0.00' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Descripción del trabajo - Tipo de venta -->
                    <div class="border border-gray-300 rounded-lg overflow-hidden mb-4">
                        <div class="bg-gray-100 px-4 py-2 border-b border-gray-300">
                            <h3 class="font-bold text-gray-700 uppercase">DESCRIPCIÓN DEL TRABAJO</h3>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="font-medium text-gray-700">Tipo de venta:</p>
                                    <p class="mt-1 mb-3 font-semibold">{{ $parteActual->tipoVenta->descripcion ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-700">Documento relacionado:</p>
                                    <p class="mt-1 mb-3 font-semibold">
                                        Serie: {{ $documentoActual->serie ?? '0000' }} | 
                                        Número: {{ $documentoActual->numero ?? $parteActual->numero_parte }} | 
                                        Fecha: {{ isset($documentoActual->fecha) ? \Carbon\Carbon::parse($documentoActual->fecha)->format('d/m/Y') : \Carbon\Carbon::parse($parteActual->fecha_inicio)->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    <div class="border border-gray-300 rounded-lg overflow-hidden mb-4">
                        <div class="bg-gray-100 px-4 py-2 border-b border-gray-300">
                            <h3 class="font-bold text-gray-700 uppercase">OBSERVACIONES</h3>
                        </div>
                        <div class="p-4">
                            <p class="text-gray-700">{{ $parteActual->observaciones ?? 'Sin observaciones' }}</p>
                        </div>
                    </div>

                    <!-- Estado de pago -->
                    <div class="border border-gray-300 rounded-lg overflow-hidden mb-4">
                        <div class="bg-gray-100 px-4 py-2 border-b border-gray-300">
                            <h3 class="font-bold text-gray-700 uppercase">ESTADO DE PAGO</h3>
                        </div>
                        <div class="p-4">
                            <div class="flex items-center">
                                <span class="font-medium mr-2">Estado:</span>
                                @if($parteActual->estado_pago == '0')
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">PENDIENTE DE PAGO</span>
                                @elseif($parteActual->estado_pago == '1')
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">PAGO PARCIAL</span>
                                @elseif($parteActual->estado_pago == '2')
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">PAGADO</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end">
                    <button wire:click="cerrarModalDocumento" type="button" class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:w-auto sm:text-sm">
                        Cerrar
                    </button>
                    <button wire:click="verDetalle({{ $parteActual->id }})" type="button" class="ml-3 inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto sm:text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        Editar
                    </button>
                    <button wire:click="confirmarEliminar({{ $parteActual->id }})" type="button" class="ml-3 inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:w-auto sm:text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Eliminar
                    </button>
                    <button wire:click="imprimirDocumento" type="button" class="ml-3 inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:w-auto sm:text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
                        </svg>
                        Imprimir
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Script para impresión -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('imprimir-documento', () => {
                // Seleccionar todo el contenido del modal sin los botones
                const modalContent = document.querySelector('.inline-block.align-bottom');
                
                if (modalContent) {
                    // Clonar el contenido para no modificar el original
                    const clonedContent = modalContent.cloneNode(true);
                    
                    // Eliminar los botones de la copia (el footer)
                    const footer = clonedContent.querySelector('.bg-gray-50.px-4.py-3');
                    if (footer) {
                        footer.remove();
                    }
                    
                    // Establecer el estilo para impresión
                    const originalStyles = document.head.innerHTML;
                    const printStyles = `
                        <style>
                            @media print {
                                body * { visibility: hidden; }
                                #print-container, #print-container * { visibility: visible; }
                                #print-container {
                                    position: absolute;
                                    left: 0;
                                    top: 0;
                                    width: 100%;
                                }
                                
                                /* Reglas para evitar que las tablas se corten */
                                #print-container table {
                                    font-size: 8px !important;
                                    page-break-inside: auto !important;
                                    border-collapse: collapse !important;
                                    width: 100% !important;
                                }
                                
                                #print-container tr {
                                    page-break-inside: avoid !important;
                                    page-break-after: auto !important;
                                }
                                
                                #print-container td, #print-container th {
                                    page-break-inside: avoid !important;
                                    padding: 2px !important;
                                }
                                
                                #print-container thead {
                                    display: table-header-group !important;
                                }
                                
                                #print-container tfoot {
                                    display: table-footer-group !important;
                                }
                                
                                /* Reducir todos los márgenes y espaciados */
                                #print-container * {
                                    margin: 0 !important;
                                    padding: 0 !important;
                                }
                                
                                #print-container .border {
                                    border-width: 1px !important;
                                }
                                
                                #print-container h1, 
                                #print-container h2, 
                                #print-container h3 {
                                    font-size: 10px !important;
                                    margin-top: 2px !important;
                                    margin-bottom: 2px !important;
                                    padding: 2px !important;
                                }
                                
                                #print-container p {
                                    font-size: 8px !important;
                                    margin-bottom: 2px !important;
                                }
                                
                                #print-container .p-4, 
                                #print-container .p-6, 
                                #print-container .px-4, 
                                #print-container .py-2,
                                #print-container .px-6,
                                #print-container .py-4 {
                                    padding: 2px !important;
                                }
                                
                                #print-container .mb-4,
                                #print-container .mb-3 {
                                    margin-bottom: 3px !important;
                                }
                                
                                #print-container .text-sm,
                                #print-container .text-xs {
                                    font-size: 8px !important;
                                }
                                
                                #print-container .text-lg, 
                                #print-container .text-xl {
                                    font-size: 10px !important;
                                }
                                
                                #print-container .gap-4 {
                                    gap: 3px !important;
                                }
                                
                                /* Asegurar que las tablas de control de horas y valorización no se corten */
                                #print-container .overflow-x-auto {
                                    overflow: visible !important;
                                }
                                
                                /* Controlar el flujo de elementos en la página */
                                #print-container .border.border-gray-300.rounded-lg.overflow-hidden.mb-4 {
                                    page-break-inside: avoid !important;
                                    margin-bottom: 5px !important;
                                }
                                
                                /* Estilos para los colores de fondo */
                                #print-container .bg-white {
                                    background-color: white !important;
                                }
                                
                                #print-container .bg-teal-600 {
                                    background-color: #0d9488 !important;
                                    color: white !important;
                                    -webkit-print-color-adjust: exact !important;
                                    print-color-adjust: exact !important;
                                }
                                
                                #print-container .bg-gray-100 {
                                    background-color: #f3f4f6 !important;
                                    -webkit-print-color-adjust: exact !important;
                                    print-color-adjust: exact !important;
                                }
                                
                                #print-container .bg-green-50 {
                                    background-color: #f0fdf4 !important;
                                    -webkit-print-color-adjust: exact !important;
                                    print-color-adjust: exact !important;
                                }
                                
                                /* Configuración de página */
                                @page {
                                    size: A4 portrait;
                                    margin: 0.5cm;
                                }
                                
                                /* Eliminar decoraciones innecesarias */
                                #print-container .rounded-lg,
                                #print-container .rounded {
                                    border-radius: 0 !important;
                                }
                                
                                #print-container .shadow-xl,
                                #print-container .shadow-md,
                                #print-container .shadow {
                                    box-shadow: none !important;
                                }
                                
                                /* Ajustes específicos para la sección de valorización */
                                #print-container .valorization-section table {
                                    font-size: 9px !important;
                                    font-weight: bold !important;
                                }
                                
                                /* Ajustes específicos para la sección de control de horas */
                                #print-container .control-horas-section table {
                                    font-size: 7px !important;
                                }
                                
                                #print-container .control-horas-section td,
                                #print-container .control-horas-section th {
                                    padding: 1px !important;
                                }
                                
                                /* Estilos adicionales para asegurar que la tabla de valorización se vea */
                                #print-container .valorization-section {
                                    page-break-before: auto !important;
                                    page-break-after: auto !important;
                                    page-break-inside: avoid !important;
                                }
                            }
                        </style>
                    `;
                    
                    // Crear contenedor para impresión
                    let printContainer = document.getElementById('print-container');
                    if (!printContainer) {
                        printContainer = document.createElement('div');
                        printContainer.id = 'print-container';
                        document.body.appendChild(printContainer);
                    }
                    
                    // Agregar contenido al contenedor
                    printContainer.innerHTML = '';
                    printContainer.appendChild(clonedContent);
                    
                    // Realizar optimizaciones adicionales en el DOM del contenedor de impresión
                    const allSections = printContainer.querySelectorAll('.border.border-gray-300.rounded-lg.overflow-hidden.mb-4');
                    allSections.forEach(section => {
                        // Asegurar que cada sección no se corte
                        section.style.pageBreakInside = 'avoid';
                        section.style.marginBottom = '5px';
                        
                        // Reducir padding en elementos internos
                        const sectionDivs = section.querySelectorAll('div');
                        sectionDivs.forEach(div => {
                            div.style.padding = '2px';
                        });
                    });
                    
                    // Optimizar tablas
                    const allTables = printContainer.querySelectorAll('table');
                    allTables.forEach(table => {
                        table.style.fontSize = '7px';
                        table.style.width = '100%';
                        table.style.pageBreakInside = 'auto';
                        
                        // Optimizar celdas
                        const cells = table.querySelectorAll('th, td');
                        cells.forEach(cell => {
                            cell.style.padding = '1px';
                            cell.style.pageBreakInside = 'avoid';
                        });
                        
                        // Asegurar que filas no se corten
                        const rows = table.querySelectorAll('tr');
                        rows.forEach(row => {
                            row.style.pageBreakInside = 'avoid';
                        });
                    });
                    
                    // Agregar estilos de impresión
                    document.head.insertAdjacentHTML('beforeend', printStyles);
                    
                    // Imprimir
                    setTimeout(() => {
                        window.print();
                        
                        // Restaurar después de imprimir
                        document.head.innerHTML = originalStyles;
                        printContainer.innerHTML = '';
                    }, 300);
                } else {
                    console.error('No se encontró el contenido del modal para imprimir');
                }
            });
        });
    </script>
</div> 