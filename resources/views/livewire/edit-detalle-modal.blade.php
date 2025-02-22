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

    <x-card title="Editar Detalle">
        <form class="flex flex-wrap justify-center -mx-4" wire:submit.prevent="save">
            <div class="w-full   px-4">
                <x-input label="ID" wire:model="detalleId" readonly />
            </div>
            <div class="w-full   px-4">
                <x-input   oninput="this.value = this.value.toUpperCase()" label="Descripción" wire:model="descripcion" />
                @error('descripcion') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="w-full  px-4">
                <x-select
                    label="Familia"
                    wire:model="familia"
                    :options="$familias"
                    option-label="descripcion"
                    option-value="id"
                    readonly
                />
                @error('familia') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="w-full   px-4">
                <x-select
                    label="Subfamilia"
                    wire:model="subfamilia"
                    :options="$subfamilias"
                    option-label="descripcion"
                    option-value="id"
                    readonly
                />
                @error('subfamilia') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="w-full   px-4">
                <x-select
                    label="Cuenta"
                    wire:model="cuenta"
                    :options="$cuentas"
                    placeholder="Seleccionar..."
                    option-label="descripcion"
                    option-value="id"
                />
                @error('cuenta') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mt-4 flex justify-end w-full px-4">
                <x-button flat label="Cancelar" wire:click="$dispatch('closeModal')" />
                <x-button primary type="submit" class="mx-4" label="Guardar Cambios" />
            </div>
        </form>
    </x-card>
</div>
