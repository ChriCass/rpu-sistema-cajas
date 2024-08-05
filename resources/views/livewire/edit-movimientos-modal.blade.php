<div>
    @if (session()->has('message'))
    <x-alert title="Felicidades!" positive>
        {{ session('message') }}
    </x-alert>
    @elseif (session()->has('error'))
    <x-alert title="Error!" negative>
        {{ session('error') }}
    </x-alert>
    @endif

    <x-card title="Registro de familias">
        <div class="flex flex-wrap -mx-4">
            <div class="w-full sm:w-4/12 px-4">
                <x-select wire:model="caja" label="Tipo" placeholder="Selecciona..." :options="$tipoCajas" option-label="descripcion" option-value="id" />
                @error('caja')
                @enderror
            </div>
            <div class="w-full sm:w-4/12 px-4">
                <x-select wire:model="año" label="Año" placeholder="Selecciona..." :options="$años" option-label="year" option-value="key" />
                @error('año')
                @enderror
            </div>

            <div class="w-full sm:w-4/12 px-4">
                <x-select wire:model="mes" label="Mes" placeholder="Selecciona..." :options="$meses" option-label="descripcion" option-value="id" />
                @error('mes')
                @enderror
            </div>
            <div class="w-full mt-3 sm:w-3/12 px-4">
                <x-maskable wire:model="numero" label="Numero" mask="#" />
                @error('numero')
                @enderror
            </div>
            <div class="w-full sm:w-9/12 mt-3 px-4">
                <x-datetime-picker wire:model="fecha" label="Fecha" placeholder="Nueva Fecha"
                without-time :min="now()->subDays(7)->hours(12)->minutes(30)" :max="now()->addDays(7)->hours(12)->minutes(30)" />
                @error('fecha')
                @enderror
            </div>
            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button flat label="Salir" wire:click="$dispatch('closeModal')" />
            </x-slot>
        </div>
    </x-card>
</div>
