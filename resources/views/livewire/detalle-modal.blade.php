<div>
    <x-button label="Nuevo" wire:click="$set('openModal', true)" primary />

    <x-modal name="persistentModal" wire:model="openModal" persistent>
        <x-card title="Registro de Productos">
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

                @livewire('input-familia-detalle')
                
                <div class="w-full sm:w-3/12 px-4">
                    <x-maskable wire:model="nuevo_producto" mask="AAAAAAAAAAAAAAAAAAA" label="Descripción"/>
                    @error('nuevo_producto')
                
                    @enderror
                </div>
                
                <div class="w-full sm:w-3/12 px-4">
                    <x-native-select wire:model="cuenta_id" label="Cuenta" :options="$cuentas" placeholder="Seleccionar..." option-label="descripcion" option-value="id" />
                    @error('cuenta_id')
                      
                    @enderror
                </div>

                <x-slot name="footer" class="flex justify-end gap-x-4">
                    <x-button flat label="Cancelar" wire:click="$set('openModal', false)" />
                    <x-button primary label="Aceptar" wire:click="insertNewProducto" />
                </x-slot>
            </div>
        </x-card>
    </x-modal>
</div>
