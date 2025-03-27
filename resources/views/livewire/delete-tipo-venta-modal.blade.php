<div>
    <x-card>
        <!-- Alerta de advertencia -->
        <x-alert title="¡Advertencia Importante!" negative padding="small">
            <x-slot name="slot">
                <p class="text-red-600 font-semibold">
                    Estás a punto de <b>eliminar el tipo de venta</b>.
                </p>
                <p class="mt-2">Esta acción es <b>permanente</b> y no podrás deshacerla. Al eliminar este tipo de venta, se perderán todos los datos asociados.</p>
                <p class="text-red-600 mt-3">¿Estás absolutamente seguro de que deseas continuar con esta eliminación?</p>
            </x-slot>
        </x-alert>
        
        <!-- Footer con los botones de Cancelar y Eliminar -->
        <x-slot name="footer" class="flex justify-end gap-x-4">
            <x-button flat negative label="Cancelar" wire:click="$dispatch('closeModal')" />
            <x-button negative label="Eliminar" wire:click="delete" />
        </x-slot>
    </x-card>
</div> 