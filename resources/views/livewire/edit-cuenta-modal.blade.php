<div>
    @if (session()->has('message'))
        <x-alert title="Felicidades!" positive>
            {{ session('message') }}
        </x-alert>
    @elseif (session()->has('error'))
        <x-alert title="Error!" negative>
            {{ session('error') }}
        </x-alert>
    @endif

    <x-card title="Editar Cuenta">
        <form class="flex flex-wrap justify-center -mx-4" wire:submit.prevent="save">
            <!-- Campo ID (solo lectura) -->
            <div class="w-full sm:w-3/12 px-2 mb-3">
                <x-input label="ID" wire:model="cuentaId" readonly />
            </div>

            <!-- Campo Descripción -->
            <div class="w-full sm:w-9/12 px-4">
                <x-input 
                    oninput="this.value = this.value.toUpperCase()" 
                    label="Descripción" 
                    wire:model="descripcion" 
                />
                @error('descripcion') 
                    <span class="text-red-500">{{ $message }}</span> 
                @enderror
            </div>

            <!-- Campo Tipo de Cuenta -->
            <div class="w-full px-4">
                <x-select
                    label="Tipo de Cuenta"
                    wire:model="idTipoCuenta"
                    :options="$tipoCuentas"
                    option-label="descripcion"
                    option-value="id"
                />
                @error('idTipoCuenta') 
                    <span class="text-red-500">{{ $message }}</span> 
                @enderror
            </div>

            <!-- Botones -->
            <div class="mt-4 flex justify-end w-full px-4">
                <x-button flat label="Cancelar" wire:click="$dispatch('closeModal')" />
                <x-button primary type="submit" class="mx-4" label="Guardar Cambios" />
            </div>
        </form>
    </x-card>
</div>