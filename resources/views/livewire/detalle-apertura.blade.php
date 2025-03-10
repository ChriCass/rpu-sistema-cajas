<div class="p-4">
  <x-card title="VaucherDePago">
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="w-full">
            <x-select readonly wire:model="caja" label="Tipo:" placeholder="Selecciona..." 
                :options="$tipoCajas" option-label="descripcion" option-value="id" />
        </div>
        <div class="w-full">
            <x-select readonly wire:model="año" label="Año:" placeholder="Selecciona..." 
                :options="$años" option-label="year" option-value="key" />
        </div>
        <div class="w-full">
            <x-select readonly wire:model="mes" label="Meses:" placeholder="Selecciona..." 
                :options="$meses" option-label="descripcion" option-value="id" />
        </div>
        <div class="w-full">
            <x-maskable readonly wire:model="numero" label="N:" mask="###" />
        </div>
        <div class="w-full">
            <x-input readonly wire:model="moneda" label="Moneda:" />
        </div>
        <div class="w-full">
            <x-input readonly wire:model="fecha" label="Fecha:" without-time />
        </div>
    </div>
    

      <div class="flex justify-between flex-wrap gap-3 mt-4 -mx-2">
        <div>
            <x-button primary label="Ingreso" wire:click="$dispatch('mostrarComponente', { componente: 'ingreso' })" />
            <x-button primary label="Salida" wire:click="$dispatch('mostrarComponente', { componente: 'salida' })" />
            <x-button primary label="CXP" wire:click="$dispatch('mostrarComponente', { componente: 'cxp' })" />
            <x-button primary label="CXC" wire:click="$dispatch('mostrarComponente', { componente: 'cxc' })" />
            <x-button primary label="Aplicaciones" wire:click="$dispatch('mostrarComponente', { componente: 'aplicaciones' })" />
        </div>
        
          <div class="flex flex-wrap justify-start">
              <x-input wire:model="montoInicial" readonly label="Monto Inicial:" mask="currency" />
          </div>
      </div>

      @livewire('tabla-detalle-apertura', ['aperturaId' => $aperturaId, 'moneda' => $moneda])

      <div class="flex flex-wrap  justify-around mt-4 -mx-2">
          <div class="w-full sm:w-1/2 px-2 mb-2 sm:mb-0">
              <x-input wire:model="totalCalculado" readonly label="Total Calculado:" mask="currency" />
          </div>
          <div class="w-full sm:w-auto mt-5 px-2">
              <x-button wire:navigate label="Salir" outline secondary href="{{ route('movimientos') }}" />
          </div>
      </div>

  </x-card>
</div>
