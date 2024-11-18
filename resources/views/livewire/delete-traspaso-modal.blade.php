<div>
    <!-- Botón para abrir el modal de eliminación -->
    <x-button label="Borrar" wire:click="$set('openModal', true)" negative />

    <!-- Modal de confirmación de eliminación -->
    <x-modal name="persistentModal" wire:model="openModal" persistent>
        <x-card>
            <!-- Alerta de advertencia -->
            <x-alert title="¡Advertencia Importante!" negative padding="small">
                <x-slot name="slot">
                    <p class="text-red-600 font-semibold">
                        Estás a punto de <b>eliminar el traspaso con los siguientes detalles:</b>
                            <br><b>Movimiento de traspaso:</b> {{$detalles}}
                        
                    </p>
                    <p class="text-red-600 font-semibold">
                        Estás a punto de <b>eliminar el voucher de traspaso:</b>
                            <br><b>Vaucher de traspaso:</b> {{$voucher}}
                        
                    </p>
                    
                    <p class="mt-2">Esta acción es <b>permanente</b> y no podrás deshacerla. Al eliminar este traspaso, se perderán todos los registros asociados.</p>
                    <p class="text-red-600 mt-3">¿Estás absolutamente seguro de que deseas continuar con esta eliminación?</p>
                </x-slot>
            </x-alert>
            
            <!-- Footer con los botones de Cancelar y Eliminar -->
            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button flat negative label="Cancelar" wire:click="$set('openModal', false)" />
                <x-button negative label="Eliminar" wire:click="deleteAplication" />
            </x-slot>
        </x-card>
    </x-modal>
</div>
