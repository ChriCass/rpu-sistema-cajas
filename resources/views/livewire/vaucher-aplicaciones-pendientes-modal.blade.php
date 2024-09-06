<div>
    <x-button label="Nuevo" wire:click="$set('openModal', true)" primary />

    <x-modal name="persistentModal" wire:model="openModal">
        <x-card title="Aplicaciones">
            <div class="p-4">
                @if (session()->has('message'))
                    <x-alert title="Felicidades!" positive>
                        {{ session('message') }}
                    </x-alert>
                @elseif (session()->has('warning'))
                    <x-alert title="Advertencia!" warning>
                        {{ session('warning') }}
                    </x-alert>
                @elseif (session()->has('error'))
                    <x-alert title="Error!" negative>
                        {{ session('error') }}
                    </x-alert>
                @endif
                
                <div class="flex justify-end mt-4 space-x-2">
                    <x-button outline secondary label="Cancelar" wire:click="$set('openModal', false)" />
                    <x-button label="Aceptar" wire:click="sendingData" primary />
                </div>

                <!-- Filtros de búsqueda -->
                <div class="flex flex-wrap -mx-2 mt-4">
                    <div class="w-3/12">
                        <select wire:model.live="filterColumn" class="w-full mb-3 p-2 border border-gray-300 rounded-md shadow-sm">
                            <option value="id_entidades">Entidades</option>
                            <option value="RZ">Descripción</option>
                            <option value="Descripcion">Cuenta</option>
                        </select>
                    </div>
                    <div class="w-7/12">
                        <input wire:model.live="searchTerm" class="w-full mb-3 p-2 border border-gray-300 rounded-md shadow-sm" placeholder="Buscar..." />
                    </div>
                </div>

                <!-- Tabla de aplicaciones -->
                <div class="overflow-x-auto mt-5">
                    <table id="pendientesTable" class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase">Selecc.</th>
                                <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase">Id</th>
                                <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase">T.doc</th>
                                <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase">Entidades</th>
                                <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase">Descripción</th>
                                <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase">Num</th>
                                <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase">Moneda</th>
                                <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase">Cuenta</th>
                                <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($aplicaciones as $aplicacion)
                                <tr>
                                    <td class="py-2 px-4 border-b border-gray-200 text-sm">
                                        <button wire:click="toggleSelection('{{ $aplicacion->id_documentos }}', '{{ $aplicacion->Num }}', '{{ $aplicacion->Descripcion }}')"
                                            class="{{ collect($contenedor)->contains(function ($item) use ($aplicacion) {
                                                return $item->id_documentos === $aplicacion->id_documentos && 
                                                       $item->Num === $aplicacion->Num && 
                                                       $item->Descripcion === $aplicacion->Descripcion;
                                            }) ? 'bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded' : 'bg-teal-500 hover:bg-teal-700 text-white py-2 px-4 rounded' }}">
                                            {{ collect($contenedor)->contains(function ($item) use ($aplicacion) {
                                                return $item->id_documentos === $aplicacion->id_documentos && 
                                                       $item->Num === $aplicacion->Num && 
                                                       $item->Descripcion === $aplicacion->Descripcion;
                                            }) ? 'Quitar' : 'Seleccionar' }}
                                        </button>
                                    </td>
                                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ $aplicacion->id_documentos }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ $aplicacion->tdoc }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ $aplicacion->id_entidades }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ $aplicacion->RZ }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ $aplicacion->Num }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ $aplicacion->Mon }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ $aplicacion->Descripcion }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ $aplicacion->monto }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        
                    </table>
                </div>
            </div>
        </x-card>
    </x-modal>
</div>
