<div>

    <x-button label="Nuevo" wire:click="$set('openModal', true)" primary />

    <x-modal name="persistentModal" wire:model="openModal" persistent>
        <x-card title="Modal de producto">

            <div class="flex flex-wrap -mx-4">
                @if (session()->has('message'))
                    <x-alert title="Felicidades!" positive>
                        {{ session('message') }}
                    </x-alert>
                @elseif (session()->has('error'))
                    <x-alert title="Error!" negative>
                        {{ session('error') }}
                    </x-alert>
                @endif
                <div class="w-full sm:w-3/12 px-4">
                    <x-input label="Código" wire:model.live="codigoProducto" readonly />
                </div>
                
                <div class="w-full sm:w-4/12 px-4">
                    <x-select 
                        label="Descripción de producto:" 
                        placeholder="Seleccione un producto"
                        :options="$productos" 
                        option-label="descripcion" 
                        option-value="id" 
                        wire:model.live="productoSeleccionado"  
                    />
                </div>
                

                <div class="w-full sm:w-5/12 px-4">
                    <x-input label="Observacion" />

                </div>
                <div class="w-full mt-3  sm:w-3/12 px-4">
                    <x-select label="Tasa impo.:" :options="[]" option-label="descripcion" option-value="id" />

                </div>
                <div class="w-full sm:w-3/12 mt-3 px-4">
                    <x-maskable mask="######" label="Cantidad" wire:model.live="cantidad" />
                </div>
                
                <div class="w-full sm:w-3/12 mt-3 px-4">
                    <x-maskable mask="######" label="C/U" wire:model.live="precioUnitario" />
                </div>
                
                <div class="w-full sm:w-3/12 mt-3 px-4">
                    <x-input label="Total" wire:model.live="total" readonly />
                </div>
                
                

                <x-slot name="footer" class="flex justify-end gap-x-4">
                    <x-button flat label="Cancelar" wire:click="$set('openModal', false)" />
                    <x-button primary label="Aceptar" wire:click="insertNewApertura" />
                </x-slot>
            </div>
        </x-card>
    </x-modal>
</div>
