<div>
    <x-card title="Nueva Unidad">
        <form class="flex flex-wrap justify-center -mx-4" wire:submit.prevent="save">
            @if (session()->has('message'))
                <x-alert title="Éxito!" positive>
                    {{ session('message') }}
                </x-alert>
            @elseif (session()->has('error'))
                <x-alert title="Error!" negative>
                    {{ session('error') }}
                </x-alert>
            @endif

            <!-- Número -->
            <div class="w-full px-4 mb-3">
                <div class="mb-4">
                    <label for="numero" class="block text-sm font-medium text-gray-700">Número</label>
                    <input type="text" 
                           wire:model.live="numero" 
                           id="numero" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           style="text-transform: uppercase"
                           placeholder="Ingrese el número">
                    @error('numero') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Descripción -->
            <div class="w-full px-4 mb-3">
                <div class="mb-4">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <input type="text" 
                           wire:model.live="descripcion" 
                           id="descripcion" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           style="text-transform: uppercase"
                           placeholder="Ingrese la descripción">
                    @error('descripcion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Estado -->
            <div class="w-full px-4 mb-3">
                <div class="mb-4">
                    <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
                    <select wire:model.live="estado" 
                            id="estado" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                    @error('estado') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Botones -->
            <div class="mt-4 flex justify-end w-full px-4">
                <x-button flat label="Cancelar" wire:click="$dispatch('closeModal')" />
                <x-button primary type="submit" class="ml-2" label="Guardar" />
            </div>
        </form>
    </x-card>
</div> 