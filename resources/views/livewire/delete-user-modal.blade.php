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

    <x-card title="Eliminar Usuario">
        <form class="flex flex-wrap justify-center -mx-4" wire:submit.prevent="deleteUser">
            @if (!$canDelete)
                <!-- Alerta de advertencia cuando no se puede eliminar -->
                <x-alert title="Alerta!" warning padding="medium">
                    <x-slot name="slot">
                        {{ $errorMessage }}
                    </x-slot>
                </x-alert>
            @else
                <!-- Alerta de peligro cuando se puede eliminar -->
                <x-alert title="Acción Permanente" negative>
                    Esta es una acción permanente que no puede deshacerse. ¿Estás seguro de que deseas eliminar este
                    usuario con ID: {{ $userId }}?
                    Todos los datos asociados se perderán para siempre.
                </x-alert>
            @endif

            <!-- Botones -->
            <div class="mt-4 flex justify-end w-full px-4">
                @if (!$canDelete)
                    <x-button warning label="Regresar" wire:click="$dispatch('closeModal')" />
                @else
                    <x-button negative flat label="Cancelar" wire:click="$dispatch('closeModal')" />
                    <x-button negative primary type="submit" class="mx-4" label="Eliminar" />
                @endif
            </div>
        </form>
    </x-card>
</div>
