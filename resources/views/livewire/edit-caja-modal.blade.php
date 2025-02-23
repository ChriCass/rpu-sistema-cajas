<div>
    @if (session()->has('message'))
        <x-alert title="Éxito!" positive>
            {{ session('message') }}
        </x-alert>
    @elseif (session()->has('error'))
        <x-alert title="Error!" negative>
            {{ session('error') }}
        </x-alert>
    @endif

    <x-card title="Editar Caja">
        <form class="flex flex-wrap justify-center -mx-4" wire:submit.prevent="save">
            <!-- Campo ID (solo lectura) -->
            <div class="w-full sm:w-3/12 px-2 mb-3">
                <x-input label="ID" wire:model="cajaId" readonly />
            </div>

            <!-- Campo Descripción -->
            <div class="w-full sm:w-8/12 px-4">
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
                    :disabled="$hasMovimientos"
                />
                @error('t04_tipodemoneda')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror

                <!-- Mensaje pequeño si hay movimientos -->
                @if ($hasMovimientos)
                    <span class="text-sm text-gray-500">
                        No se puede cambiar el tipo de moneda porque la caja tiene movimientos asociados.
                    </span>
                @endif
            </div>

            <!-- Botones -->
            <div class="mt-4 flex justify-end w-full px-4">
                <x-button flat label="Cancelar" wire:click="$dispatch('closeModal')" />
                <x-button primary type="submit" class="ml-2" label="Guardar Cambios" />
            </div>
        </form>
    </x-card>
</div>