<div>
    <x-button label="Nuevo" wire:click="$set('openModal', true)" primary />

    <x-modal name="persistentModal" wire:model="openModal" persistent>
        <x-card title="Registro de Productos">
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

                <!-- Selección de familia -->
                <div class="w-full sm:w-6/12 px-4">
                    <label for="familia">Familia</label>
                    <select wire:model.live="familia_id" id="familia"  class="block w-full pl-4 pr-10 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm text-gray-600 disabled:bg-gray-100 disabled:text-gray-400">
                        <option value="">Seleccionar...</option>
                        @foreach ($familias as $fam)
                            <option value="{{ $fam->id }}">{{ $fam->descripcion }}</option>
                        @endforeach
                    </select>
                    @error('familia_id') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Selección de subfamilia -->
                <div class="w-full sm:w-6/12 px-4">
                    <label for="subfamilia">Subfamilia</label>
                    <select wire:model.live="subfamilia_id" id="subfamilia"  class="block w-full pl-4 pr-10 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm text-gray-600 disabled:bg-gray-100 disabled:text-gray-400">
                        <option value="">Seleccionar...</option>
                        @foreach ($subfamilias as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->desripcion }}</option>
                        @endforeach
                    </select>
                    @error('subfamilia_id') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Campo de descripción del nuevo producto -->
                <div class="w-full sm:w-6/12 px-4">
                    <x-maskable wire:model="nuevo_producto" mask="AAAAAAAAAAAAAAAAAAA" label="Descripción"/>
                    @error('nuevo_producto') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
                
                <!-- Selección de cuenta -->
                <div class="w-full sm:w-6/12 px-4">
                    <x-native-select wire:model="cuenta_id" label="Cuenta" :options="$cuentas" placeholder="Seleccionar..." option-label="descripcion" option-value="id" />
                    @error('cuenta_id') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <x-slot name="footer" class="flex justify-end gap-x-4">
                    <x-button flat label="Cancelar" wire:click="$set('openModal', false)" />
                    <x-button primary label="Aceptar" wire:click="insertNewProducto" />
                </x-slot>
            </div>
        </x-card>
    </x-modal>
</div>
