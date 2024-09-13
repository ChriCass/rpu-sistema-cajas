<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Registro de Documentos de Ingreso
        </h2>
    </x-slot>

    <div class="p-4">
        <x-card title="Registro de Documentos">
            <div class="flex flex-wrap -mx-2">
                <div class="mx-5">
                    <x-button   label="avanzado" info wire:navigate     href="{{ route('apertura.edit.registodocumentosingreso.avanzado', ['aperturaId' => $aperturaId]) }}"></x-button>
                </div>
               
                <div class="w-full flex justify-around flex-wrap -mx-2 mt-4 px-2">
                    <!-- Select Familia -->
                    <div class="w-full md:w-3/12 px-2">
                        <x-select 
                            label="Familia:" 
                            placeholder="Selecciona..." 
                            wire:model.live="familiaId" 
                            :options="$familias" 
                            option-label="descripcion" 
                            option-value="id" 
                        />
                    </div>
                    
                    <!-- Select Sub-Familia -->
                    <div class="w-full md:w-3/12 px-2">
                        <x-select 
                            label="Sub-Familia:" 
                            placeholder="Selecciona..." 
                            wire:model.live="subfamiliaId" 
                            :options="$subfamilias" 
                            option-label="desripcion" 
                            option-value="id" 
                        />
                    </div>
                    
                    <!-- Select Detalle -->
                    <div class="w-full md:w-3/12 px-2">
                        <x-select 
                            label="Detalle:" 
                            placeholder="Selecciona..." 
                            wire:model.live="detalleId" 
                            :options="$detalles" 
                            option-label="descripcion" 
                            option-value="id" 
                        />
                    </div>
                </div>
                
                
                <div class="w-full md:w-6/12 px-2">

                    <div class="flex flex-wrap -mx-2 mt-4">
                        <div class="w-full md:w-2/12 px-2">
                            <!-- Abelardo = Cambie el Maskable por un Imput con el objetivo de que capte el enveto del teclado -->
                            <x-input label="T. Doc:" 
                                wire:model.live="TDocId" 
                                wire:keydown.enter="EnterTDocId"
                                type="text"
                                inputmode="numeric"
                                maxlength="2"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 2)"  
                                 />
                        </div>
                        <div class="w-full md:w-4/12 px-2">
                            <!-- Abelardo = Le paso la variable TDcocDesc para que se muestre el nombre del documento al presionar enter -->
                            <x-input readonly label="Descripcion de Documento" wire:model.live="TDocDesc" />
                            <!-- Abelardo = En base al numero de la validacion se muestra el mensaje de error -->
                            @if ($valTdoc == 1)
                                <x-alert title="EL codigo no es valido" negative style="padding: 1px; margin-top: 6px"/>
                            @endif
                        </div>
                        <div class="w-full md:w-4/12 px-2">
                            <div class="flex items-center">
                                <!-- Abelardo = Las series tienen que ser en mayuscula -->
                                <x-input  label="Serie"
                                        type="text"
                                        maxlength="4"
                                        oninput="this.value = this.value.toUpperCase()"
                                        />
                                <span class="mx-3"> </span>
                                <!-- Abelardo = Solo acepta numeros -->
                                <x-input  label="Numero" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" />
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-2 mt-4">
                        <div class="w-full md:w-2/12 px-2">
                            <!-- Abelardo = Aqui van los tipos de documentos de identidad -->
                            <x-select label="Tip Doc Iden:" :options="[['id' => '1', 'descripcion' => '1'],['id' => '6', 'descripcion' => '6']]" option-label="descripcion"
                                option-value="id" wire:model.live="docIdenId" />
                        </div>
                        <div class="w-full md:w-4/12 px-2">
                            <!-- Abelardo = Se tiene que validar el Ruc o DNI -->
                            <x-input label="{{ $descIdenId }}" wire:model.live="rucId" wire:keydown.enter="EnterRuc" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, {{$lenIdenId}})"/>
                        </div>
                        <div class="w-full md:w-2/12 px-2">
                            <!-- Abelardo = Se modifico las monedas -->
                            <x-select label="Moneda:"
                                placeholder="Selecciona..."
                                :options="[['id' => 'PEN', 'descripcion' => 'PEN'],['id' => 'USD', 'descripcion' => 'USD']]" 
                                option-label="descripcion"
                                option-value="id" />
                        </div>
                    
                    </div>

                    <div class="flex flex-wrap justify-between -mx-2 mt-4">
                        <div class="w-full md:w-8/12 px-2">
                            <!-- Abelardo = Validacion de errores en base al ruc -->
                            <x-input readonly label="Entidad:" wire:model.live="dosIdenDesc" />
                            @if ($valDocIden == 1)
                                <x-alert title="{{ $ErrorDocIden }}" negative style="padding: 1px; margin-top: 6px"/>
                            @endif
                        </div>
                        <div class="w-full md:w-4/12 px-2">
                            <!-- Abelardo = Se modifico los tipos gravados -->
                            <x-select 
                                placeholder="Selecciona..."
                                label="Tasa Impositiva:" 
                                :options="[['id' => 0, 'descripcion' => 'No Gravado'],['id' => 1, 'descripcion' => '18%'],['id' => 2, 'descripcion' => '10%']]" option-label="descripcion"
                                wire:model.live="idTipGrav"
                                option-value="id" />
                            @if ($valGrav == 1)
                                <x-alert title="Elige un Tip. Gravado" negative class=" mt-2"/>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-wrap justify-between -mx-2 mt-4">
                        <!-- Abelardo = Se activo las fecha -->
                        <div class="w-full md:w-3/12 px-2">
                            <x-datetime-picker label="Fec Emi:" placeholder="Nueva Fecha" without-time
                                />
                        </div>
                        <!-- Abelardo = Se activo las fecha -->
                        <div class="w-full md:w-3/12 px-2">
                            <x-datetime-picker label="Fec Ven:" placeholder="Nueva Fecha" without-time
                                 />
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-6/12 px-2">
                    <div class="flex flex-wrap -mx-2 mt-4">
                        <fieldset class="border p-10 border-gray-300 p-2 rounded-md w-full">
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
                    <!-- Abelardo = Se activo las Observaciones -->
                    <x-input label="Observaciones:" />
                </div>
            </div>
            <div class="flex flex-wrap justify-end -mx-2 mt-4">
                <div class="w-full md:w-3/12 px-2">
                    <div class="flex flex-col space-y-2">
                        <!-- Abelardo = Se activo los imputs -->
                        <x-input label="Base Imponible:" wire:model.live="BI" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1').slice(0, 10)" />
                        <x-input label="IGV:" wire:model.live="IGV" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1').slice(0, 10)" />
                        <x-input label="Otros Tributos:" wire:model.live="OtroTrib" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1').slice(0, 10)"/>
                        <x-input label="No Gravado:" wire:model.live="NoGravado" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1').slice(0, 10)"/>
                        <x-input label="Precio:" readonly wire:model.live="Precio" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1').slice(0, 10)"/>
                    </div>
                </div>
            </div>
            <div class="flex justify-end mt-4 space-x-2">
                @livewire('cuadro-de-ordenes-modal')
                <x-button label="Cancelar" wire:navigate outline secondary href="{{ route('apertura.edit', ['aperturaId' => $aperturaId]) }}" />
                <x-button label="Aceptar" wire:click="submit" primary />
            </div>
        </x-card>
    </div>
</div>
