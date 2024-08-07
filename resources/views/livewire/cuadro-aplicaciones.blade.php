<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Cuadro de aplicaciones
        </h2>
    </x-slot>

    <div class="p-4">
        <x-card title="CuadroDeAplicaciones">
            <div class="flex flex-wrap -mx-2 mt-4">
                <div class="w-full md:w-4/12 px-2">
                    <x-select readonly label="Mes:" placeholder="Selecciona Mes" :options="[]"
                        option-label="descripcion" option-value="id" />
                </div>
                <div class="w-full md:w-4/12 px-2">
                    <x-select readonly label="Año:" placeholder="Selecciona Año" :options="[]"
                        option-label="descripcion" option-value="id" />
                </div>
                <div class="w-full md:w-4/12 px-2 flex items-center justify-end">
                    <x-button label="Nuevo" />
                </div>
            </div>
            <div class="overflow-x-auto mt-5">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th
                                class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Cuenta</th>
                            <th
                                class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Fecha</th>
                            <th
                                class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Mov</th>
                            <th
                                class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Monto</th>
                            <th
                                class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Monto Do</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Aquí puedes agregar filas de datos -->
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end mt-4 space-x-2">
                <x-button label="Cancelar" wire:navigate outline secondary
                    href="{{ route('apertura.edit', ['aperturaId' => $aperturaId]) }}" />
            </div>
        </x-card>
    </div>
</div>
