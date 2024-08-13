<div>
    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Registro CXP
        </h2>
    </x-slot>

    <div class="p-4">
        <x-card title="CuadroDeDocumentos">
            <x-button icon="arrow-left" outline secondary label="Regresar" wire:navigate
                href="{{ route('apertura.edit.vaucherdepagos', ['aperturaId' => $aperturaId]) }}" />
            <div class="flex   flex-wrap -mx-2 mt-4">
                <div class="w-full md:w-2/12 px-2">
                    <x-select readonly label="Mes:" placeholder="Selecciona Mes" :options="[]"
                        option-label="descripcion" option-value="id" />
                </div>
                <div class="w-full md:w-2/12 px-2">
                    <x-select readonly label="T. Doc:" placeholder="Selecciona Documento" :options="[]"
                        option-label="descripcion" option-value="id" />
                </div>
                <div class="w-full md:w-2/12 px-2">
                    <x-select readonly label="Moneda:" placeholder="Selecciona Moneda" :options="[]"
                        option-label="descripcion" option-value="id" />
                </div>
                <div class="w-full md:w-2/12 px-2">
                    <x-select readonly label="Tasa:" placeholder="Selecciona Tasa" :options="[]"
                        option-label="descripcion" option-value="id" />
                </div>
                <div class="w-full md:w-2/12 px-2 flex items-center justify-end">
                    <button    wire:navigate 
    href="{{ route('apertura.edit.vaucherdepagos.registrocxp.formregistrocxp', ['aperturaId' => $aperturaId]) }}" class="bg-teal-500 hover:bg-teal-700 mt-5 text-white py-3 px-6 rounded">
                        Nuevo
                    </button>

                </div>
            </div>
            <div class="flex flex-wrap  -mx-2 mt-4">
                <div class="w-full md:w-2/12 px-2">
                    <x-select readonly label="Usuarios:" placeholder="Selecciona Usuario" :options="[]"
                        option-label="descripcion" option-value="id" />
                </div>
                <div class="w-full md:w-2/12 px-2">
                    <x-input readonly label="" placeholder="" value="" />
                </div>
                <div class="w-full md:w-2/12 px-2">
                    <x-input readonly label="" placeholder="" value="" />
                </div>
            </div>
            <div class="overflow-x-auto mt-5">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th
                                class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Id</th>
                            <th
                                class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Fecha Emi</th>
                            <th
                                class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                T.Doc</th>
                            <th
                                class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Cod</th>
                            <th
                                class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Entidad</th>
                            <th
                                class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Serie</th>
                            <th
                                class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Numero</th>
                            <th
                                class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Moneda</th>
                            <th
                                class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                tasa</th>
                            <th
                                class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                precio</th>
                            <th
                                class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                usuario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Aquí puedes agregar filas de datos -->
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</div>
