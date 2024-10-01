<div>
  
        <div class="flex items-center space-x-4 mb-4">
            <!-- Campo Fecha -->
            <div>
                <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha:</label>
                <input 
                    type="date" 
                    id="fecha" 
                    name="fecha" 
                    wire:model.live="fecha" 
                    class="mt-1 block w-full pl-3 pr-3 py-2 border-gray-300 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm rounded-md" 
                    readonly 
                />
            </div>
            <!-- Campo Voucher -->
            <div>
                <x-input label="voucher" wire:model='aplicacionesId' readonly></x-input>
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
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Debe$</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Haber$</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detalles as $detalle)
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
                        <td class="px-4 py-2 border-b border-gray-300">{{ $detalle['montododebe'] }}</td>
                        <td class="px-4 py-2 border-b border-gray-300">{{ $detalle['montodohaber'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
   <!-- Botones -->
   <div class="flex justify-end mt-4 space-x-2">
    <x-button label="Cancelar" outline secondary wire:navigate href="{{ route('aplicaciones') }}" />


     
         @livewire('delete-aplicaciones-modal', ['detalles'=> $detalles])
 
</div>
   
</div>
