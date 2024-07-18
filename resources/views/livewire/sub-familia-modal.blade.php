<div>
    <x-button label="Nuevo" wire:click="$set('openModal', true)" primary />

    <x-modal name="persistentModal" wire:model="openModal" persistent>
        <x-card title="Registro de Subfamilias">
            <div class="flex flex-wrap -mx-4">
                <div class="w-full sm:w-4/12 px-4">
                    <x-native-select label="Familia" placeholder="Selecciona" :options="$familia" option-value="id" option-label="descripcion" />
                </div>
                <div class="w-full sm:w-2/12 px-4">
                    <x-input label="input 2" class="w-full" />
                </div>
                <div class="w-full sm:w-6/12 px-4">
                    <x-input label="descripcion" class="w-full" />
                </div>
                <x-slot name="footer" class="flex justify-end gap-x-4">
                    <x-button flat label="Cancelar" wire:click="$set('openModal', false)" />

                    <x-button primary label="Aceptar" wire:click="agree" />
                </x-slot>
            </div>
        </x-card>

    </x-modal>
</div>
