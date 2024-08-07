<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Registro de Documentos de Egreso
        </h2>
    </x-slot>

    <div class="p-4">
        <x-card title="Registro de Documentos">
            <div class="flex flex-wrap -mx-2">
                <div class="w-full flex justify-around flex-wrap -mx-2 mt-4 px-2">
                    <div class="w-full md:w-3/12 px-2">
                        <x-select readonly label="Familia:" placeholder="Selecciona..." :options="[]" option-label="descripcion" option-value="id" />
                    </div>
                    <div class="w-full md:w-3/12 px-2">
                        <x-select readonly label="Sub-Familia:" placeholder="Selecciona..." :options="[]" option-label="descripcion" option-value="id" />
                    </div>
                    <div class="w-full md:w-3/12 px-2">
                        <x-select readonly label="Detalle:" placeholder="Selecciona..." :options="[]" option-label="descripcion" option-value="id" />
                    </div>
                </div>
                <div class="w-full md:w-6/12 px-2">
                    <div class="flex flex-wrap -mx-2 mt-4">
                        <div class="w-full md:w-2/12 px-2">
                            <x-maskable readonly label="T. Doc:" mask="#" value="" />
                        </div>
                        <div class="w-full md:w-4/12 px-2">
                            <x-input readonly label="Recibo por Honorarios" value="" />
                        </div>
                        <div class="w-full md:w-4/12 px-2">
                            <div class="flex items-center">
                                <x-input readonly label="Serie" value="" />
                                <span class="mx-2">-</span>
                                <x-input readonly label="Numero" value="" />
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-2 mt-4">
                        <div class="w-full md:w-2/12 px-2">
                            <x-select readonly label="Tip Doc Iden:" :options="[]" option-label="descripcion" option-value="id" />
                        </div>
                        <div class="w-full md:w-4/12 px-2">
                            <x-input readonly label="RUC:" value="" />
                        </div>
                        <div class="w-full md:w-2/12 px-2">
                            <x-select readonly label="Moneda:" :options="[]" option-label="descripcion" option-value="id" />
                        </div>
                        <div class="w-full md:w-4/12 px-2">
                            <x-input readonly label="Entidad:" value="" />
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-2 mt-4">
                        <div class="w-full md:w-2/12 px-2">
                            <x-select readonly label="Tasa Impositiva:" :options="[]" option-label="descripcion" option-value="id" />
                        </div>
                        <div class="w-full md:w-3/12 px-2">
                            <x-datetime-picker readonly label="Fec Emi:" placeholder="Nueva Fecha" without-time value="" />
                        </div>
                        <div class="w-full md:w-3/12 px-2">
                            <x-datetime-picker readonly label="Fec Ven:" placeholder="Nueva Fecha" without-time value="" />
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-6/12 px-2">
                    <div class="flex flex-wrap -mx-2 mt-4">
                        <fieldset class="border border-gray-300 p-2 rounded-md w-full">
                            <legend class="text-sm font-medium text-gray-700">T. Referencia</legend>
                            <div class="flex flex-wrap">
                                <div class="w-full md:w-6/12 px-2">
                                    <x-input readonly label="T. Doc:" value="" />
                                </div>
                                <div class="w-full md:w-6/12 px-2">
                                    <x-input readonly label="Orden Numero:" value="" />
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap -mx-2 mt-4">
                <div class="w-full px-2">
                    <x-input readonly label="Observaciones:" value="" />
                </div>
            </div>
            <div class="flex flex-wrap justify-end -mx-2 mt-4">
                <div class="w-full md:w-3/12 px-2">
                    <div class="flex flex-col space-y-2">
                        <x-input readonly label="Base Imponible:" value="" />
                        <x-input readonly label="IGV:" value="" />
                        <x-input readonly label="Otros Tributos:" value="" />
                        <x-input readonly label="No Gravado:" value="" />
                        <x-input readonly label="Precio:" value="" />
                    </div>
                </div>
            </div>
            <div class="flex justify-end mt-4 space-x-2">
                @livewire('cuadro-de-ordenes-modal')
                <x-button label="Cancelar" wire:navigate outline secondary href="{{ route('apertura.edit', ['aperturaId' => $aperturaId]) }}" />
                <x-button label="Aceptar" primary />
            </div>
        </x-card>
    </div>
</div>
