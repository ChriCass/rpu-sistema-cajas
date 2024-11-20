<div>
    @if ($visible)
        <div class="flex justify-center mb-4">
            <x-button warning wire:click="$set('visible', false)">
                Cancelar
            </x-button>
        </div>

        <div class="p-4">
            <x-card title="Edicion de Documentos cxc">
                @if (session()->has('message'))
                    <x-alert title="Felicidades!" positive class="mb-3">
                        {{ session('message') }}
                    </x-alert>
                @elseif (session()->has('warning'))
                    <x-alert title="Advertencia!" warning class="mb-3">
                        {{ session('warning') }}
                    </x-alert>
                @elseif (session()->has('error'))
                    <x-alert title="Error!" negative class="mb-3">
                        {{ session('error') }}
                    </x-alert>
                @endif
                <div class="flex flex-wrap -mx-2">
                    <div class="mx-5">
                        <button type="button" disabled
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                            Avanzado
                        </button>
                    </div>


                    <div class="w-full flex justify-around flex-wrap -mx-2 mt-4 px-2">
                        <!-- Select Familia -->
                        <div class="w-full md:w-3/12 px-2">
                            <x-select label="Familia:" placeholder="Selecciona..." wire:model.live="familiaId"
                                :options="$familias" option-label="descripcion" option-value="id" />
                        </div>

                        <!-- Select Sub-Familia -->
                        <div class="w-full md:w-3/12 px-2">
                            <x-select label="Sub-Familia:" placeholder="Selecciona..." wire:model.live="subfamiliaId"
                                :options="$subfamilias" option-label="desripcion" option-value="ic" :disabled="$disableFields" />
                        </div>

                        <!-- Select Detalle -->
                        <div class="w-full md:w-3/12 px-2">
                            <x-select label="Detalle:" placeholder="Selecciona..." wire:model.live="detalleId"
                                :options="$detalles" option-label="descripcion" option-value="id" :disabled="$disableFields" />
                        </div>


                    </div>


                    <div class="w-full md:w-6/12 px-2">

                        <div class="flex flex-wrap -mx-2 mt-4">
                            <div class="w-full md:w-2/12 px-2">
                                <x-input label="T. Doc:" :disabled="$disableFields" wire:model.live="tipoDocumento"
                                    wire:keydown.enter="buscarDescripcionTipoDocumento"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 2)" />
                            </div>

                            <div class="w-full md:w-4/12 px-2">
                                <x-input label="Descripción T. Doc:" :disabled=True
                                    wire:model.live="tipoDocDescripcion" />
                            </div>

                            <div class="w-full md:w-4/12 px-2">
                                <div class="flex items-center">
                                    <x-input label="Serie" :disabled="$disableFields" wire:model.live="serieNumero1"
                                        oninput="this.value = this.value.toUpperCase()" maxlength="4" />
                                    <span class="mx-2"> </span>
                                    <x-input label="Numero" :disabled="$disableFields" wire:model.live="serieNumero2"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" />
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-wrap -mx-2 mt-4">
                            <div class="w-full md:w-3/12 px-2">
                                <x-select label="Tip Doc Iden:" wire:model.live="tipoDocId" :options="$tipoDocIdentidades"
                                    option-label="abreviado" option-value="id" :disabled="$disableFieldsEspecial" />
                            </div>
                            <div class="w-full md:w-4/12 px-2">
                                <x-input :disabled="$disableFieldsEspecial" label="Num Ident:" wire:model.live="docIdent"
                                    wire:keydown.enter="EnterRuc"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, {{ $lenIdenId }})" />
                            </div>
                            <div class="w-full md:w-3/12 px-2">
                                <x-select label="Moneda:" wire:model.live="monedaId" :options="$monedas"
                                    option-label="id" option-value="id" :disabled="$disableFields" />
                            </div>

                        </div>

                        <div class="flex flex-wrap justify-between -mx-2 mt-4">
                            <div class="w-full md:w-8/12 px-2">
                                <x-input label="Entidad:" wire:model.live='entidad' :disabled=True />
                            </div>
                            <div class="w-full md:w-4/12 px-2">
                                <x-select label="Tasa Impositiva:" wire:model.live="tasaIgvId" :options="$tasasIgv"
                                    option-label="tasa" placeholder="Selecc." option-value="tasa" :disabled="$disableFields" />
                            </div>
                        </div>
                        <div class="flex flex-wrap justify-between -mx-2 mt-4">

                            <div class="w-full md:w-4/12 px-2">
                                <label for="">Fecha Emision</label>
                                <input wire:model="fechaEmi" @if ($disableFields) disabled @endif
                                    type="date"
                                    class="block w-full pl-4 pr-10 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm text-gray-600 disabled:bg-gray-100 disabled:text-gray-400">

                            </div>
                            <div class="w-full md:w-4/12 px-2">
                                <label for="fecha_ven">Fecha Ven</label>
                                <input wire:model="fechaVen" type="date"
                                    class="block w-full pl-4 pr-10 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm text-gray-600 disabled:bg-gray-100 disabled:text-gray-400"
                                    @if ($disableFields) disabled @endif>


                            </div>
                        </div>
                    </div>
                    <div class="w-full md:w-5/12 px-2">
                        <div class="flex flex-wrap -mx-2 mt-4">
                            <fieldset class="border border-gray-300 p-10 rounded-md w-full">
                                <legend class="text-sm font-medium text-gray-700">T. Referencia</legend>
                                <div class="flex flex-wrap">
                                    <div class="w-full md:w-7/12 px-2">
                                        <x-input label="T. Doc:" value="" />
                                    </div>
                                    <div class="w-full md:w-5/12 px-2 flex gap-3">
                                        <x-input label="serie:" value="" />
                                        <x-input label="Numero:" value="" />
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap -mx-2 mt-4">
                    <div class="w-full px-2">
                        <x-input wire:model.live='observaciones' label="Observaciones:"
                            oninput="this.value = this.value.toUpperCase()" />
                    </div>
                    <div class="w-full md:w-2/12 mt-4  px-2">
                        <x-input wire:model.live='cod_operacion' label="Codigo de operacion"
                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1').slice(0, 10)"/>
                    </div>
                </div>
                <div class="flex flex-wrap justify-between -mx-2 mt-4">
                    <div class="w-full md:w-3/12 px-2">
                        <!-- Abelardo (cree ese select para los centros de costos) -->
                        <div class="w-full md:w-6/12 px-2">
                            <x-select wire:model.live='centroDeCostos' placeholder="selecc." :options="$CC"
                                option-value="id" option-label="descripcion" label="C.C.:" />
                        </div>
                        <!-- Destinatario (solo visible si aplica) -->
                        @if ($destinatarioVisible)
                            <div class="w-full md:w-6/12 px-2">
                                <x-select wire:model.live='nuevoDestinatario' placeholder="selecc." :options="$destinatarios"
                                    option-value="id" option-label="descripcion" label="Destinatario:" />
                            </div>
                        @endif
                        <div>
                            <legend class="text-sm font-medium text-gray-700">{{ $PruebaArray }}</legend>
                        </div>
                    </div>
                    <div class="w-full md:w-3/12 px-2">
                        <div class="flex flex-col space-y-2">
                            <x-input label="Base Imponible:" wire:model.live="basImp"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1').slice(0, 10)" />
                            <x-input label="IGV:" wire:model.live='igv'
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1').slice(0, 10)" />
                            <x-input label="Otros Tributos:" wire:model.live='otrosTributos'
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1').slice(0, 10)" />
                            <x-input label="No Gravado:" wire:model.live='noGravado'
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1').slice(0, 10)" />
                            <x-input readonly wire:model.live='precio' label="Precio:" />
                        </div>
                    </div>
                </div>
                <div class="flex justify-between mt-4 space-x-2 gap-3">
                    <div class="flex justify-start ">
                        <!-- Detracción -->
                        <div class="flex items-center justify-start gap-3 mx-3 w-3/12">
                            <x-toggle wire:model.live="toggle" left-label="tiene porcentaje?" name="toggle" />
                            <div class="w-5/12">
                                <x-maskable mask="###" :disabled="!$toggle" wire:model.live="porcentaje"
                                    suffix="%" />
                            </div>
                        </div>

                        <div class="flex mx-3">
                            <label for="monto_detraccion" class="text-sm font-medium text-gray-700">Monto de
                                detracción:</label>
                            <x-input class="w-1/12" wire:model.live="montoDetraccion" id="montoDetraccion"
                                class="w-32" :disabled="!$toggle" />
                        </div>

                        <div class="flex items-center">
                            <label for="monto_neto" class="text-sm font-medium text-gray-700">Monto Neto:</label>
                            <x-input class="w-1/12" wire:model.live="montoNeto" id="monto_Neto" class="w-32"
                                :disabled=True />
                        </div>
                    </div>


                    <div class="flex gap-3">
                        <div>
                            @livewire('cuadro-de-ordenes-modal')
                        </div>
                        <div>
                            @livewire('delete-cxc-modal', ['idcxc' => $this->idcxc])
                        </div>
                        <div> <x-button label="Aceptar" wire:click='submit' primary /></div>



                    </div>
                </div>
            </x-card>
        </div>
    @endif
</div>
