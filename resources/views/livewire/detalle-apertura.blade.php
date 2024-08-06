<div class="p-4">


    <x-card title="VaucherDePago">

        <div class="flex flex-wrap -mx-2">
            <div class="w-full sm:w-3/12 px-2">
                <x-select readonly  wire:model="caja" label="Tipo:" placeholder="Selecciona..." :options="$tipoCajas"
                    option-label="descripcion" option-value="id" />
           
            </div>
            <div class="w-full sm:w-3/12 px-2">
                <x-select readonly  wire:model="año" label="Año:" placeholder="Selecciona..." :options="$años"
                    option-label="year" option-value="key" />
           
            </div>
            <div class="w-full sm:w-3/12 px-2">
                <x-select readonly  wire:model="mes" label="Meses:" placeholder="Selecciona..." :options="$meses"
                    option-label="descripcion" option-value="id" />
          
            </div>
            <div class="w-full sm:w-1/12 px-2">
                <x-maskable readonly  wire:model="numero" label="N:" mask="#" />
            
            </div>
            <div class="w-full sm:w-2/12 px-2">
                <x-datetime-picker readonly wire:model="fecha" label="Fecha:" placeholder="Nueva Fecha" without-time
                    :min="now()->subDays(7)->hours(12)->minutes(30)" :max="now()->addDays(7)->hours(12)->minutes(30)" />
           
            </div>
        </div>
        <div class="flex flex-wrap mt-4 -mx-2">
            <div class="w-full sm:w-auto px-2">
                
                <x-button wire:navigate label="Salir" outline secondary href="{{ route('movimientos') }}" />
               
            </div>
        </div>

    </x-card>
</div>
