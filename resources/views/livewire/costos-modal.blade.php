<div>
    <!-- Botón para abrir el modal -->
    <x-button label="Nuevo" wire:click="$set('openModal', true)" primary />

    <!-- Modal para el registro de centros de costos -->
    <x-modal name="persistentModal" wire:model="openModal" persistent>
        <x-card title="Registro de Centros de Costos">
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
                <form class="flex flex-wrap gap-4  justify-center px-3 -mx-4" wire:submit.prevent="insertNewCosto">
                    <!-- Campo Descripción -->
                    <div class="w-full sm:w-7/12 mb-3 ">
                        <x-input 
                            label="Descripción" 
                            wire:model="descripcion" 
                            oninput="this.value = this.value.toUpperCase()" 
                        />
                        @error('descripcion') 
                            <span class="text-red-500">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Campo Abreviatura -->
                    <div class="w-full sm:w-3/12 mb-3 ">
                        <x-maskable mask="AAA" 
                            label="Abreviatura" 
                            wire:model="abrev" 
                            oninput="this.value = this.value.toUpperCase()" 
                        />
                        @error('abrev') 
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