<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Movimientos
        </h2>
    </x-slot>

    <div class="p-4">
        @livewire('detalle-apertura', ['aperturaId' => $aperturaId])
    </div>
</div>
