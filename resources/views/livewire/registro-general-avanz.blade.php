<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Registro de Documentos avanzado de {{ $origen }}
        </h2>
    </x-slot>
    <div class="p-4">
        <x-card>
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
            <div class="flex flex-wrap   -mx-2 mt-4">
                <div class="w-full md:w-2/12 px-2">
                    <x-input label="T. Doc:" wire:model.live="tipoDocumento"
                        wire:keydown.enter="buscarDescripcionTipoDocumento"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 2)" />
                </div>
                <div class="w-full md:w-4/12 px-2">
                    <x-input label="Descripción T. Doc:" :disabled=True wire:model.live="tipoDocDescripcion" />
                </div>
                <div class="w-full md:w-4/12 px-2">
                    <div class="flex gap-3 items-center">
                        <x-input label="Serie" wire:model.live="serieNumero1"
                            oninput="this.value = this.value.toUpperCase()" maxlength="4" />

                        <x-input label="Numero" wire:model.live="serieNumero2"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" />
                    </div>
                </div>
            </div>

            <!-- Segunda fila -->
            <div class="flex flex-wrap  -mx-2 mt-4">
                <div class="w-full md:w-2/12 px-2">
                    <x-select label="Tip Doc Iden:" wire:model.live="tipoDocId" :options="$tipoDocIdentidades"
                        option-label="abreviado" option-value="id" />
                </div>
                <div class="w-full md:w-4/12 px-2">
                    <x-input label="Num Ident:" wire:model.live="docIdent" wire:keydown.enter="EnterRuc"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, {{ $lenIdenId }})" />
                </div>
                <div class="w-full md:w-4/12 px-2">
                    <x-input label="Entidad:" wire:model.live='entidad' :disabled=True />
                </div>

            </div>

            <!-- Tercera fila -->
            <div class="flex flex-wrap gap-3   -mx-2 my-4">
                <div class="w-full md:w-2/12 px-2">
                    <x-select label="Moneda:" wire:model.live="monedaId" :options="$monedas" option-label="id"
                        option-value="id" />
                </div>
                <div class="w-full md:w-2/12 px-2">
                    <x-select label="Tasa Impositiva:" wire:model.live="tasaIgvId" :options="$tasasIgv" option-label="tasa"
                        placeholder="Selecc." option-value="tasa" />
                </div>
                <div class="w-full md:w-2/12 px-2">
                    <label for="">Fecha Emision</label>
                    <input wire:model="fechaEmi" type="date"
                        class="block w-full pl-4 pr-10 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm text-gray-600 disabled:bg-gray-100 disabled:text-gray-400">
                </div>
                <div class="w-full md:w-2/12 px-2">
                    <label for="fecha_ven">Fecha Ven</label>
                    <input wire:model="fechaVen" type="date"
                        class="block w-full pl-4 pr-10 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm text-gray-600 disabled:bg-gray-100 disabled:text-gray-400">


                </div>
            </div>



            <!-- Productos -->
            <div class="mt-10">
                <div class="flex items-center gap-4 my-5">
                    <h3 class="text-sm font-bold text-gray-700">Productos:</h3>
                    @if (!empty($productos))
                        <div>
                            @livewire('modal-producto-general-avanz')
                        </div>
                    @endif
                </div>

                <!-- Verificar si la lista de productos está vacía -->
                @if (empty($productos))
                    <div
                        class="flex flex-col items-center justify-center h-40 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 10h11M9 21V3m0 18l-4-4m4 4l4-4" />
                        </svg>
                        <p class="mt-2 text-gray-600 mb-3">No hay productos añadidos.</p>
                        @livewire('modal-producto-general-avanz')
                    </div>
                @else
                    <!-- Mostrar la tabla si hay productos -->
                    <div class="overflow-x-auto mt-4">
                        <table class="min-w-full bg-white border border-gray-300 rounded-md">
                            <thead>
                                <tr>
                                    <th
                                        class="py-2 px-4 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Código
                                    </th>
                                    <th
                                        class="py-2 px-4 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Descripción
                                    </th>
                                    <th
                                        class="py-2 px-4 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Tasa
                                    </th>
                                    <th
                                        class="py-2 px-4 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Cantidad
                                    </th>
                                    <th
                                        class="py-2 px-4 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        C/U
                                    </th>
                                    <th
                                        class="py-2 px-4 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Total
                                    </th>
                                    <th
                                        class="py-2 px-4 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Centro de Costos
                                    </th>
                                    <th
                                        class="py-2 px-4 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Accion
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productos as $index => $producto)
                                    <tr>
                                        <td class="py-2 px-4 border-b border-gray-200">{{ $producto['codigoProducto'] }}
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-200">
                                            {{ $producto['productoSeleccionado'] }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200">
                                            {{ $producto['tasaImpositiva'] ? 'Sí' : 'No' }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200">{{ $producto['cantidad'] }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200">{{ $producto['precioUnitario'] }}
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-200">{{ $producto['total'] }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200">{{ $producto['CC'] }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200">
                                            <x-button label="Eliminar" negative
                                                wire:click="eliminarProducto({{ $index }})" />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Observaciones y Totales -->
            <div class="flex flex-wrap -mx-2 mt-4">
                <div class="w-full   px-2">
                    <x-input wire:model.live='observaciones' label="Observaciones:"
                        oninput="this.value = this.value.toUpperCase()" />
                </div>
                @if ($origen === 'ingreso' || $origen === 'egreso' || $origen === 'editar ingreso' || $origen === 'editar egreso')
                    <div class="w-full md:w-2/12 mt-4  px-2">
                        <x-input wire:model.live='cod_operacion' label="Codigo de operacion"
                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1').slice(0, 10)"/>
                    </div>
                @endif
                <div class="w-full md:w-2/12 mt-4  px-2">
                    <x-select 
                        label="Cuenta:" 
                        placeholder="Seleccione un cuenta"
                        :options="$cuentas" 
                        option-label="descripcion" 
                        option-value="id" 
                        wire:model.live="cuenta"  
                    />
                </div>
            </div>
            <div class="flex flex-wrap gap-2 justify-end">

                <div class="flex flex-col space-y-4 mt-4 w-full md:w-2/12">
                    <div class="w-full px-2">
                        <x-input label="Base Imponible:" wire:model.live="basImp"
                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1').slice(0, 10)" />
                    </div>
                    <div class="w-full px-2">
                        <x-input label="IGV:" wire:model.live='igv'
                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1').slice(0, 10)" />
                    </div>
                    <div class="w-full px-2">
                        <x-input label="Otros Tributos:" wire:model.live='otrosTributos'
                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1').slice(0, 10)" />
                    </div>
                    <div class="w-full px-2">
                        <x-input label="No Gravado:" wire:model.live='noGravado'
                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1').slice(0, 10)" />
                    </div>
                    <div class="w-full px-2">
                        <x-input wire:model.live='precio' label="Precio:" value="53.69" readonly />
                    </div>
                </div>
            </div>


            <div class="flex flex-wrap -mx-2 mt-4 justify-between">
                <!-- Detracción y Botones -->
                @if ($origen === 'cxp' || $origen === 'cxc' || $origen === 'editar cxp' || $origen === 'editar cxc')
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
                            <x-input class="w-1/12" wire:model.live="montoDetraccion" id="monto_detraccion"
                                class="w-32" :disabled="!$toggle" />
                        </div>

                        <div class="flex items-center">
                            <label for="monto_neto" class="text-sm font-medium text-gray-700">Monto Neto:</label>
                            <x-input class="w-1/12" wire:model.live="montoNeto" id="monto_neto" class="w-32"
                                :disabled=True />
                        </div>
                    </div>
                @endif
                </div>
            


                <div class="flex items-center  mt-4 space-x-2">

                    @if ($origen === 'ingreso' || $origen === 'egreso')
                    <!-- Botón para Ingreso y Egreso, que dependen de aperturaId -->
                    <div> 
                        <x-button 
                            label="Cancelar" 
                            wire:navigate 
                            outline 
                            secondary
                            href="{{ route('apertura.edit', ['aperturaId' => $aperturaId]) }}" 
                        />
                    </div>
                @elseif ($origen === 'cxc')
                    <!-- Botón para CXC sin aperturaId -->
                    <div> 
                        <x-button 
                            label="Cancelar" 
                            wire:navigate 
                            outline 
                            secondary
                            href="{{ route('cxc') }}" 
                        />
                    </div>
                @elseif ($origen === 'cxp')
                    <!-- Botón para CXP sin aperturaId -->
                    <div> 
                        <x-button 
                            label="Cancelar" 
                            wire:navigate 
                            outline 
                            secondary
                            href="{{ route('cxp') }}" 
                        />
                    </div>
                @endif
                
                    <div> <x-button label="Aceptar" wire:click='submit' primary /></div>






                </div>
            </div>


        </x-card>
    </div>

</div>
