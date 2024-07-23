<div>
    <x-button label="Nuevo" wire:click="$set('openModal', true)" primary />

    <x-modal name="persistentModal" wire:model="openModal" persistent>
        <x-card title="Registro de Subfamilias">
            <div class="flex flex-wrap -mx-4">
                @if (session()->has('message'))
                <x-alert title="Felicidades!" positive>
                    {{ session('message') }}
                </x-alert>
            @endif
                <div class="w-full sm:w-6/12 px-4">
                    <x-select wire:model="selectedFamilia" label="Familia" placeholder="Selecciona" :options="$familia" option-value="id" option-label="descripcion" />
                </div>
            
                <div class="w-full sm:w-6/12 px-4">
                    <x-maskable wire:model="nuevaSubfamilia" mask="AAAAAAAAAAAAAAAAAAA"  label="descripcion" class="w-full" />
                </div>
                <x-slot name="footer" class="flex justify-end gap-x-4">
                    <x-button flat label="Cancelar" wire:click="$set('openModal', false)" />

                    <x-button primary label="Aceptar" wire:click="insertNewSubFamilia" />
                </x-slot>
            </div>
        </x-card>

    </x-modal>
</div>
