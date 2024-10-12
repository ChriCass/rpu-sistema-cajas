<div>

    <x-button label="Nuevo" wire:click="$set('openModal', true)" primary />

    <x-modal name="persistentModal" wire:model="openModal" persistent>
        <x-card title="Registro de familias">

            <div class="flex flex-wrap -mx-4">
                @if (session()->has('message'))
                    <x-alert title="Felicidades!" positive>
                        {{ session('message') }}
                    </x-alert>
                @elseif (session()->has('error'))
                    <x-alert title="Error!" negative>
                        {{ session('error') }}
                    </x-alert>
                @endif
                <div class="w-full sm:w-4/12 px-4">
                    <x-select wire:model="tipo_caja" label="Tipo" placeholder="Selecciona..." :options="$tipoCajas" option-label="descripcion" option-value="id" />
                    @error('tipo_caja')
                    @enderror
                </div>
                <div class="w-full sm:w-4/12 px-4">
                    <x-select wire:model="nuevo_año"  label="Año" placeholder="Selecciona..." :options="$años" option-label="year" option-value="key" />
                    @error('nuevo_año')
                    @enderror
                </div>

                <div class="w-full sm:w-4/12 px-4">
                    <x-select wire:model="nuevo_mes" label="meses" placeholder="Selecciona..." :options="$meses" option-label="descripcion" option-value="id" />
                    @error('nuevo_mes')
                    @enderror
                </div>
                <div class="w-full mt-3  sm:w-3/12 px-4">
                    <x-maskable  wire:model="nuevo_numero" label="Numero" mask="###"  />
                    @error('nuevo_numero')
                    @enderror
                </div>
                <div class="w-full sm:w-9/12 mt-3  px-4">
                    <x-datetime-picker wire:model="nueva_fecha"  label="Fecha" placeholder="Nueva Fecha"
                    without-time :min="now()->subDays(7)->hours(12)->minutes(30)" :max="now()->addDays(7)->hours(12)->minutes(30)" />
                    @error('nueva_fecha')
                    @enderror
                </div>
                <x-slot name="footer" class="flex justify-end gap-x-4">
                    <x-button flat label="Cancelar" wire:click="$set('openModal', false)" />
                    <x-button primary label="Aceptar" wire:click="insertNewApertura" />
                </x-slot>
            </div>
        </x-card>
    </x-modal>
</div>
