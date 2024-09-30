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

    <x-card title="Editar Familia">
        <form class="flex flex-wrap justify-center -mx-4" wire:submit.prevent="save">
            <div class="w-full sm:w-3/12 px-4">
                <x-input label="ID" wire:model="familiaId" readonly />
            </div>
            <div class="w-full sm:w-4/12 px-4">
                <x-input oninput="this.value = this.value.toUpperCase()" label="Descripción" wire:model="descripcion" />
                @error('descripcion') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="w-full sm:w-5/12 px-4">
                <x-select
                    label="Tipo de Familia"
                    wire:model="idTipofamilias"
                    :options="$tipoFamilias"
                    option-label="descripcion"
                    option-value="id"
                    readonly
                />
            </div>
            <div class="mt-4 flex justify-end">
                <x-button flat label="Cancelar" wire:click="$dispatch('closeModal')" />
                <x-button primary type="submit" class="mx-4" label="Guardar Cambios" />
            </div>
        </form>
    </x-card>
</div>
