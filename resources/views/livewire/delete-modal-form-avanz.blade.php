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
                        Estás a punto de <b>eliminar el siguiente 
                        @if ($origen === 'editar_cxc' || $origen === 'cxc')
                            movimiento de Compras por Cobrar (CXC) con Id: {{$IdDocumento}}
                        @elseif ($origen === 'editar_cxp' || $origen === 'cxp')
                            movimiento de Compras por Pagar (CXP) con Id: {{$IdDocumento}}
                        @else
                            {{ str_replace('_', ' ', $origen) }}
                        @endif
                        </b>
                    </p>
                    <p class="mt-2">Esta acción es <b>permanente</b> y no podrás deshacerla. Al eliminar este registro, se perderán todos los datos asociados.</p>
                    <p class="text-red-600 mt-3">¿Estás absolutamente seguro de que deseas continuar con esta eliminación?</p>
                </x-slot>
                
            </x-alert>
            
            <!-- Footer con los botones de Cancelar y Eliminar -->
            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button flat negative label="Cancelar" wire:click="$set('openModal', false)" />
                <x-button negative label="Eliminar" wire:click="delete" />
            </x-slot>
        </x-card>
    </x-modal>
</div>
