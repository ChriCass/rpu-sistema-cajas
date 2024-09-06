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
            <div class="space-x-2">
              <x-button 
              label="Registro CXC" 
              wire:click="$dispatch('mostrarComponente', { componente: 'registroCXC' })" 
          />
          
          <x-button 
              label="Ingreso" 
              wire:click="$dispatch('mostrarComponente', { componente: 'ingreso' })" 
          />
          
          <x-button 
              label="Gasto" 
              wire:click="$dispatch('mostrarComponente', { componente: 'gasto' })" 
          />
          
            </div>

            @if (!empty($contenedor))
            <div class="space-x-2 flex items-center">
                <!-- Mostrar el campo "Haber" solo si hay una fila seleccionada -->
                <div class="{{ $selectedIndex !== null ? 'block' : 'hidden' }}">
                    <x-input readonly label="Haber" wire:model="haber" />
                </div>
                <!-- Mostrar el campo "Debe" siempre -->
                <div class="block">
                    <x-input readonly label="Debe" wire:model="debe" />
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
                <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Debe</th>
                <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Haber</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($contenedor as $index => $item)
                  <tr wire:click="selectDebe({{ $index }})" class="{{ $selectedIndex === $index ? 'bg-teal-500 text-white' : '' }}">
                      <td class="py-2 px-4 border-b border-gray-200">
                          {{ is_object($item) ? $item->id_documentos : $item['id_documentos'] }}
                      </td>
                      <td class="py-2 px-4 border-b border-gray-200">
                          {{ is_object($item) ? $item->tdoc : $item['tdoc'] }}
                      </td>
                      <td class="py-2 px-4 border-b border-gray-200">
                          {{ is_object($item) ? $item->id_entidades : $item['id_entidades'] }}
                      </td>
                      <td class="py-2 px-4 border-b border-gray-200">
                          {{ is_object($item) ? $item->RZ : $item['RZ'] }}
                      </td>
                      <td class="py-2 px-4 border-b border-gray-200">
                          {{ is_object($item) ? $item->Num : $item['Num'] }}
                      </td>
                      <td class="py-2 px-4 border-b border-gray-200">
                          {{ is_object($item) ? $item->Mon : $item['Mon'] }}
                      </td>
                      <td class="py-2 px-4 border-b border-gray-200">
                          {{ is_object($item) ? $item->Descripcion : $item['Descripcion'] }}
                      </td>
                      <td class="py-2 px-4 border-b border-gray-200">
                          {{ is_object($item) ? $item->monto : $item['monto'] }}
                      </td>
                      <td class="py-2 px-4 border-b border-gray-200">
                          {{ is_object($item) ? $item->monto : $item['monto'] }}
                      </td>
                      <td class="py-2 px-4 border-b border-gray-200">
                          {{ is_object($item) ? $item->monto : $item['monto'] }}
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
