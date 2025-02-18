<div class="p-5">
    <!-- Botón para mostrar u ocultar contenido -->
    <div class="mb-4 flex justify-center">
        @if ($showContent)
            <!-- Si el contenido está visible, mostrar botón "Cancelar" en warning -->
            <x-button wire:click="toggleContent" label="Cancelar" warning class="py-2 px-4" />
        @else
            <!-- Si el contenido está oculto, mostrar botón "Mostrar Contenido" en primary -->
            <x-button wire:click="toggleContent" label="Nuevo" primary class="py-2 px-4" />
        @endif
    </div>

    <!-- Contenido que se mostrará u ocultará -->
    @if ($showContent)
        <x-card>
            <div>
                {{-- Alerta de éxito --}}
                @if(session()->has('message'))
                    <x-alert title="¡Transacción Exitosa!" class="mb-3" positive padding="none">
                        <x-slot name="slot">
                            {{ session('message') }} — <b>¡verifícalo!</b>
                        </x-slot>
                    </x-alert>
                @endif
            
                {{-- Alerta de error --}}
                @if(session()->has('error'))
                    <x-alert title="¡Error en la transacción!" class="mb-3"  negative padding="small">
                        <x-slot name="slot">
                            {{ session('error') }} — <b>¡revisa los datos!</b>
                        </x-slot>
                    </x-alert>
                @endif
            
                {{-- Alerta de advertencia --}}
                @if(session()->has('warning'))
                    <x-alert title="¡Advertencia!" class="mb-3"  warning padding="medium">
                        <x-slot name="slot">
                            {{ session('warning') }} 
                        </x-slot>
                    </x-alert>
                @endif
            </div>
            
            <!-- Filtros de Fecha y Moneda -->
            <div class="flex justify-between space-x-4 mb-4">
                <div class="flex gap-3">
                    <div class="flex flex-col">
                        <label for="fecha" class="text-sm font-medium text-gray-700 mb-1">Fecha:</label>
                        <input 
                        type="date" wire:model='fecha'
                        id="appointment_date" 
                        class="block w-full pl-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm text-gray-600" 
                        placeholder="Appointment Date"
                    />
                    </div>
                    <div class="flex flex-col">
                        <x-select
                        label="Moneda"
                        placeholder="Seleccione una moneda"
                        :options="$monedas"
                        option-label="id"
                        option-value="id"
                        wire:model.live='moneda'
                    />
                    </div>
                </div>

                <!-- Campo Moneda -->

                <!-- Botón Nuevo -->
                <div class="ml-auto">
                    <x-button label="Selecc." wire:click="largo" primary />
                   @livewire('vaucher-aplicaciones-pendientes-modal', ['fecha'=> $fecha, 'moneda' => $moneda])
                </div>
            </div>

            <!-- Botones de Acción (distribuidos horizontalmente) -->
            <div class="flex gap-3 justify-between mb-4">
                <div>
                    @if($balance < 0)
                        <x-alert class="font-bold"
                            title="Balance: {{ $balance }}" 
                            negative 
                        />
                    @else
                        <x-alert 
                            title="Balance: {{ $balance }}" 
                            info 
                        />
                    @endif
                </div>
                <!--   <div class="flex gap-3 mt-5">
                 <div> <x-button label="Registro CXC" primary class="py-2 px-4" /></div>
                    <div><x-button label="Registro CXP" secondary class="py-2 px-4" /></div>
                    <div> <x-button label="Ingreso" secondary class="py-2 px-4" /></div>
                    <div><x-button label="Gasto" secondary class="py-2 px-4" /></div>  
                </div>  -->

               
            
        <!-- Botones de acción -->
        <div class="flex space-x-2 justify-between mb-4">
           
            
            <!-- Input de búsqueda -->
            <div class="flex gap-3">
                 
                <x-input label="debe" readonly wire:model='TotalDebe' />
                <x-input label="haber" readonly wire:model='TotalHaber'  />
          
            </div>
        </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300">
                    <thead class="bg-gray-50">
                        @if (empty($contenedor))
                        <tr>
                            <td colspan="10" class="px-4 py-2 border-b border-gray-300 text-center text-sm text-gray-700">
                                No se encontraron registros. Prueba añadiendo un registro usando el botón "Nuevo".
                            </td>
                        </tr>
                        @else
                        <tr>
                            <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Id</th>
                            <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">T.doc</th>
                            <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Entidades</th>
                            <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Descripcion</th>
                            <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Num</th>
                            <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Moneda</th>
                            <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Cuenta</th>
                            <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Monto</th>
                            <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Debe</th>
                            <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Haber</th>
                            <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Acciones</th>
                        </tr>
                        @endif
                    </thead>
                    <tbody>
                        @if (!empty($contenedor))
                        @foreach ($contenedor as $index => $detalle)
                        <tr>
                            <td class="px-4 py-2 border-b border-gray-300">{{ $detalle['id'] }}</td>
                            <td class="px-4 py-2 border-b border-gray-300">{{ $detalle['tdoc'] }}</td>
                            <td class="px-4 py-2 border-b border-gray-300">{{ $detalle['id_entidades'] }}</td>
                            <td class="px-4 py-2 border-b border-gray-300">{{ $detalle['entidades'] }}</td>
                            <td class="px-4 py-2 border-b border-gray-300">{{ $detalle['num'] }}</td>
                            <td class="px-4 py-2 border-b border-gray-300">{{ $detalle['id_t04tipmon'] }}</td>
                            <td class="px-4 py-2 border-b border-gray-300">{{ $detalle['cuenta'] }}</td>
                            
                            <!-- Columna Monto -->
                            <td class="px-4 py-2 border-b border-gray-300">{{ $detalle['monto'] }}</td>
            
                            <!-- Columnas Debe y Haber -->
                            <td class="px-4 py-2 border-b border-gray-300">{{ $detalle['montodebe'] }}</td>
                            <td class="px-4 py-2 border-b border-gray-300">{{ $detalle['montohaber'] }}</td>
            
                            <!-- Columna Acciones: muestra el input y los botones de edición -->
                            <td class="px-4 py-2 border-b border-gray-300">
                                @if ($editingIndex === $index)
                                    <!-- Input de edición del monto -->
                                    <div class="relative mb-2">
                                        <x-input   wire:model.live="editingMonto"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1').slice(0, 10)"/>
                                        @if ($warningMessage[$index] ?? false)
                                            <span class="text-red-500 text-sm block mt-1">{{ $warningMessage[$index] }}</span>
                                        @endif
                                    </div>
                                    <!-- Botones Guardar y Cancelar -->
                                    <div class="flex space-x-2">
                                        <x-button label="Guardar" wire:click="saveMonto({{ $index }})" />
                                        <x-button label="Cancelar" wire:click="cancelEdit" outline secondary />
                                    </div>
                                @else
                                    <!-- Botón para editar el monto -->
                                    <x-button label="Editar Monto" wire:click="editMonto({{ $index }})" />
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            
            
            
            <!-- Botones Cancelar y Aceptar, alineados a la derecha -->
            <div class="flex justify-end mt-4 space-x-2">
                
                <x-button wire:click='submit' label="Aceptar" />
            </div>
        </x-card>
    @endif
</div>
