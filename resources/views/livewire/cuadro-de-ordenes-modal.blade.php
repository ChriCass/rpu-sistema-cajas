<div>
    <x-button label="Detalle" wire:click="$set('openModal', true)" primary />

    <x-modal name="persistentModal" wire:model="openModal" persistent>
        <x-card title="Registro de Productos">
            <div class="flex bg-white justify-center">
                @livewire('cuadro-de-ordenes-table')
                
                <x-slot name="footer" class="flex justify-end gap-x-4">
                    <x-button flat label="Cancelar" wire:click="$set('openModal', false)" />
                </x-slot>
            </div>
        </x-card>
    </x-modal>
</div>
