<div>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Vaucher de pago ventas
      </h2>
  </x-slot>

  <div class="p-4">
      <x-card title="VaucherDePago">
        <div class="flex flex-wrap -mx-2 mt-4">
          <div class="w-full md:w-4/12 px-2">
            <x-input readonly label="Fecha:" wire:model="fechaApertura" />
          </div>
          <div class="w-full md:w-4/12 px-2">
            <x-input readonly label="Moneda:" wire:model="moneda" />
        </div>
          <div class="w-full md:w-4/12 px-2 flex items-center justify-end">
            @livewire('cuadro-de-pendientes-ventas-modal', ['aperturaId' => $aperturaId])
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

            @if (!empty($contenedor))
            <div class="space-x-2 flex items-center">
              <div class="p-2 border-2 border-teal-500 rounded-lg bg-white shadow-sm">
                <span class="text-lg font-semibold text-gray-700">
                    {{ $tipoCaja->descripcion }}
                </span>
            </div>
                <div class="block">
                    <x-input readonly   wire:model.live="debe" />
                </div>
            </div>
        @endif
        

          </div>
        </div>
        <div class="overflow-x-auto mt-5">
          <table class="min-w-full bg-white">
            <thead>
              <tr>
                <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Id</th>
                <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">T.doc</th>
                <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Entidades</th>
                <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Descripcion</th>
                <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Num</th>
                <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Moneda</th>
                <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Cuenta</th>
                <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Monto</th>
                <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Acciones</th>
            </tr>
            </thead>
            <tbody>
              @foreach ($contenedor as $index => $item)
                  <tr  >
                      <td class="py-2 px-4 border-b border-gray-200">{{ $item['id_documentos'] }}</td>
                      <td class="py-2 px-4 border-b border-gray-200">{{ $item['tdoc'] }}</td>
                      <td class="py-2 px-4 border-b border-gray-200">{{ $item['id_entidades'] }}</td>
                      <td class="py-2 px-4 border-b border-gray-200">{{ $item['RZ'] }}</td>
                      <td class="py-2 px-4 border-b border-gray-200">{{ $item['Num'] }}</td>
                      <td class="py-2 px-4 border-b border-gray-200">{{ $item['Mon'] }}</td>
                      <td class="py-2 px-4 border-b border-gray-200">{{ $item['Descripcion'] }}</td>
                      
                      <!-- Si estamos en modo edición, mostrar input -->
                      <td class="py-2 px-4 border-b border-gray-200">
                          @if ($editingIndex === $index)
                              <div class="relative">
                                <x-input   oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1').slice(0, 10)" wire:model.live="editingMonto"  />
                                  <!-- Mostrar advertencia debajo del input si hay mensaje -->
                                  @if ($warningMessage[$index] ?? false)
                                      <span class="text-red-500 text-sm block mt-1">
                                          {{ $warningMessage[$index] }}
                                      </span>
                                  @endif
                              </div>
                          @else
                              {{ $item['monto'] }}
                          @endif
                      </td>
  
                      <!-- Columna de acciones, mostrar los botones Guardar y Cancelar solo cuando se está editando -->
                      <td class="py-2 px-4 border-b border-gray-200">
                          @if ($editingIndex === $index)
                              <div class="flex space-x-2">
                                  <x-button label="Guardar" wire:click="saveMonto({{ $index }})" />
                                  <x-button label="Cancelar" wire:click="cancelEdit" outline secondary />
                              </div>
                          @else
                              <x-button label="Editar Monto" wire:click="editMonto({{ $index }})" />
                          @endif
                      </td>
                  </tr>
              @endforeach
          </tbody>
          
          </table>
        </div>
        <div class="flex justify-end mt-4 space-x-2">
          <x-button label="Cancelar" wire:navigate outline secondary href="{{ route('apertura.edit', ['aperturaId' => $aperturaId]) }}" />
          <x-button label="Aceptar" primary />
        </div>
      </x-card>
    </div>
</div>
