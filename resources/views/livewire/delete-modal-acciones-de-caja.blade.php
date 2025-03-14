<div>
    <!-- Modal de confirmación de eliminación -->
    <x-modal name="persistentModal" wire:model="openModal" persistent>
        <x-card>
            <!-- Alerta de advertencia -->
            <x-alert title="¡Advertencia Importante!" :type="$mov == '1' ? 'positive' : 'negative'" padding="small">
                <x-slot name="slot">
                    <p> Estás a punto de <b> @if($mov == '1') Generar @else Eliminar @endif varias aperturas</b>.</p>
                    <p>Esta acción es <b>permanente</b> y no podrás deshacerla.</p>
                    <p>¿Estás seguro de que deseas continuar?</p>
                </x-slot>
            </x-alert>
            
            <!-- Footer con los botones de Cancelar y Eliminar -->
            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button flat :type="$mov == '1' ? 'positive' : 'negative'" label="Cancelar" wire:click="$set('openModal', false)" />
                <x-button :type="$mov == '1' ? 'positive' : 'negative'" :label="$mov == 1 ? 'Generar' : 'Eliminar'" wire:click="AccionesDocumentos" />
            </x-slot>
        </x-card>
    </x-modal>
</div>
