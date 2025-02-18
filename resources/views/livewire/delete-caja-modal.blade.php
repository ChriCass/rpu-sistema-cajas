<div>
    @if (session()->has('message'))
        <x-alert title="Éxito!" positive>
            {{ session('message') }}
        </x-alert>
    @elseif (session()->has('error'))
        <x-alert title="Error!" negative>
            {{ session('error') }}
        </x-alert>
    @endif

    <x-card title="Eliminar Caja">
        <form class="flex flex-wrap justify-center -mx-4" wire:submit.prevent="deleteCaja">
            <!-- Alerta de peligro (Acción permanente) -->
            @if ($hasMovimientos)
                <!-- Alerta de advertencia cuando hay movimientos -->
                <x-alert title="Alerta!" warning padding="medium">
                    <x-slot name="slot">
                        Lamentablemente, no se puede eliminar esta caja debido a que tiene aperturas asociadas.
                        Recuerde que solo es posible eliminar aquellas cajas que no tengan registros relacionados.
                    </x-slot>
                </x-alert>
            @else
                <!-- Alerta de peligro cuando no hay movimientos -->
                <x-alert title="Acción Permanente" negative>
                    Esta es una acción permanente que no puede deshacerse. ¿Estás seguro de que deseas eliminar esta
                    caja con código: {{ $cajaId }}?
                    Todos los datos asociados se perderán para siempre.
                </x-alert>
            @endif

            <!-- Botones -->
            <div class="mt-4 flex justify-end w-full px-4">
                @if ($hasMovimientos)
                    <x-button warning label="Regresar" wire:click="$dispatch('closeModal')" />
                @else
                    <x-button negative flat label="Cancelar" wire:click="$dispatch('closeModal')" />
                    <x-button negative primary type="submit" class="mx-4" label="Eliminar" />
                @endif
            </div>
        </form>
    </x-card>
</div>