<div>
    <x-button label="Nuevo" wire:click="$set('openModal', true)" primary />

    <x-modal name="persistentModal" wire:model="openModal" persistent>
        <x-card title="Registro de Productos">
            <div class="flex flex-wrap -mx-4">
                <div class="w-full sm:w-2/12 px-4">
                    <select name="" id="" class="block w-full mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50">
                        <!-- Opciones del select -->
                    </select>
                </div>
                <div class="w-full sm:w-2/12 px-4">
                    <select name="" id="" class="block w-full mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50">
                        <!-- Opciones del select -->
                    </select>
                    
                </div>
                <div class="w-full sm:w-2/12 px-4">
                    <x-input label="input 2" class="w-full" />
                </div>
                <div class="w-full sm:w-3/12 px-4">
              
                </div>
                <div class="w-full sm:w-3/12 px-4">
                     
                </div>
                <x-slot name="footer" class="flex justify-end gap-x-4">
                    <x-button flat label="Cancelar" wire:click="$set('openModal', false)" />

                    <x-button primary label="Aceptar" wire:click="agree" />
                </x-slot>
            </div>
        </x-card>

    </x-modal>
</div>
