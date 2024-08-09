<div class="p-4">
  <x-card title="VaucherDePago">
      <div class="flex flex-wrap -mx-2">
          <div class="w-full sm:w-3/12 px-2">
              <x-select readonly wire:model="caja" label="Tipo:" placeholder="Selecciona..." :options="$tipoCajas"
                  option-label="descripcion" option-value="id" />
          </div>
          <div class="w-full sm:w-3/12 px-2">
              <x-select readonly wire:model="año" label="Año:" placeholder="Selecciona..." :options="$años"
                  option-label="year" option-value="key" />
          </div>
          <div class="w-full sm:w-3/12 px-2">
              <x-select readonly wire:model="mes" label="Meses:" placeholder="Selecciona..." :options="$meses"
                  option-label="descripcion" option-value="id" />
          </div>
          <div class="w-full sm:w-1/12 px-2">
              <x-maskable readonly wire:model="numero" label="N:" mask="#" />
          </div>
          <div class="w-full sm:w-2/12 px-2">
              <x-input readonly wire:model="fecha" label="Fecha:"   without-time
          />
          </div>
      </div>

      <div class="flex justify-between flex-wrap gap-3 mt-4 -mx-2">
          <div>
              <x-button primary label="Ingreso" wire:navigate
                  href="{{ route('apertura.edit.registodocumentosingreso', ['aperturaId' => $aperturaId]) }}" />
              <x-button primary label="Salida" wire:navigate
                  href="{{ route('apertura.edit.registodocumentosegreso', ['aperturaId' => $aperturaId]) }}" />
              <x-button primary label="CXP" wire:navigate
                  href="{{ route('apertura.edit.vaucherdepagos', ['aperturaId' => $aperturaId]) }}" />
              <x-button primary label="CXC" wire:navigate
                  href="{{ route('apertura.edit.vaucherdepagosventas', ['aperturaId' => $aperturaId]) }}" />
              <x-button primary label="Aplicaciones" wire:navigate
                  href="{{ route('apertura.edit.cuadroaplicaciones', ['aperturaId' => $aperturaId]) }}" />
          </div>
          <div class="flex flex-wrap justify-start">
              <x-input wire:model="montoInicial" readonly label="Monto Inicial:" mask="currency" />
          </div>
      </div>

      @livewire('tabla-detalle-apertura', ['aperturaId' => $aperturaId])

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
