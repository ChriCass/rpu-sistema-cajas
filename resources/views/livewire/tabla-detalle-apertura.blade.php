<div>
    <div x-data="{ show: true }" x-show="show" x-transition class="my-3">
        @if (session()->has('message'))
            <x-alert title="¡Éxito!" positive padding="none">
                <x-slot name="slot">
                    {{ session('message') }} — <b>¡Revísalo!</b>
                    <button @click="show = false" class="ml-2 text-red-500 hover:text-red-700">Cerrar</button>
                </x-slot>
            </x-alert>
        @endif
    </div>
    
    <div x-data="{ show: true }" x-show="show" x-transition class="my-3">
        @if (session()->has('error'))
            <x-alert title="¡Error!" negative padding="small">
                <x-slot name="slot">
                    {{ session('error') }} — <b>¡Revísalo!</b>
                    <button @click="show = false" class="ml-2 text-red-500 hover:text-red-700">Cerrar</button>
                </x-slot>
            </x-alert>
        @endif
    </div>
    

    <div class="overflow-x-auto mt-5">
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th
                        class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        NumeroMov</th>
                    <th
                        class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        Familia</th>
                    <th
                        class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        SubFamilia</th>
                    <th
                        class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        Detalle</th>
                    <th
                        class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        DetalleProducto</th>
                    <th
                        class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        Entidad</th>
                    <th
                        class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        NumeroDocumento</th>
                    <th
                        class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        Monto</th>
                    <th
                        class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        Glosa</th>
                    <th
                        class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if ($movimientos)
                    @foreach ($movimientos as $movimiento)
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $movimiento->NumeroMovimiento }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $movimiento->Familia }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $movimiento->SubFamilia }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $movimiento->Detalle }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $movimiento->DetalleProducto }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $movimiento->Entidad }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $movimiento->NumeroDocumento }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $movimiento->Monto }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $movimiento->Glosa }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">
                                <div class="flex gap-3 flex-col">
                                    <div><x-button
                                            wire:click="editarMovimiento({{ $movimiento->Monto }}, '{{ $movimiento->NumeroMovimiento }}')"
                                            label="Editar" warning /></div>
                                    <div wire:ignore> @livewire('delete-apertura-modal', ['numMov' => $movimiento->NumeroMovimiento, 'aperturaId' => $aperturaId, 'familias' => $movimiento->Familia])</div>
                                </div>


                            </td>

                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="9" class="py-2 px-4 border-b border-gray-200">No hay movimientos</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
