<div>
    {{-- Because she competes with no one, no one can compete with her. --}}
    <div class="w-full p-4 mt-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700">
        <p class="text-sm md:text-base font-semibold flex items-center">
            ⚠️ Solo se puede borrar el documento si no tiene movimientos en caja.
        </p>
      </div>
      <div class="container mx-auto p-4">
          <div class="w-full max-w-2xl">
              <div class="flex flex-wrap md:flex-nowrap justify-start items-end gap-4">
                <div class="w-full md:w-1/3">
                    <x-select label="Año" placeholder="Selecc." wire:model="año" :options="$años" class="w-full" />
                </div>
                  <div class="w-full md:w-1/3">
                      <x-select label="Mes" placeholder="Selecc." wire:model="mes" :options="$meses"
                          option-label="descripcion" option-value="id" class="w-full" />
                  </div>
                  <div class="w-full md:w-1/3">
                    <x-select label="Tipo de Movimiento" placeholder="Selecc." wire:model="mov" :options="$tipmov"
                        option-label="descripcion" option-value="id" class="w-full" />
                </div>
                  <div class="w-full md:w-1/6">
                      <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded w-full" wire:click="buscar">
                          Buscar
                      </button>
                  </div>
              </div>
          </div>
      </div>
      @if (session()->has('error'))
        <div class="w-full bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-md mb-4">
            <p class="text-sm md:text-base font-semibold flex items-center">
                ⚠️ {{ session('error') }}
            </p>
        </div>
    @endif

    @if (session()->has('success'))
        <div class="w-full bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-md mb-4">
            <p class="text-sm md:text-base font-semibold flex items-center">
                ✅ {{ session('success') }}
            </p>
        </div>
    @endif
    
    @if ($conteo <> 'No')
        @if (!empty($documentos))
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2">Acciones</th>
                        <th class="border p-2">ID Documento</th>
                        <th class="border p-2">Fecha Emisión</th>
                        <th class="border p-2">ID Entidad</th>
                        <th class="border p-2">Descripción</th>
                        <th class="border p-2">Serie</th>
                        <th class="border p-2">Número</th>
                        <th class="border p-2">Precio</th>
                        <th class="border p-2">Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($documentos as $index => $documento)
                        <tr>
                            <td class="border p-2 text-center">
                                <button 
                                    x-data="{ estado: @entangle('documentos.' . $index . '.seleccionado') }"
                                    @click="$wire.toggleEstado({{ $index }})"
                                    :class="estado ? 'bg-teal-500 hover:bg-teal-700' : 'bg-red-500 hover:bg-red-600'"
                                    class="text-white font-bold py-1 px-3 rounded transition">
                                    <span x-text="estado ? 'Seleccionar' : 'Borrar'"></span>
                                </button>
                            </td>
                            <td class="border p-2">{{ $documento['id_documentos'] }}</td>
                            <td class="border p-2">
                                {{ \Carbon\Carbon::parse($documento['fechaEmi'])->format('d/m/Y') }}
                            </td>
                            <td class="border p-2">{{ $documento['id_entidades'] }}</td>
                            <td class="border p-2">{{ $documento['descripcion'] }}</td>
                            <td class="border p-2">{{ $documento['serie'] }}</td>
                            <td class="border p-2">{{ $documento['numero'] }}</td>
                            <td class="border p-2">S/. {{ number_format($documento['precio'], 2) }}</td>
                            <td class="border p-2">{{ $documento['observaciones'] }}</td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4 flex justify-end">
            <x-button negative label="Eliminar" wire:click="DeleteModal" />
            <!-- Modal de confirmación de eliminación -->
            <x-modal name="persistentModal" wire:model="openModal" persistent>
                <x-card>
                    <!-- Alerta de advertencia -->
                    <x-alert title="¡Advertencia Importante!" negative padding="small">
                        <x-slot name="slot">
                            <p class="text-red-600 font-semibold">Estás a punto de <b>Varios Documentos</b>.</p>
                            <p>Esta acción es <b>permanente</b> y no podrás deshacerla.</p>
                            <p class="text-red-600 mt-3">¿Estás seguro de que deseas continuar?</p>
                        </x-slot>
                    </x-alert>
                    
                    <!-- Footer con los botones de Cancelar y Eliminar -->
                    <x-slot name="footer" class="flex justify-end gap-x-4">
                        <x-button flat negative label="Cancelar" wire:click="$set('openModal', false)" />
                        <x-button negative label="Eliminar" wire:click="BorrarDocumentos" />
                    </x-slot>
                </x-card>
            </x-modal>
        </div>        
        @endif
    @else
        <div class="w-full bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-md mb-4">
            <p class="text-sm md:text-base font-semibold flex items-center">
                ✅ No existen registros
            </p>
        </div>
    @endif

</div>
