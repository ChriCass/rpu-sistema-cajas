<div>
    <!-- Botón para abrir el modal -->
    <x-button label="Nuevo" wire:click="$set('openModal', true)" primary />

    <!-- Modal para el registro de cajas -->
    <x-modal name="persistentModal" wire:model="openModal" persistent>
        <x-card title="Registro de Cajas">

            <div>
                <!-- Mensajes de éxito o error -->
                @if (session()->has('message'))
                    <x-alert title="Éxito!" positive>
                        {{ session('message') }}
                    </x-alert>
                @elseif (session()->has('error'))
                    <x-alert title="Error!" negative>
                        {{ session('error') }}
                    </x-alert>
                @endif

                <!-- Formulario -->
                <form class="flex flex-wrap justify-center -mx-4" wire:submit.prevent="insertNewCaja">
                    <!-- Campo Descripción -->
                    <div class="w-full px-4">
                        <x-input 
                            label="Descripción" 
                            wire:model="descripcion" 
                            oninput="this.value = this.value.toUpperCase()" 
                        />
                        @error('descripcion') 
                            <span class="text-red-500">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Campo Tipo de Moneda -->
                    <div class="w-full px-4">
                        <x-select 
                            label="Tipo de Moneda" 
                            wire:model="t04_tipodemoneda" 
                            placeholder="Seleccionar..."
                            :options="$tiposDeMoneda" 
                            option-label="descripcion" 
                            option-value="id" 
                        />
                        @error('t04_tipodemoneda') 
                            <span class="text-red-500">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="mt-4 flex justify-end w-full px-4">
                        <x-button flat label="Cancelar" wire:click="closeModal" />
                        <x-button primary type="submit" class="ml-2" label="Guardar" />
                    </div>
                </form>
            </div>
        </x-card>
    </x-modal>
</div>