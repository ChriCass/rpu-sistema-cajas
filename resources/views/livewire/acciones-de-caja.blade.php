<div>
    {{-- Because she competes with no one, no one can compete with her. --}}
    <div class="w-full p-4 mt-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700">
        <p class="text-sm md:text-base font-semibold flex items-center">
            ⚠️ Los movimientos de caja se generaran en funcion al dia del mes.
        </p>
      </div>
      <div class="container mx-auto p-4">
          <div class="w-full max-w-2xl">
              <div class="flex flex-wrap md:flex-nowrap justify-start items-end gap-4">
                <div class="w-full md:w-1/3">
                    <x-select label="Caja" placeholder="Selecc." wire:model="caja" :options="$tipodecaja" option-label="descripcion" option-value="id" class="w-full" />
                </div>
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
                        <th class="border p-2">Tipo de Caja</th>
                        <th class="border p-2">Numero</th>
                        <th class="border p-2">Año</th>
                        <th class="border p-2">Mes</th>
                        <th class="border p-2">Fecha</th>
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
                            <td class="border p-2">{{ $documento['tipo'] }}</td>
                            <td class="border p-2">{{ $documento['numero'] }}</td>
                            <td class="border p-2">{{ $documento['anno'] }}</td>
                            <td class="border p-2">{{ $documento['mes'] }}</td>
                            <td class="border p-2">{{ $documento['fecha'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4 flex justify-end">
            @if ($mov == '1')
                <x-button info label="Generar" wire:click="DeleteModal" />
            @else
                <x-button negative label="Eliminar" wire:click="DeleteModal" />
            @endif
        </div>        
        @endif
    @else
        <div class="w-full bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-md mb-4">
            <p class="text-sm md:text-base font-semibold flex items-center">
                ✅ No existen registros
            </p>
        </div>
    @endif
    @livewire('delete-modal-acciones-de-caja')
</div>
