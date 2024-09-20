<div>
    <div class="p-4">
    
        <x-card>
            @if($mostrarAlerta)
            <x-alert title="Documento no editable" class="mb-4" warning padding="medium">
                <x-slot name="slot">
                    <div class="flex justify-between">
                        <div>
                            <p> Los Vaucher de Transferencia, Comprobante de Anticipo, o Vaucher de Rendición no se pueden editar.</p>  
                          </div>
                         
                          <div>
                            <x-button outline warning label="cerrar" wire:click="$set('mostrarAlerta', false)"  />
                          </div>
                    </div>
               
                </x-slot>
            </x-alert>
        @endif
        
            <div class="flex justify-end">
                <x-button label="Nuevo" xl wire:click='mostrarRegistro' />
            </div>
           
            @livewire('cxc-table')
        </x-card>
    </div>
</div>
