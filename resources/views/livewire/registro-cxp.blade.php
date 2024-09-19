<div>
    <div class="p-4">
    
        <x-card>
            <div class="flex justify-end">
                <x-button label="Nuevo" xl wire:click='mostrarRegistro' />
            </div>
           
            @livewire('cxp-table')
        </x-card>
    </div>
</div>
