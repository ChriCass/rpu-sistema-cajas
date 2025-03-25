<div>
    <x-card title="Eliminar Unidad">
        <form class="flex flex-wrap justify-center -mx-4" wire:submit.prevent="delete">
            @if (session()->has('message'))
                <x-alert title="Éxito!" positive>
                    {{ session('message') }}
                </x-alert>
            @elseif (session()->has('error'))
                <x-alert title="Error!" negative>
                    {{ session('error') }}
                </x-alert>
            @endif

            <!-- Alerta de peligro -->
            <x-alert title="Acción Permanente" negative>
                Esta es una acción permanente que no puede deshacerse. ¿Estás seguro de que deseas eliminar esta unidad?
                Todos los datos asociados se perderán para siempre.
            </x-alert>

            <!-- Botones -->
            <div class="mt-4 flex justify-end w-full px-4">
                <x-button negative flat label="Cancelar" wire:click="$dispatch('closeModal')" />
                <x-button negative primary type="submit" class="mx-4" label="Eliminar" />
            </div>
        </form>
    </x-card>
</div> 