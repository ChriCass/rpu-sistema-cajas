<div>
    <div class="p-4">

        <x-card>
            <!-- Mensaje de éxito -->
            @if (session()->has('message'))
                <x-alert title="¡Éxito!" positive padding="medium" class="mb-4">
                    <x-slot name="slot">
                        {{ session('message') }}
                    </x-slot>
                </x-alert>
            @endif

            <!-- Mensaje de error -->
            @if (session()->has('error'))
                <x-alert title="¡Error!" negative padding="medium" class="mb-4">
                    <x-slot name="slot">
                        {{ session('error') }}
                    </x-slot>
                </x-alert>
            @endif

            @if ($mostrarAlerta)
                <x-alert title="Documento no editable" class="mb-4" warning padding="medium">
                    <x-slot name="slot">
                        <div class="flex justify-between">
                            <div>
                                <p> Los Vaucher de Transferencia, Comprobante de Anticipo, o Vaucher de Rendición no se
                                    pueden editar.</p>
                            </div>

                            <div>
                                <x-button outline warning label="cerrar" wire:click="$set('mostrarAlerta', false)" />
                            </div>
                        </div>

                    </x-slot>
                </x-alert>
            @endif
            <div class="flex justify-end">
                <x-button label="Nuevo" xl wire:click='mostrarRegistro' />
            </div>

            @livewire('cxp-table')
        </x-card>
    </div>
</div>
