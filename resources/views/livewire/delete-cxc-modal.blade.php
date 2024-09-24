<div>
    <!-- Botón para abrir el modal de eliminación -->
    <x-button label="Borrar " wire:click="$set('openModal', true)" negative />

    <!-- Modal de confirmación de eliminación -->
    <x-modal name="persistentModal" wire:model="openModal" persistent>
        <x-card>
            <!-- Alerta de advertencia -->
            <x-alert title="¡Advertencia Importante!" negative padding="small">
                <x-slot name="slot">
                    <p class="text-red-600 font-semibold">Estás a punto de <b>eliminar la cuenta por cobrar numero: {{$idcxc }}</b>.</p>
                    <p>Esta acción es <b>permanente</b> y no podrás deshacerla.</p>
                    <p class="text-red-600 mt-3">¿Estás seguro de que deseas continuar?</p>
                </x-slot>
            </x-alert>
            
            <!-- Footer con los botones de Cancelar y Eliminar -->
            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button flat negative label="Cancelar" wire:click="$set('openModal', false)" />
                <x-button negative label="Eliminar" wire:click="deleteCXC" />
            </x-slot>
        </x-card>
    </x-modal>
</div>
