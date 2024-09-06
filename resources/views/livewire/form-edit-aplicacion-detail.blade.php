<div>

    
    <div class="p-4 bg-white rounded shadow-md">
        <div>
            {{-- Alerta de éxito --}}
            @if(session()->has('message'))
                <x-alert title="¡Transacción Exitosa!" positive padding="none">
                    <x-slot name="slot">
                        {{ session('message') }} — <b>¡verifícalo!</b>
                    </x-slot>
                </x-alert>
            @endif
        
            {{-- Alerta de error --}}
            @if(session()->has('error'))
                <x-alert title="¡Error en la transacción!" negative padding="small">
                    <x-slot name="slot">
                        {{ session('error') }} — <b>¡revisa los datos!</b>
                    </x-slot>
                </x-alert>
            @endif
        
            {{-- Alerta de advertencia --}}
            @if(session()->has('warning'))
                <x-alert title="¡Advertencia!" warning padding="medium">
                    <x-slot name="slot">
                        {{ session('warning') }} 
                    </x-slot>
                </x-alert>
            @endif
        </div>
        
        
        <!-- Filtros de Fecha y Moneda -->
        <div class="flex items-center justify-between space-x-4 mb-4">
            <!-- Campo Fecha -->
            <div>
                <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha:</label>
                <input 
                    type="date" 
                    id="fecha" 
                    name="fecha" 
                    wire:model.live="fecha" 
                    class="mt-1 block w-full pl-3 pr-3 py-2 border-gray-300 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm rounded-md" 
             
                />
                    <!-- Campo Moneda -->
            <div>
                <x-select
                label="Moneda"
                placeholder="Selecciona una moneda"
                :options="$monedas"
                option-label="id"
                option-value="id"
                wire:model="moneda"
            />
                 
            </div>
            </div>
        

            <!-- Botón Nuevo -->
            <div class="ml-auto">
               @livewire('aplicacion-detail-modal', [ 'detalles' => $detalles, 'fecha' => $fecha,'aplicacionesId' => $aplicacionesId, 'moneda' => $moneda])
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="flex space-x-2 justify-between mb-4">
            <div>
                <x-button label="Ingreso" primary />
                <x-button label="Gasto" secondary />
            </div>
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
            
            <!-- Input de búsqueda -->
            <div class="flex gap-3">
                 
                <x-input label="debe" readonly wire:model='TotalDebe' />
                <x-input label="haber" readonly wire:model='TotalHaber'  />
          
            </div>
        </div>

        <!-- Tabla -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead class="bg-gray-50">
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
                    </tr>
                </thead>
                <tbody>
                    @foreach ($contenedor as $detalle)
                    <tr>
                        <td class="px-4 py-2 border-b border-gray-300">{{ $detalle['id'] }}</td>
                        <td class="px-4 py-2 border-b border-gray-300">{{ $detalle['tdoc'] }}</td>
                        <td class="px-4 py-2 border-b border-gray-300">{{ $detalle['id_entidades'] }}</td>
                        <td class="px-4 py-2 border-b border-gray-300">{{ $detalle['entidades'] }}</td>
                        <td class="px-4 py-2 border-b border-gray-300">{{ $detalle['num'] }}</td>
                        <td class="px-4 py-2 border-b border-gray-300">{{ $detalle['id_t04tipmon'] }}</td>
                        <td class="px-4 py-2 border-b border-gray-300">{{ $detalle['cuenta'] }}</td>
                        <td class="px-4 py-2 border-b border-gray-300">{{ $detalle['monto'] }}</td>
                        <td class="px-4 py-2 border-b border-gray-300">{{ $detalle['montodebe'] }}</td>
                        <td class="px-4 py-2 border-b border-gray-300">{{ $detalle['montohaber'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
                
            </table>
        </div>

        <!-- Totales y Botones de acción -->
        <div class="flex justify-end items-center mt-4">
       
            <div class="flex space-x-2">
                <x-button label="Cancelar" outline secondary />
                <x-button label="Aceptar" wire:click='submit' class="bg-teal-500 hover:bg-teal-600 text-white" />
            </div>
        </div>
    </div>
</div>
