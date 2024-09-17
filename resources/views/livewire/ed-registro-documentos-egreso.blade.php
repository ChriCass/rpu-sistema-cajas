<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edicion de Documentos de Egreso
        </h2>
    </x-slot>

    <div class="p-4">
        <x-card title="Edicion de Documentos Egreso">
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
            <div class="flex flex-wrap -mx-2">
                <div class="mx-5">
                    <button 
                        type="button" 
                        disabled
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
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
                            :options="$subfamilias" option-label="desripcion" option-value="id" :disabled="$disableFields" />
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
                            <x-input
                                label="T. Doc:"
                                :disabled="$disableFields"
                                wire:model.live="tipoDocumento"
                                wire:keydown.enter="buscarDescripcionTipoDocumento"
                            />
                        </div>
                        
                        <div class="w-full md:w-4/12 px-2">
                            <x-input
                                label="Descripción T. Doc:"
                                :disabled="$disableFields"
                                wire:model.live="tipoDocDescripcion"

                                readonly
                            />
                        </div>
                        
                        <div class="w-full md:w-4/12 px-2">
                            <div class="flex items-center">
                                <x-input label="Serie" :disabled="$disableFields" wire:model.live="serieNumero1" />
                                <span class="mx-2">-</span>
                                <x-input   label="Numero" :disabled="$disableFields" wire:model.live="serieNumero2" />

                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-2 mt-4">
                        <div class="w-full md:w-2/12 px-2">
                            <x-select label="Tip Doc Iden:" wire:model.live="tipoDocId" :options="$tipoDocIdentidades"
                                option-label="abreviado" option-value="id" :disabled="$disableFields" />
                        </div>
                        <div class="w-full md:w-4/12 px-2">
                            <x-input :disabled="$disableFields" label="RUC:" wire:model.live="docIdent" />
                        </div>
                        <div class="w-full md:w-2/12 px-2">
                            <x-select label="Moneda:" wire:model.live="monedaId" :options="$monedas" option-label="id"
                                option-value="id" :disabled="$disableFields" />
                        </div>

                    </div>

                    <div class="flex flex-wrap justify-between -mx-2 mt-4">
                        <div class="w-full md:w-8/12 px-2">
                            <x-input  label="Entidad:" wire:model.live='entidad' :disabled="$disableFields" />
                        </div>
                        <div class="w-full md:w-4/12 px-2">
                            <x-select label="Tasa Impositiva:" wire:model.live="tasaIgvId" :options="$tasasIgv"
                                option-label="tasa" placeholder="Selecc." option-value="tasa" :disabled="$disableFields" />
                        </div>
                    </div>
                    <div class="flex flex-wrap justify-between -mx-2 mt-4">

                        <div class="w-full md:w-3/12 px-2">
                            <label for="">Fecha Emision</label>
                            <input wire:model="fechaEmi" @if ($disableFields) disabled @endif
                                type="date"
                                class="block w-full pl-4 pr-10 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm text-gray-600 disabled:bg-gray-100 disabled:text-gray-400">

                        </div>
                        <div class="w-full md:w-3/12 px-2">
                            <label for="fecha_ven">Fecha Ven</label>
                            <input wire:model="fechaVen" type="date"
                                class="block w-full pl-4 pr-10 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm text-gray-600 disabled:bg-gray-100 disabled:text-gray-400"
                                @if ($disableFields) disabled @endif>


                        </div>
                    </div>
                </div>
                <div class="w-full md:w-6/12 px-2">
                    <div class="flex flex-wrap -mx-2 mt-4">
                        <fieldset class="border p-10 border-gray-300 p-2 rounded-md w-full">
                            <legend class="text-sm font-medium text-gray-700">T. Referencia</legend>
                            <div class="flex flex-wrap">
                                <div class="w-full md:w-6/12 px-2">
                                    <x-input   label="T. Doc:" value="" />
                                </div>
                                <div class="w-full md:w-6/12 px-2">
                                    <x-input   label="Orden Numero:" value="" />
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap -mx-2 mt-4">
                <div class="w-full px-2">
                    <x-input wire:model.live='observaciones'   label="Observaciones:" />
                </div>
            </div>
            <div class="flex flex-wrap justify-between -mx-2 mt-4">
                <div class="w-full md:w-3/12 px-2">
                     <!-- Destinatario (solo visible si aplica) -->
                     @if ($destinatarioVisible)
                     <div class="w-full md:w-6/12 px-2">
                         <x-select wire:model.live='nuevoDestinatario' placeholder="selecc." :options="$destinatarios" option-value="descripcion" option-label="descripcion" label="Destinatario:" />
                     </div>
                 @endif
                </div>
                <div class="w-full md:w-3/12 px-2">
                    <div class="flex flex-col space-y-2">
                        <x-input   label="Base Imponible:" wire:model.live="basImp"   />
                        <x-input   label="IGV:" wire:model.live='igv'/>
                        <x-input   label="Otros Tributos:"   />
                        <x-input   label="No Gravado:" wire:model.live='noGravado'  />
                        <x-input readonly  wire:model.live='precio' label="Precio:"   />
                    </div>
                </div>
            </div>
            <div class="flex justify-end mt-4 space-x-2">
                @livewire('cuadro-de-ordenes-modal')
                <x-button label="Cancelar" wire:navigate outline secondary
                    />
                <x-button label="Aceptar" wire:click='submit' primary />
            </div>
        </x-card>
    </div>
</div>
