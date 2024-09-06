<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Vaucher de pago
        </h2>
    </x-slot>

    <div class="p-4">
        <x-card title="Vaucher De Pago">
            <div class="flex flex-wrap -mx-2 mt-4">
                <div class="w-full md:w-4/12 px-2">
                    <x-input readonly label="Fecha:" wire:model="fechaApertura" />
                </div>
                <div class="w-full md:w-4/12 px-2">
                    <x-input readonly label="Moneda:" wire:model="moneda" />
                </div>
                <div class="w-full md:w-4/12 px-2 flex items-center justify-end">
                    @livewire('cuadro-de-pendientes-modal', ['aperturaId' => $aperturaId])
                </div>

            </div>

            <div class="flex flex-wrap -mx-2 mt-4">
                <div class="w-full flex justify-end gap-5 px-2">
                    <div>
                        @if ($balance < 0)
                            <x-alert title="Balance: {{ $balance }}" negative />
                        @else
                            <x-alert title="Balance: {{ $balance }}" positive />
                        @endif
                    </div>
                    <div class="space-x-2 mt-5">
                        <!-- Aquí cambiamos wire:navigate por dispatch -->
                        <x-button 
                            label="Registro CXP" 
                            wire:click="$dispatch('mostrarComponente', {componente: 'registroCXP'})" 
                        />

                        <x-button 
                            label="Ingreso" 
                            wire:click="$dispatch('mostrarComponente', {componente: 'ingreso'})" 
                        />

                        <x-button 
                            label="Gasto" 
                            wire:click="$dispatch('mostrarComponente', {componente: 'gasto'})" 
                        />
                    </div>
                    <!-- Mostrar "Haber" cuando hay datos en el contenedor -->
                    @if (!empty($contenedor))
                        <div class="space-x-2 flex items-center">
                                 <!-- Mostrar el campo "Debe" solo si hay una fila seleccionada -->
                                 <div class="{{ $selectedIndex !== null ? 'block' : 'hidden' }}">
                                    <x-input readonly label="Debe" wire:model="debe" />
                                </div>
                            <!-- Mostrar el campo "Haber" siempre -->
                            <div class="block">
                                <x-input readonly label="Haber" wire:model="haber" />
                            </div>
                       
                        </div>
                    @endif
                </div>
            </div>

            <div class="overflow-x-auto mt-5">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th
                                class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Id</th>
                            <th
                                class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                T.doc</th>
                            <th
                                class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Entidades</th>
                            <th
                                class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Descripción</th>
                            <th
                                class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Num</th>
                            <th
                                class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Moneda</th>
                            <th
                                class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Cuenta</th>
                            <th
                                class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Monto</th>
                            <th
                                class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Debe</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contenedor as $index => $item)
                            <tr wire:click="selectDebe({{ $index }})"
                                class="{{ $selectedIndex === $index ? 'bg-teal-500 text-white' : '' }}">
                                <td class="py-2 px-4 border-b border-gray-200">{{ $item['id_documentos'] }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $item['tdoc'] }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $item['id_entidades'] }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $item['RZ'] }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $item['Num'] }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $item['Mon'] }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $item['Descripcion'] }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $item['monto'] }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $item['monto'] }}</td> <!-- Debe -->
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

            <div class="flex justify-end mt-4 space-x-2">
                <x-button label="Cancelar" wire:click="$dispatch('mostrarComponente', {componente: 'cancelar'})" outline secondary />
                <x-button label="Aceptar" primary />
            </div>
        </x-card>
    </div>
</div>
