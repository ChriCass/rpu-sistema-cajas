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
                        Estás a punto de <b>eliminar la aplicación con los siguientes detalles:</b>
                        @foreach ($detalles as $detalle)
                            <br><b>ID de la aplicación:</b> {{$detalle['id']}} - {{$detalle['descripcion']}}
                        @endforeach
                    </p>
                    
                    <p class="mt-2">Esta acción es <b>permanente</b> y no podrás deshacerla. Al eliminar esta aplicación, se perderán todos los registros asociados.</p>
                    <p class="text-red-600 mt-3">¿Estás absolutamente seguro de que deseas continuar con esta eliminación?</p>
                </x-slot>
            </x-alert>
            
            <!-- Footer con los botones de Cancelar y Eliminar -->
            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button flat negative label="Cancelar" wire:click="$set('openModal', false)" />
                <x-button negative label="Eliminar" wire:click="deleteAplicacion" />
            </x-slot>
        </x-card>
    </x-modal>
</div>
