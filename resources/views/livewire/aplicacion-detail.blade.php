<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Vaucher de Pago
        </h2>
    </x-slot>

    <div class="p-4">
        <x-card>
            @livewire('edit-aplicacion-detail', ['aplicacionesId' => $aplicacionesId])
    
            <!-- Botones -->
            <div class="flex justify-end mt-4 space-x-2">
                <x-button label="Cancelar" outline secondary wire:click="$set('showFormEdit', false)" />
    
                @if($showFormEdit)
                    <x-button  icon="x-mark"  label="No Editar" warning wire:click="toggleEdit" />
                @else
                    <x-button icon="pencil" label="Editar" wire:click="toggleEdit" />
                @endif
            </div>
        </x-card>
    </div>
    @if($showFormEdit)
    <div class="p-4">
        @livewire('form-edit-aplicacion-detail')

    </div>
    @endif

</div>
