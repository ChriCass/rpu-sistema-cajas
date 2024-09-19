<div>
    <x-button label="Borrar" wire:click="$set('openModal', true)" negative />

    <x-modal name="persistentModal" wire:model="openModal" persistent>
        <x-card>
            <x-alert title="¡Advertencia Importante!" negative padding="small">
                <x-slot name="slot">
                    <p class="text-red-600 font-semibold">Estás a punto de <b>eliminar este registro</b>.</p>
                    <p>Esta acción es <b>permanente</b> y no podrás deshacerla.</p>
                    <p class="mt-2">
                        <span class="font-bold">Número de Movimiento:</span> {{$numMov}} <br>
                        <span class="font-bold"> Apertura a la cual pertenece:</span> {{$aperturaId}}
                    </p>
                    <p class="text-red-600 mt-3">¿Estás seguro de que deseas continuar?</p>
                </x-slot>
            </x-alert>
            
            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button flat negative label="Cancelar" wire:click="$set('openModal', false)" />
                <x-button negative label="Eliminar" wire:click="insertNewProducto" />
            </x-slot>
        </x-card>
    </x-modal>
</div>
