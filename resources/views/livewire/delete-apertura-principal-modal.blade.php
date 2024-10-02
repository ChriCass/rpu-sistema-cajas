<div>
    <x-card title="Eliminar apertura">
        <form class="flex flex-wrap justify-center -mx-4" wire:submit.prevent="delete">
           
            <!-- Alerta de peligro -->
            <x-alert title="Acción Permanente" negative>
                Esta es una acción permanente que no puede deshacerse. ¿Estás seguro de que deseas eliminar esta apertura con numero: {{$aperturaId}}? 
                Todos los datos asociados se perderán para siempre.
            </x-alert>

            <div class="mt-4 flex justify-end w-full px-4">
                <x-button negative flat label="Cancelar" wire:click="$dispatch('closeModal')" />
                <x-button negative primary type="submit" class="mx-4" label="Eliminar" />
            </div>
        </form>
    </x-card>
</div>

