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

    <x-card title="Editar Centro de Costos">
        <form class="flex flex-wrap justify-center px-3 -mx-4" wire:submit.prevent="save">
            <!-- Campo ID (solo lectura) -->
            <div class="w-full sm:w-6/12 px-2 mb-3">
                <x-input label="ID" wire:model="centroDeCostosId" readonly />
            </div>

            <!-- Campo Abreviatura -->
            <div class="w-full sm:w-6/12 px-2">
                <x-maskable mask="AAA" label="Abreviatura" wire:model="abrev" />
                @error('abrev')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <!-- Campo Descripción -->
            <div class="w-full px-2">
                <x-input label="Descripción" wire:model="descripcion" oninput="this.value = this.value.toUpperCase()" />
                @error('descripcion')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>



            <!-- Botones -->
            <div class="mt-4 flex justify-end w-full px-4">
                <x-button flat label="Cancelar" wire:click="$dispatch('closeModal')" />
                <x-button primary type="submit" class="ml-2" label="Guardar Cambios" />
            </div>
        </form>
    </x-card>
</div>
