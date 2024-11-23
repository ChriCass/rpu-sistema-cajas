<div>
    <x-button label="Nuevo" wire:click="$set('openModal', true)" primary />

    <x-modal name="persistentModal" wire:model="openModal" persistent>
        <x-card title="Registro de familias">

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
                <div class="w-full sm:w-6/12 px-4">
                    <x-input wire:model="nuevafamilia"  class="w-full"  label="Nueva familia" oninput="this.value = this.value.toUpperCase()"/>
  
                    @error('nuevafamilia')
                    @enderror
                </div>
                <div class="w-full sm:w-6/12 px-4">
                    <x-select wire:model="selectedTipoFamilia" label="Afecta a" placeholder="Seleccionar..."
                        :options="$tipoFamilia" option-label="descripcion" option-value="id" />
                    @error('selectedTipoFamilia')
                    @enderror
                </div>
                <div class="w-full px-4 mt-4">
                    <x-button outline label="Limpiar Campos" wire:click="clearFields" />
                </div>
                <x-slot name="footer" class="flex justify-end gap-x-4">
                    <x-button flat label="Cancelar" wire:click="closeModal" />
                    <x-button primary label="Aceptar" wire:click="insertNewFamilia" />
                </x-slot>
            </div>
        </x-card>
    </x-modal>
</div>
