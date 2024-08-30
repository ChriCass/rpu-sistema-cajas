<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Vaucher de Pago
        </h2>
    </x-slot>

    <div class="p-4">
        @livewire('edit-aplicacion-detail', ['aplicacionesId' => $aplicacionesId])

    </div>
</div>
