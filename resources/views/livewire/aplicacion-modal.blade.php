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
                   <x-input label="input 1" />
                </div>
                <div class="w-full sm:w-6/12 px-4">
                    <x-input label="input 1" />
                </div>
                <x-slot name="footer" class="flex justify-end gap-x-4">
                    <x-button flat label="Cancelar" wire:click="$set('openModal', false)" />
                    <x-button primary label="Aceptar" wire:click="insertNewFamilia" />
                </x-slot>
            </div>
        </x-card>
    </x-modal>
</div>
