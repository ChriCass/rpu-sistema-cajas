<div>
    <x-card title="Editar Familia">
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
                <x-maskable wire:model="familia.descripcion" mask="AAAAAAAAAAAAAAAAAAA" label="Descripción"
                    class="w-full" />
                @error('familia.descripcion')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="w-full sm:w-6/12 px-4">
                <x-select wire:model="selectedTipoFamilia" label="Tipo de Familia" placeholder="Seleccionar..."
                    :options="$tipoFamilia->map(fn($tipo) => ['value' => $tipo->id, 'label' => $tipo->descripcion])->toArray()"
                    option-label="label" option-value="value" disabled />
                @error('familia.id_tipofamilias')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="w-full px-4 mt-4">
                <x-button outline label="Limpiar Campos" wire:click="clearFields" />
            </div>
            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button href="{{ route('familias') }}" wire:navigate label="Cancelar" flat />
                <x-button primary label="Guardar Cambios" wire:click="save" />
            </x-slot>
        </div>
    </x-card>
</div>
