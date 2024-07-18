<div>
    <x-button label="Nuevo" wire:click="$set('openModal', true)" primary />
 
    <x-modal name="persistentModal" wire:model="openModal" persistent>
        <x-card title="Registro de familias">
            <div class="flex flex-wrap -mx-4">
              <div class="w-full sm:w-4/12 px-4">
                <x-input label="input 1" class="w-full"/>
              </div>
              <div class="w-full sm:w-4/12 px-4">
                <x-input label="input 2" class="w-full"/>
              </div>
              <div class="w-full sm:w-4/12 px-4">
                <x-select
                label="afecta a"
                placeholder="Select one status"
                :options="['Active', 'Pending', 'Stuck', 'Done']"
            />
              </div>
              <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button flat label="Cancelar" wire:click="$set('openModal', false)" />
     
                <x-button primary label="Aceptar" wire:click="agree" />
            </x-slot>
            </div>
          </x-card>
          
    </x-modal>
</div>
