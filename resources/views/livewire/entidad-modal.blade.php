<div>
    @if(!isset($hideButton) || !$hideButton)
        <x-button label="Nuevo" wire:click="$set('openModal', true)" primary />
    @endif

    <x-modal name="persistentModal" wire:model="openModal" persistent>
        <x-card title="Registro de entidades">
            <div class="flex flex-wrap justify-center -mx-4">
                @if (session()->has('message'))
                <x-alert title="Felicidades!" positive>
                    {{ session('message') }}
                </x-alert>
                @elseif (session()->has('error'))
                <x-alert title="Error!" negative>
                    {{ session('error') }}
                </x-alert>
                @endif

                <!-- Toggle para desconocer tipo de documento -->
                <div class="w-full sm:w-12/12 px-4 mb-3">
                    <x-toggle label="Desconozco tipo documento" wire:model.live="desconozcoTipoDocumento" />
                </div>

                <div class="w-full sm:w-12/12 px-4 mb-3">
                    <x-select label="Seleccione tipo documento" placeholder="Seleccione..."
                              :options="$docs" option-label="abreviado" option-value="id" :disabled="$desconozcoTipoDocumento" 
                              wire:model.live="tipoDocId" />
                    @error('tipoDocId')
                    @enderror
                </div>
                
                <!-- Input del ID del documento -->
                <div class="w-full sm:w-4/12 px-4">
                    <x-input wire:model.live="docIdent" wire:keydown.enter="processDocIdent" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1').slice(0, 11)" wire:keydown.enter="processDocIdent" label="Documento de Identidad"
                             class="w-full" :disabled="$desconozcoTipoDocumento" />
                    @error('docIdent')
                    @enderror
                </div>
                

                <!-- Input de nueva entidad -->
                <div class="w-full sm:w-8/12 px-4">
                    <x-input wire:model.live="entidad" label="Nueva entidad" class="w-full" :disabled="$desconozcoTipoDocumento1" oninput="this.value = this.value.toUpperCase()" />
                    @error('entidad')
                    @enderror
                </div>
           
            
                <x-slot name="footer" class="flex justify-end gap-x-4">
                    <x-button flat label="Cancelar" wire:click="$set('openModal', false)" />
                    <x-button primary label="Aceptar" wire:click='submitEntidad'  />
                </x-slot>
            </div>
        </x-card>
    </x-modal>
</div>


<script>
    Livewire.on('close-modal', () => {
        // Esperar 3 segundos y luego cerrar el modal
        setTimeout(() => {
            @this.set('openModal', false);
        }, 5000);
    });
</script>