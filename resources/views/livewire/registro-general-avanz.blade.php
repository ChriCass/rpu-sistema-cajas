<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Registro de Documentos avanzado de {{$origen}}
        </h2>
    </x-slot>
    <div class="p-4">
        <x-card  >
            <!-- Primera fila -->
            <div class="flex flex-wrap justify-center -mx-2 mt-4">
                <div class="w-full md:w-2/12 px-2">
                    <x-input label="T. Doc :" value="01" />
                </div>
                <div class="w-full md:w-4/12 px-2">
                    <x-input label="Factura" />
                </div>
                <div class="w-full md:w-4/12 px-2">
                    <div class="flex gap-3 items-center">
                        <x-input label="Serie" value="E001" />
                        
                        <x-input label="Numero" value="51" />
                    </div>
                </div>
            </div>

            <!-- Segunda fila -->
            <div class="flex flex-wrap justify-center -mx-2 mt-4">
                <div class="w-full md:w-2/12 px-2">
                    <x-select label="Tip Doc Iden:" :options="[]" option-label="descripcion" option-value="id" />
                </div>
                <div class="w-full md:w-4/12 px-2">
                    <x-input label="RUC:" value="10295782248" />
                </div>
                <div class="w-full md:w-4/12 px-2">
                    <x-input label="Entidad:" value="SALAS BRICEÑO MARTHA PILAR" />
                </div>
               
            </div>

            <!-- Tercera fila -->
            <div class="flex flex-wrap gap-3 justify-center -mx-2 mt-4">
                <div class="w-full md:w-2/12 px-2">
                    <x-select label="Moneda:" :options="[]" option-label="descripcion" option-value="id" />
                </div>
                <div class="w-full md:w-2/12 px-2">
                    <x-select label="Tasa Impositiva:" :options="[]" option-label="descripcion" option-value="id" />
                </div>
                <div class="w-full md:w-2/12 px-2">
                    <x-datetime-picker label="Fec Emi:" value="08/08/2023" without-time />
                </div>
                <div class="w-full md:w-2/12 px-2">
                    <x-datetime-picker label="Fec Ven:" value="08/08/2023" without-time />
                </div>
            </div>

            <!-- Cuarta fila -->
            <div class="flex flex-wrap justify-around -mx-2 mt-4">
           
                <div class="w-full md:w-2/12 px-2">
                    <x-input label="Ref de ventas:" value="2" />
                </div>
      
            </div>

            <!-- Productos -->
            <div class="mt-4">
                <div class="flex items-center gap-4 my-5">
                    <h3 class="text-sm font-bold text-gray-700">Productos:</h3>
                    @if(!empty($productos))
                    <div>
                        @livewire('modal-producto-general-avanz')
                    </div>
                    @endif
                </div>
            
                <!-- Verificar si la lista de productos está vacía -->
                @if(empty($productos))
                    <div class="flex flex-col items-center justify-center h-40 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h11M9 21V3m0 18l-4-4m4 4l4-4" />
                        </svg>
                        <p class="mt-2 text-gray-600">No hay productos añadidos.</p>
                        @livewire('modal-producto-general-avanz')
                    </div>
                @else
                    <!-- Mostrar la tabla si hay productos -->
                    <div class="overflow-x-auto mt-4">
                        <table class="min-w-full bg-white border border-gray-300 rounded-md">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Código
                                    </th>
                                    <th class="py-2 px-4 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Descripción
                                    </th>
                                    <th class="py-2 px-4 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Tasa
                                    </th>
                                    <th class="py-2 px-4 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Cantidad
                                    </th>
                                    <th class="py-2 px-4 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        C/U
                                    </th>
                                    <th class="py-2 px-4 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Total
                                    </th>
                                    <th class="py-2 px-4 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Accion
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productos as $index => $producto)
                                <tr>
                                    <td class="py-2 px-4 border-b border-gray-200">{{ $producto['codigoProducto'] }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200">{{ $producto['productoSeleccionado'] }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200">{{ $producto['tasaImpositiva'] ? 'Sí' : 'No' }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200">{{ $producto['cantidad'] }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200">{{ $producto['precioUnitario'] }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200">{{ $producto['total'] }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200">
                                        <x-button label="Eliminar" negative wire:click="eliminarProducto({{ $index }})" />
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
                <div class="w-full md:w-12/12 px-2">
                    <x-input label="Observaciones:" value="" />
                </div>
            </div>
            <div class="flex flex-wrap gap-2 justify-end">
           
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
                @if($origen === 'cxp' || $origen === 'cxc')
                <div class="w-full md:w-3/12 px-2 flex justify-end items-center">
                    <x-checkbox id="left-label" label="Detracción" />
                </div>
                <div class="w-full md:w-3/12 px-2">
                    <x-input label="Porcentaje" suffix="%" value="" />
                </div>
                <div class="w-full md:w-3/12 px-2">
                    <x-input label="Monto de detracción:" value="" />
                </div>
                <div class="w-full md:w-3/12 px-2">
                    <x-input label="Monto Neto:" value="" />
                </div>
            @endif
            

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
