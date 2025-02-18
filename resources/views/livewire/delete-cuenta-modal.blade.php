<div>
    <x-card title="Eliminar Cuenta">
        <form class="flex flex-wrap justify-center -mx-4" wire:submit.prevent="deleteCuenta">

            <!-- Alerta de peligro (Acción permanente) -->
            @if ($hasMovimientos)
                <!-- Alerta de advertencia cuando hay movimientos -->
                <x-alert title="Alerta!" warning padding="medium">
                    <x-slot name="slot">
                        Lamentablemente, no se puede eliminar esta cuenta debido a que tiene movimientos asociados.
                        Recuerde que solo es posible eliminar aquellas cuentas que no tengan registros relacionados
                    </x-slot>
                </x-alert>
            @else
                <!-- Alerta de peligro cuando no hay movimientos -->
                <x-alert title="Acción Permanente" negative>
                    Esta es una acción permanente que no puede deshacerse. ¿Estás seguro de que deseas eliminar esta
                    cuenta con código: {{ $cuentaId }}?
                    Todos los datos asociados se perderán para siempre.
                </x-alert>
            @endif

            @if ($hasMovimientos)
                <div class="mt-4 flex justify-end w-full px-4">
                    <x-button warning label="regresar" wire:click="$dispatch('closeModal')" />
                </div>
            @else
                <div class="mt-4 flex justify-end w-full px-4">
                    <x-button negative flat label="Cancelar" wire:click="$dispatch('closeModal')" />
                    <x-button negative primary type="submit" class="mx-4" label="Eliminar" />
                </div>
            @endif
        </form>
    </x-card>
</div>
