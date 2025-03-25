<div>
    {{-- The best athlete wants his opponent at his best. --}}
    <div>
        {{ $this->template() }}
    </div>

    <!-- Modal -->
    <x-dialog-modal wire:model="showModal">
        <x-slot name="title">
            {{ $tipo_venta_id ? 'Editar Tipo de Venta' : 'Nuevo Tipo de Venta' }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 gap-4">
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
                {{ $tipo_venta_id ? 'Actualizar' : 'Guardar' }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
