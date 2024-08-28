<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Formulario de Registro CXP
        </h2>
    </x-slot>

    <div class="p-4">
        <x-card title="CuadroDeDocumentos">

            <div class="flex flex-wrap -mx-2">
                <div class="w-full flex justify-around flex-wrap -mx-2 mt-4 px-2">
                    <div class="w-full md:w-3/12 px-2">
                        <x-select 
                            wire:model.live="selectedFamilia" 
                            placeholder="Seleccione Familia" 
                            label="Familia:" 
                            :options="$familias" 
                            option-value="id" 
                            option-label="descripcion" 
                        />
                    </div>
                    <div class="w-full md:w-3/12 px-2">
                        <x-select 
                            wire:model.live="selectedSubfamilia" 
                            placeholder="Seleccione Subfamilia" 
                            label="Subfamilia:" 
                            :options="$subfamilias" 
                            option-value="id" 
                            option-label="desripcion" 
                            :disabled="!$selectedFamilia"
                        />
                    </div>
                    <div class="w-full md:w-3/12 px-2">
                        <x-select 
                            wire:model.live="selectedDetalle" 
                            placeholder="Seleccione Detalle" 
                            label="Detalle:" 
                            :options="$detalles" 
                            option-value="id" 
                            option-label="descripcion" 
                            :disabled="!$selectedSubfamilia"
                        />
                    </div>
                </div>
                <div class="w-full md:w-6/12 px-2">

                    <div class="flex flex-wrap -mx-2 mt-4">
                        <div class="w-full md:w-2/12 px-2">
                            <x-maskable wire:model.live="tipoDocumento" label="T. Doc:" mask="#"  />
                        </div>
                        <div class="w-full md:w-4/12 px-2">
                            <x-input wire:model.live="numeroDocumento" label="# doc" />
                        </div>
                        <div class="w-full md:w-4/12 px-2">
                            <div class="flex items-center">
                                <x-input wire:model.live="serie" label="Serie" />
                                <span class="mx-2">-</span>
                                <x-input wire:model.live="numero" label="Número" />
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-2 mt-4">
                        <div class="w-full md:w-2/12 px-2">
                            <x-select wire:model.live="selectedTipoDocumento" placeholder="selecc." label="Tip Doc Iden:" :options="$documentos" option-value="id" option-label="abreviado" />
                        </div>
                        <div class="w-full md:w-4/12 px-2">
                            <label>{{ $labelDoc }}:</label> 
                            <x-input wire:model.live="documentoIdentidad" />
                        </div>
                        <div class="w-full md:w-2/12 px-2">
                            <x-select wire:model.live="selectedMoneda" placeholder="selecc." label="Moneda:" :options="$monedas" option-value="id" option-label="id"/>
                        </div>
                    
                    </div>

                    <div class="flex flex-wrap justify-between -mx-2 mt-4">
                        <div class="w-full md:w-8/12 px-2">
                            <x-input wire:model.live="entidad" label="Entidad:" />
                        </div>
                        <div class="w-full md:w-4/12 px-2">
                            <x-select 
                                wire:model.live="selectedTasaIgv"
                                placeholder="selecc." 
                                label="Tasa Impositiva:" 
                                :options="$igvs" 
                                option-value="id" 
                                option-label="tasa"
                            />
                        </div>
                        
                    </div>
                    <div class="flex flex-wrap justify-between -mx-2 mt-4">
                   
                        <div class="w-full md:w-3/12 px-2">
                            <x-datetime-picker wire:model.live="fechaEmi" label="Fec Emi:" placeholder="Nueva Fecha" without-time />
                        </div>
                        <div class="w-full md:w-3/12 px-2">
                            <x-datetime-picker wire:model.live="fechaVen" label="Fec Ven:" placeholder="Nueva Fecha" without-time />
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-6/12 px-2">
                    <div class="flex flex-wrap -mx-2 mt-4">
                        <fieldset class="border p-10 border-gray-300 p-2 rounded-md w-full">
                            <legend class="text-sm font-medium text-gray-700">T. Referencia</legend>
                            <div class="flex flex-wrap">
                                <div class="w-full md:w-6/12 px-2">
                                    <x-input   readonly placeholder="Seleccione Tipo de Documento" label="T. Doc:"  />
                                </div>
                                <div class="w-full md:w-6/12 px-2">
                                    <x-input   readonly label="Orden Número:" />
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap -mx-2 mt-4">
                <div class="w-full px-2">
                    <x-input wire:model.live="observaciones" label="Observaciones:" />
                </div>
            </div>
            <div class="flex flex-wrap justify-end -mx-2 mt-4">
                <div class="w-full md:w-3/12 px-2 border">
                    @if(empty($data))
                        <p>Nada que mostrar</p>
                    @else 
                        <p>Mostrando:</p>
                        <pre>{{ print_r($data, true) }}</pre>
                    @endif
                </div>
                
                <div class="w-full md:w-3/12 px-2">
                    <div class="flex flex-col space-y-2">
                        <!-- Base Imponible -->
                        <x-input 
                            label="Base Imponible:" 
                            wire:model.live="baseImponible" 
                        />
                    
                        <!-- IGV -->
                        <x-input 
                            label="IGV:" 
                            wire:model.live="igv"
                            :value="$igv !== null ? $igv : ''"
                        />
                    
                        <!-- Otros Tributos -->
                        <x-input 
                            label="Otros Tributos:" 
                            wire:model.live="otroTributo"
                        />
                    
                        <!-- No Gravado -->
                        <x-input 
                            label="No Gravado:" 
                            wire:model.live="noGravadas"
                        />
                    
                        <!-- Precio (Total) -->
                        <x-input 
                            label="Precio:" 
                            wire:model.live="total"
                            :value="$total !== null ? $total : ''"
                        />
                    </div>
                    
                </div>
            </div>

            <div class="flex justify-between mt-4 space-x-2">
                <div class="flex flex-wrap  ">
                    <div class="w-full md:w-3/12 flex flex-col justify-center items-center">
                        <x-checkbox id="left-label" left-label="Detracción" wire:model="model1" value="left-label" />
                    </div>
                    
                    <div class="w-full md:w-2/12">
                        <x-input wire:model.live="porcentajeDetraccion" label="Porcentaje" suffix="%" />
                    </div>
                    <div class="w-full md:w-3/12 mx-5">
                        <x-input wire:model.live="montoDetraccion" readonly label="Monto de detracción" />
                    </div>
                    <div class="w-full md:w-2/12">
                        <x-input wire:model.live="montoNeto" readonly label="Monto neto" />
                    </div>
                </div>
                
                <div class="flex items-center space-x-2">
                    @livewire('cuadro-de-ordenes-modal')
                    <div><x-button label="Cancelar" outline secondary wire:navigate href="{{ route('apertura.edit.vaucherdepagos.registrocxp', ['aperturaId' => $aperturaId]) }}"   /></div>
                    <div>
                        <!-- Botón que dispara la función para guardar el documento -->
                        <x-button label="Aceptar" primary class="flex-none" wire:click="save" />
                    </div>
                    
                </div>
                

            </div>

        </x-card>
    </div>
</div>
