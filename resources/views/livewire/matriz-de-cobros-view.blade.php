<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Matriz de Cobros
        </h2>
    </x-slot>
    <div class="container mx-auto p-4">
        <!-- Botones superiores -->

        <x-card class="p-4">
            <div class="flex justify-between items-center mb-4">
                <div class="space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" class="form-radio text-teal-600" wire:model.live="status" name="status"
                            value="pendiente">
                        <span class="ml-2">Pendiente</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" class="form-radio text-teal-600" wire:model.live="status" name="status"
                            value="pagado">
                        <span class="ml-2">Pagado</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" class="form-radio text-teal-600" wire:model.live="status" name="status"
                            value="todo">
                        <span class="ml-2">Todo</span>
                    </label>
                </div>
                <div class="flex space-x-4">
                    <div class="flex flex-col items-center">
                        <input 
                            type="button" 
                            wire:click="procesar" 
                            class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 
                            {{ !empty($movimientos) ? 'opacity-50 cursor-not-allowed' : '' }}" 
                            value="Procesar"
                            {{ !empty($movimientos) ? 'disabled' : '' }}
                        >
                    </div>
                    

                </div>
                <button href="{{ route('movimientos') }}" wire:navigate
                    class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
                    Movimientos
                </button>
            </div>
    </div>

    <!-- Tabla con cabeceras -->
    <div class="overflow-x-auto">
        <div class="flex flex-wrap gap-4 justify-center p-4 rounded-md">
            <div>
                <x-maskable mask="########" wire:model.live="filters.id" placeholder="ID" class="w-full"
                    :disabled="empty($movimientos) && !$hasFiltered" />
            </div>
            <div>
                <x-input  wire:model.live="filters.tdoc" placeholder="T. DOC" class="w-full"
                    :disabled="empty($movimientos) && !$hasFiltered" />
            </div>
            <div>
                <x-input  wire:model.live="filters.Doc" placeholder="DOC" class="w-full"
                    :disabled="empty($movimientos) && !$hasFiltered" />
            </div>
            <div>
                <x-maskable mask="###########" wire:model.live="filters.id_entidades" placeholder="ID ENTIDAD" class="w-full"
                    :disabled="empty($movimientos) && !$hasFiltered" />
            </div>
            <div>
                <x-input  wire:model.live="filters.Deski" placeholder="ENTIDAD" class="w-full"
                    :disabled="empty($movimientos) && !$hasFiltered" />
            </div>
            <div>
                <x-input wire:model.live="filters.name" placeholder="USUARIO" class="w-full"
                    :disabled="empty($movimientos) && !$hasFiltered" />
            </div>
            <div>
                <x-input wire:model.live="filters.id_t04tipmon" placeholder="MONEDA" class="w-full"
                    :disabled="empty($movimientos) && !$hasFiltered" />
            </div>
            <div>
                <x-input  wire:model.live="filters.estadoMon" placeholder="ESTADO" class="w-full"
                    :disabled="empty($movimientos) && !$hasFiltered" />
            </div>
        </div>


        @if (!empty($movimientos))
            <table class="min-w-full bg-white border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 border-b">ID</th>
                        <th class="px-4 py-2 border-b">T. DOC</th>
                        <th class="px-4 py-2 border-b">DOC</th>
                        <th class="px-4 py-2 border-b">FECHA EMI</th>
                        <th class="px-4 py-2 border-b">ID ENTIDAD</th>
                        <th class="px-4 py-2 border-b">ENTIDAD</th>
                        <th class="px-4 py-2 border-b">USUARIO</th>
                        <th class="px-4 py-2 border-b">MONEDA</th>
                        <th class="px-4 py-2 border-b">FACTURADO</th>
                        <th class="px-4 py-2 border-b">MONTO</th>
                        <th class="px-4 py-2 border-b">MONTO K</th>
                        <th class="px-4 py-2 border-b">ESTADO</th>
                        <th class="px-4 py-2 border-b">DIAS</th>
                        <th class="px-4 py-2 border-b">FEC VEN</th>
                        <th class="px-4 py-2 border-b">DETRACCION</th>
                        <th class="px-4 py-2 border-b">ESTADO DETR</th>
                        <th class="px-4 py-2 border-b">VEN DETRACCION</th>
                        <th class="px-4 py-2 border-b">OBSERVACION</th>
                        <th class="px-4 py-2 border-b">PAGOS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($movimientos as $movimiento)
                        <tr>
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
                            <td class="px-4 py-2 border-b">{{ $movimiento->estadoMon }}</td>
                            <td class="px-4 py-2 border-b">{{ $movimiento->dias ?? '-' }}</td>
                            <td class="px-4 py-2 border-b">{{ $movimiento->fechaVen }}</td>
                            <td class="px-4 py-2 border-b">{{ $movimiento->detraccion ?? '-' }}</td>
                            <td class="px-4 py-2 border-b">{{ $movimiento->EstadoDetr ?? '-' }}</td>
                            <td class="px-4 py-2 border-b">{{ $movimiento->VenDetraccion ?? '-' }}</td>
                            <td class="px-4 py-2 border-b">{{ $movimiento->observaciones ?? '-' }}</td>
                            <td class="px-4 py-2 border-b">{{ $movimiento->Num ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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

    </x-card>
</div>

</div>
