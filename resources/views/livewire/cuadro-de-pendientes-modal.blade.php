<div>
    <x-button label="Nuevo" wire:click="$set('openModal', true)" primary />

    <x-modal name="persistentModal" wire:model="openModal">

        <x-card title="Cuadro De Pendientes">
            <div class="p-4">
                <!-- Mostrar el mensaje aquí -->
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

                <div class="flex flex-wrap -mx-2 mt-4">
                    <div class="w-full md:w-4/12 px-2">
                        <x-input readonly label="Fecha Apertura" wire:model="fechaApertura" />
                    </div>
                    <div class="w-full md:w-4/12 px-2">
                        <x-input readonly label="Moneda" wire:model="moneda" />
                    </div>
                </div>

                <div class="overflow-x-auto mt-5">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th
                                    class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Selecc.</th>
                                <th
                                    class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Id</th>
                                <th
                                    class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    T.doc</th>
                                <th
                                    class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Entidades</th>
                                <th
                                    class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Descripción</th>
                                <th
                                    class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Num</th>
                                <th
                                    class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Moneda</th>
                                <th
                                    class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Cuenta</th>
                                <th
                                    class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pendientes as $pendiente)
                                <tr>
                                    <td class="py-2 px-4 border-b border-gray-200 text-sm">
                                        <button wire:click="toggleSelection({{ $pendiente->id_documentos }})"
                                            class="{{ collect($contenedor)->contains('id_documentos', $pendiente->id_documentos) ? 'bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded' : 'bg-teal-500 hover:bg-teal-700 text-white py-2 px-4 rounded' }}">
                                            {{ collect($contenedor)->contains('id_documentos', $pendiente->id_documentos) ? 'Quitar' : 'Selecc' }}
                                        </button>
                                    </td>
                                    <td class="py-2 px-4 border-b border-gray-200 text-sm">
                                        {{ $pendiente->id_documentos }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ $pendiente->tdoc }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200 text-sm">
                                        {{ $pendiente->id_entidades }}
                                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ $pendiente->RZ }}</td>

                                    </td>
                                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ $pendiente->Num }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ $pendiente->Mon }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200 text-sm">
                                        {{ $pendiente->Descripcion }}
                                    </td>
                                    <td class="py-2 px-4 border-b border-gray-200 text-sm">{{ $pendiente->monto }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-end mt-4 space-x-2">
                    <x-button flat label="Cancelar" wire:click="$set('openModal', false)" />
                    <x-button label="Aceptar" wire:click="sendingData" primary />
                </div>
        </x-card>
</div>
</x-modal>
</div>
