<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Registro de Documentos de Ingreso
        </h2>
    </x-slot>
    <div class="p-4">
        <x-card title="Registro de Documentos">
            <!-- Primera fila -->
            <div class="flex flex-wrap -mx-2 mt-4">
                <div class="w-full md:w-2/12 px-2">
                    <x-input label="T. Doc :" value="01" />
                </div>
                <div class="w-full md:w-4/12 px-2">
                    <x-input label="Factura" />
                </div>
                <div class="w-full md:w-4/12 px-2">
                    <div class="flex items-center">
                        <x-input label="Serie" value="E001" />
                        <span class="mx-2">-</span>
                        <x-input label="Numero" value="51" />
                    </div>
                </div>
            </div>

            <!-- Segunda fila -->
            <div class="flex flex-wrap -mx-2 mt-4">
                <div class="w-full md:w-2/12 px-2">
                    <x-select label="Tip Doc Iden:" :options="[]" option-label="descripcion" option-value="id" />
                </div>
                <div class="w-full md:w-4/12 px-2">
                    <x-input label="RUC:" value="10295782248" />
                </div>
                <div class="w-full md:w-2/12 px-2">
                    <x-select label="Moneda:" :options="[]" option-label="descripcion" option-value="id" />
                </div>
            </div>

            <!-- Tercera fila -->
            <div class="flex flex-wrap -mx-2 mt-4">
                <div class="w-full md:w-8/12 px-2">
                    <x-input label="Entidad:" value="SALAS BRICEÑO MARTHA PILAR" />
                </div>
                <div class="w-full md:w-4/12 px-2">
                    <x-select label="Tasa Impositiva:" :options="[]" option-label="descripcion" option-value="id" />
                </div>
            </div>

            <!-- Cuarta fila -->
            <div class="flex flex-wrap -mx-2 mt-4">
                <div class="w-full md:w-2/12 px-2">
                    <x-datetime-picker label="Fec Emi:" value="08/08/2023" without-time />
                </div>
                <div class="w-full md:w-2/12 px-2">
                    <x-datetime-picker label="Fec Ven:" value="08/08/2023" without-time />
                </div>
                <div class="w-full md:w-2/12 px-2">
                    <x-input label="Ref de ventas:" value="2" />
                </div>
                <div class="w-full md:w-6/12 px-2">
                    <fieldset class="border border-gray-300 p-2 rounded-md">
                        <legend class="text-sm font-medium text-gray-700">Referencias:</legend>
                        <div class="flex flex-wrap">
                            <x-input label="Id" value="26" />
                            <x-input label="Documento" value="Orden de Compra" />
                            <x-input label="Serie" value="OC01" />
                            <x-input label="Numero" value="22" />
                        </div>
                        <div class="flex flex-wrap mt-2">
                            <x-input label="Id" value="28" />
                            <x-input label="Documento" value="Sub Orden de Compra" />
                            <x-input label="Serie" value="SOC1" />
                            <x-input label="Numero" value="3" />
                        </div>
                    </fieldset>
                </div>
            </div>

            <!-- Productos -->
            <div class="mt-4">
                <h3 class="text-sm font-medium text-gray-700">Productos:</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th
                                    class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Código</th>
                                <th
                                    class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Descripción</th>
                                <th
                                    class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Tasa</th>
                                <th
                                    class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Cantidad</th>
                                <th
                                    class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    C/U</th>
                                <th
                                    class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="py-2 px-4 border-b border-gray-200">102000001</td>
                                <td class="py-2 px-4 border-b border-gray-200">EXTINTORES</td>
                                <td class="py-2 px-4 border-b border-gray-200">Sí</td>
                                <td class="py-2 px-4 border-b border-gray-200">1</td>
                                <td class="py-2 px-4 border-b border-gray-200">45.5</td>
                                <td class="py-2 px-4 border-b border-gray-200">45.5</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Observaciones y Totales -->
            <div class="flex flex-wrap -mx-2 mt-4">
                <div class="w-full md:w-12/12 px-2">
                    <x-input label="Observaciones:" value="" />
                </div>
            </div>
            <div class="flex flex-wrap gap-2 justify-end">
                <div class="flex flex-wrap w-full md:w-6/12 -mx-2 mt-4">
                    <div class="w-full md:w-4/12 px-2">
                        <x-input label="Total:" value="45.5" readonly />
                    </div>
                    <div class="w-full md:w-4/12 px-2">
                        <x-input label="Descuentos:" value="0" />
                    </div>
                    <div class="w-full md:w-4/12 px-2">
                        <x-input label="Recargos:" value="0" />
                    </div>
                    <div class="w-full md:w-4/12 px-2">
                        <x-input label="Total:" value="45.5" readonly />
                    </div>
                    <div class="w-full md:w-4/12 px-2">
                        <x-input label="Descuentos:" value="0" />
                    </div>
                    <div class="w-full md:w-4/12 px-2">
                        <x-input label="Recargos:" value="0" />
                    </div>

                </div>
                <div class="flex flex-col space-y-4 mt-4 w-full md:w-2/12">
                    <div class="w-full px-2">
                        <x-input label="Base Imponible:" value="45.5" readonly />
                    </div>
                    <div class="w-full px-2">
                        <x-input label="Igv:" value="8.19" readonly />
                    </div>
                    <div class="w-full px-2">
                        <x-input label="Otros Tributos:" value="0" />
                    </div>
                    <div class="w-full px-2">
                        <x-input label="No Gravado:" value="0" />
                    </div>
                    <div class="w-full px-2">
                        <x-input label="Precio:" value="53.69" readonly />
                    </div>
                </div>
            </div>


            <div class="flex flex-wrap -mx-2 mt-4 justify-between">
                <!-- Detracción y Botones -->
                <div class="flex flex-wrap -mx-2 mt-4 ">
                    <div class="w-full md:w-3/12 px-2 flex justify-end items-center">
                        <x-checkbox id="left-label" label="Detracción" />
                    </div>
                    <div class="w-full md:w-3/12 px-2">
                        <x-input label="porcentaje" suffix="%" value="" />
                    </div>
                    <div class="w-full md:w-3/12 px-2">
                        <x-input label="Monto de detracción:" value="" />
                    </div>
                    <div class="w-full md:w-3/12 px-2">
                        <x-input label="Monto Neto:" value="" />
                    </div>
                </div>

                <div class="flex items-center  mt-4 space-x-2">
                    <div>
                        <x-button label="Imprimir" />
                    </div>
                    <div> <x-button label="Eliminar" /></div>
                    <div><x-button label="Editar" /></div>
                    <div> <x-button label="Cancelar" /></div>
                    <div> <x-button label="Aceptar" primary /></div>





                </div>
            </div>


        </x-card>
    </div>

</div>
