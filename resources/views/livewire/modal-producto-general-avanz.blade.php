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
                
                <div class="w-full sm:w-6/12 lg:w-8/12 px-4">
                    <x-select 
                        label="Descripción de producto:" 
                        placeholder="Seleccione un producto"
                        :options="$productos" 
                        option-label="descripcion" 
                        option-value="id" 
                        wire:model.live="productoSeleccionado"  
                    />
                </div>
                

                <div class="w-full mt-3  sm:w-3/12 px-4">
                    <x-input label="Observacion" />

                </div>
                <div class="w-full mt-3  sm:w-3/12 px-4">
                    <x-select 
                    label="Tasa Impositiva:"
                    :options="[['id' => '1', 'descripcion' => 'SI'], ['id' => '0', 'descripcion' => 'NO']]" 
                    option-label="descripcion" 
                    option-value="id" 
                    wire:model.live="tasaImpositiva"
                />

                </div>
                <div class="w-full sm:w-3/12 mt-3 px-4">
                    <x-input label="Cantidad" wire:model.live="cantidad" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1').slice(0, 10)"/>
                </div>
                
                <div class="w-full sm:w-3/12 mt-3 px-4">
                    <x-input label="C/U" wire:model.live="precioUnitario" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1').slice(0, 10)"/>
                </div>
                
                <div class="w-full sm:w-3/12 mt-3 px-4">
                    <x-input label="Total" wire:model.live="total" readonly />
                </div>
                
                <div class="w-full sm:w-5/12 md:w-6/12 lg:w-8/12 mt-3 px-4">
                    <x-select 
                        label="Centro de Costos:" 
                        placeholder="Seleccione un producto"
                        :options="$CentroDeCostos" 
                        option-label="descripcion" 
                        option-value="descripcion" 
                        wire:model.live="CC"  
                    />
                </div>
                

                <x-slot name="footer" class="flex justify-end gap-x-4">
                    <x-button flat label="Cancelar" wire:click="$set('openModal', false)" />
                    <x-button primary label="Aceptar" wire:click="sendingProductoTabla" />
                </x-slot>
            </div>
        </x-card>
    </x-modal>
</div>
