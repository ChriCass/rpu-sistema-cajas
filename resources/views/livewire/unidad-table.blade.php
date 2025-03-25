<div>
    <div>
        {{ $this->template() }}
    </div>

    <!-- Modal -->
    <x-dialog-modal wire:model="showModal">
        <x-slot name="title">
            {{ $unidad_id ? 'Editar Unidad' : 'Nueva Unidad' }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <x-label for="numero" value="Número" />
                    <x-input id="numero" type="text" class="mt-1 block w-full" wire:model="numero" />
                    @error('numero') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-label for="descripcion" value="Descripción" />
                    <x-input id="descripcion" type="text" class="mt-1 block w-full" wire:model="descripcion" />
                    @error('descripcion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-label for="estado" value="Estado" />
                    <x-select id="estado" class="mt-1 block w-full" wire:model="estado">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </x-select>
                    @error('estado') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeModal" wire:loading.attr="disabled">
                Cancelar
            </x-secondary-button>

            <x-button class="ml-2" wire:click="save" wire:loading.attr="disabled">
                {{ $unidad_id ? 'Actualizar' : 'Guardar' }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
