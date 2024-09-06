<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Movimientos
        </h2>
    </x-slot>

    <!-- Componente principal de detalle de apertura -->
    <div class="p-4">
        @livewire('detalle-apertura', ['aperturaId' => $aperturaId])
    </div>

    <!-- Mostrar los componentes principales -->
    @if ($mostrarIngreso || $mostrarSalida || $mostrarCXP || $mostrarCXC || $mostrarAplicaciones)
        <div class="mt-4 flex justify-center">
            <!-- Botón de cancelar, resetea todos los componentes -->
            <x-button right-icon="x-mark" warning label="Cancelar" wire:click="cancelarComponente" />
        </div>
    @endif

    <!-- Componentes principales -->
    <div class="mt-4 p-4">
        @if ($mostrarIngreso)
            @livewire('registro-documentos-ingreso', ['aperturaId' => $aperturaId])
        @elseif ($mostrarSalida)
            @livewire('registro-documentos-egreso', ['aperturaId' => $aperturaId])
        @elseif ($mostrarCXP)
            @livewire('vaucher-pago-compras', ['aperturaId' => $aperturaId])
        @elseif ($mostrarCXC)
            @livewire('vaucher-pago-ventas', ['aperturaId' => $aperturaId])
        @elseif ($mostrarAplicaciones)
            <div class="flex flex-wrap -mx-4">
                <div class="w-full w-6/12 px-4 mb-4">
                    @livewire('aplicacion-table')
                </div>
                <div class="w-full w-6/12 px-4 mb-4">
                    @livewire('vaucher-de-aplicaciones')
                </div>
            </div>
        @endif
    </div>

    <!-- Mostrar los componentes secundarios debajo de los principales -->
    <div class="mt-8">
        @if ($mostrarRegistroCXP)
            @livewire('registro-cxp', ['aperturaId' => $aperturaId])
        @endif

        @if ($mostrarIngresoComponente)
            @livewire('registro-ingreso', ['aperturaId' => $aperturaId])
        @endif

        @if ($mostrarGastoComponente)
            @livewire('registro-gasto', ['aperturaId' => $aperturaId])
        @endif

        @if ($mostrarRegistroCXC)
            @livewire('registro-cxc', ['aperturaId' => $aperturaId])
        @endif
    </div>
</div>
