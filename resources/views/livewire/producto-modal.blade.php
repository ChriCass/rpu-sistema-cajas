<div>
    <x-button label="Nuevo" wire:click="showModal" primary />

    <x-modal name="persistentModal" wire:model="openModal" persistent>
        <x-card title="Registro de productos">
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
                    <label for="familia">Familia</label>
                    <select wire:model.live="selectedFamilia" id="familia"  class="block w-full mt-1 bg-white border border-gray-300 rounded-md shadow-md focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50">
                        <option value="">Seleccionar...</option>
                        @foreach($familias as $familia)
                            <option value="{{ $familia->id }}">{{ $familia->descripcion }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="w-full sm:w-6/12 px-4">
                    <label for="subfamilia">Subfamilia</label>
                    <select wire:model.live="selectedSubfamilia" id="subfamilia"  class="block w-full mt-1 bg-white border border-gray-300 rounded-md shadow-md focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50">
                        <option value="">Seleccionar...</option>
                        @foreach($subfamilias as $subfamilia)
                            <option value="{{ $subfamilia->id }}">{{ $subfamilia->desripcion }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="w-full sm:w-6/12 px-4 mt-3">
                    <label for="detalle">Detalle</label>
                    <select wire:model.live="selectedDetalle" id="detalle"  class="block w-full mt-1 bg-white border border-gray-300 rounded-md shadow-md focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50">
                        <option value="">Seleccionar...</option>
                        @foreach($detalles as $detalle)
                            <option value="{{ $detalle->id }}">{{ $detalle->descripcion }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full sm:w-6/12 px-4 mt-3">
                    <label for="codigo">Código</label>
                    <x-input readonly id="codigo" class="mt-1" wire:model.live="codigo" />
                </div>

                <div class="w-full px-4 mt-3">
                    <x-textarea label="descripcion" wire:model.live='producto' placeholder="escribir descripcion"  maxlength="255" />
                    <p class="text-sm text-gray-600 mt-1">{{ 255 - strlen($producto) }} caracteres restantes</p>
                </div>

                <div class="w-full px-4 mt-4">
                    <x-button outline label="Limpiar Campos" wire:click="clearFields" />
                </div>
                
                <x-slot name="footer" class="flex justify-end gap-x-4">
                    <x-button flat label="Cancelar" wire:click="$set('openModal', false)" />
                    <x-button primary label="Aceptar" wire:click="insertNewFamilia" />
                </x-slot>
            </div>
        </x-card>
    </x-modal>
</div>
