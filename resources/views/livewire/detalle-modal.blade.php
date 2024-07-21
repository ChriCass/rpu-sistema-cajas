<div>
    <x-button label="Nuevo" wire:click="$set('openModal', true)" primary />

    <x-modal name="persistentModal" wire:model="openModal" persistent>
        <x-card title="Registro de Productos">
            <div class="flex flex-wrap -mx-4">
               
                @livewire('input-familia-detalle')
                
            
                <div class="w-full sm:w-3/12 px-4">
                    <x-input label="Descripción"/>
                </div>
                <div class="w-full sm:w-3/12 px-4">
                     <x-native-select label="Cuenta" :options="$cuentas" placeholder="Seleccionar..." option-label="descripcion" option-value="id_tcuentas" />
                </div>
                <x-slot name="footer" class="flex justify-end gap-x-4">
                    <x-button flat label="Cancelar" wire:click="$set('openModal', false)" />

                    <x-button primary label="Aceptar" wire:click="agree" />
                </x-slot>
            </div>
        </x-card>

    </x-modal>
</div>
