<div>
    <!-- Botón para abrir el modal -->
    <x-button label="Nuevo" wire:click="$set('openModal', true)" primary />

    <!-- Modal para el registro de cuentas -->
    <x-modal name="persistentModal" wire:model="openModal" persistent>
        <x-card title="Registro de Cuentas">

            <div class="flex flex-wrap -mx-4">
                <!-- Mensajes de éxito o error -->
                @if (session()->has('message'))
                    <x-alert title="Felicidades!" positive>
                        {{ session('message') }}
                    </x-alert>
                @elseif (session()->has('error'))
                    <x-alert title="Error!" negative>
                        {{ session('error') }}
                    </x-alert>
                @endif

                <!-- Campo Descripción -->
                <div class="w-full sm:w-6/12 px-4">
                    <x-input 
                        wire:model="descripcion" 
                        class="w-full" 
                        label="Descripción de la Cuenta" 
                        oninput="this.value = this.value.toUpperCase()"
                    />
                    @error('descripcion')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Campo Tipo de Cuenta -->
                <div class="w-full sm:w-6/12 px-4">
                    <x-select 
                        wire:model="idTipoCuenta" 
                        label="Tipo de Cuenta" 
                        placeholder="Seleccionar..."
                        :options="$tipoCuentas" 
                        option-label="descripcion" 
                        option-value="id" 
                    />
                    @error('idTipoCuenta')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Botón para limpiar campos -->
                <div class="w-full px-4 mt-4">
                    <x-button outline label="Limpiar Campos" wire:click="clearFields" />
                </div>

                <!-- Footer del modal con botones de acción -->
                <x-slot name="footer" class="flex justify-end gap-x-4">
                    <x-button flat label="Cancelar" wire:click="closeModal" />
                    <x-button primary label="Aceptar" wire:click="insertNewCuenta" />
                </x-slot>
            </div>
        </x-card>
    </x-modal>
</div>