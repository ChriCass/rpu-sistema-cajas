<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <!-- Estilos específicos para ocultar las flechas nativas de los selectores en todos los navegadores -->
    <style>
        select {
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            background-image: none !important;
        }
        select::-ms-expand {
            display: none !important;
        }
        /* Asegurar que Safari respete la regla */
        select::-webkit-inner-spin-button,
        select::-webkit-outer-spin-button,
        select::-webkit-search-decoration {
            -webkit-appearance: none !important;
        }
    </style>
    
    <!-- Encabezado con fondo teal -->
    <div class="bg-teal-600 text-white p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold tracking-wide">METAMSUR S.A.C.</h1>
                <p class="text-sm mt-1">RUC: 20606566558</p>
                <p class="text-sm mt-1">📱 959898721</p>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-bold mb-2">PARTE DIARIO DE MAQUINARIA</h2>
                <div class="bg-white text-teal-600 rounded px-3 py-1 inline-block">
                    <span class="font-bold">Nº {{ $numero }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensaje de notificación -->
    @if (session()->has('message'))
        <div class="bg-teal-50 border-l-4 border-teal-500 text-teal-700 p-4 mx-6 mt-4" role="alert">
            <p>{{ session('message') }}</p>
        </div>
    @endif

    <!-- Contenido principal con padding -->
    <div class="p-6">
        <!-- Fechas de inicio y fin -->
        <div class="mb-6">
            <h3 class="text-sm font-bold pb-2 mb-3 text-teal-600 bg-gray-50 p-2 rounded">PERÍODO DE TRABAJO</h3>
            <div class="flex justify-end items-center">
                <div class="flex space-x-4 items-center">
                    <div class="flex items-center">
                        <label class="text-sm font-medium mr-2">Fecha de Inicio:</label>
                        <input type="date" wire:model="fechaInicio" 
                               class="border border-gray-300 rounded py-1 px-2 text-sm focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                    </div>
                    <div class="flex items-center">
                        <label class="text-sm font-medium mr-2">Fecha de Fin:</label>
                        <input type="date" wire:model="fechaFin" 
                               class="border border-gray-300 rounded py-1 px-2 text-sm focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                    </div>
                    <div class="flex items-center">
                        <label class="text-sm font-medium mr-2">Duración:</label>
                        <div class="border border-gray-300 rounded py-1 px-3 text-sm bg-teal-50 text-center font-medium w-20">
                            {{ $diasTotales }} {{ $diasTotales > 1 ? 'días' : 'día' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Datos principales -->
        <div class="grid grid-cols-2 gap-6 mb-8">
            <!-- Columna izquierda -->
            <div>
                <h3 class="text-sm font-bold border-b border-gray-300 pb-1 mb-3 text-teal-600">INFORMACIÓN DEL OPERADOR</h3>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <label class="w-1/3 text-sm font-medium">Operador:</label>
                        <div class="w-2/3 relative">
                            <select wire:model="operador" 
                                   class="w-full py-1 px-3 border border-gray-300 rounded focus:border-teal-500 focus:ring-0 bg-transparent appearance-none outline-none"
                                   style="-webkit-appearance: none; -moz-appearance: none; appearance: none;">
                                <option value="">Seleccione un operador</option>
                                @foreach($operadores as $op)
                                    <option value="{{ $op['id'] }}">{{ $op['nombre'] }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4 text-teal-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <label class="w-1/3 text-sm font-medium">Unidad:</label>
                        <div class="w-2/3 relative">
                            <select wire:model="unidad" 
                                   class="w-full py-1 px-3 border border-gray-300 rounded focus:border-teal-500 focus:ring-0 bg-transparent appearance-none outline-none"
                                   style="-webkit-appearance: none; -moz-appearance: none; appearance: none;">
                                <option value="">Seleccione una unidad</option>
                                @foreach($unidades as $u)
                                    <option value="{{ $u['id'] }}">{{ $u['numero'] }} - {{ $u['descripcion'] }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4 text-teal-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Columna derecha -->
            <div>
                <h3 class="text-sm font-bold border-b border-gray-300 pb-1 mb-3 text-teal-600">INFORMACIÓN DEL CLIENTE</h3>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <label class="w-1/3 text-sm font-medium">Cliente:</label>
                        <div class="w-2/3 relative">
                            <div class="flex space-x-2">
                                <div class="relative flex-1">
                                    <input type="text" wire:model="busquedaCliente" wire:keyup="buscarClientes"
                                           class="w-full py-1 px-3 border border-gray-300 rounded focus:border-teal-500 focus:ring-0 bg-transparent" 
                                           placeholder="Buscar cliente...">
                                    @if($cliente)
                                        <button wire:click="limpiarCliente" 
                                                class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                                <button type="button" wire:click="crearCliente" 
                                        class="px-3 py-1 bg-teal-600 text-white rounded hover:bg-teal-700 transition flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    <span class="text-sm">Nuevo</span>
                                </button>
                            </div>
                            
                            @if($mostrarResultados && count($clientes) > 0)
                                <div class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto">
                                    @foreach($clientes as $c)
                                        <div wire:click="seleccionarCliente({{ $c['id'] }})"
                                             class="px-3 py-2 hover:bg-teal-50 cursor-pointer">
                                            <div class="text-sm">{{ $c['descripcion'] }}</div>
                                            <div class="text-xs text-gray-500">Código: {{ $c['id'] }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center">
                        <label class="w-1/3 text-sm font-medium">CÓDIGO ENTIDAD:</label>
                        <input type="text" wire:model="codigoEntidad" readonly
                               class="w-2/3 py-1 px-3 border border-gray-300 rounded bg-gray-50" 
                               placeholder="Se completará automáticamente">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Lugar de trabajo en toda la anchura -->
        <div class="mb-8">
            <h3 class="text-sm font-bold pb-2 mb-2 text-teal-600 bg-gray-50 p-2 rounded">LUGAR DE TRABAJO</h3>
            <div class="border border-gray-300 rounded overflow-hidden">
                <input type="text" wire:model="lugarTrabajo" 
                      class="w-full py-2 px-3 border-none focus:ring-0 bg-transparent" 
                      placeholder="Ingrese la ubicación o dirección completa del lugar de trabajo">
            </div>
        </div>

        <!-- Tabla de control de horas -->
        <div class="mb-8">
            <h3 class="text-sm font-bold pb-2 mb-2 text-teal-600 bg-gray-50 p-2 rounded">CONTROL DE HORAS</h3>
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="border border-gray-300 px-2 py-2 text-sm" rowspan="2">CONTROL</th>
                        <th class="border border-gray-300 px-2 py-2 text-sm text-center" colspan="3">MAÑANA</th>
                        <th class="border border-gray-300 px-2 py-2 text-sm text-center" colspan="3">TARDE</th>
                        <th class="border border-gray-300 px-2 py-2 text-sm text-center" rowspan="2">TOTAL HORAS</th>
                    </tr>
                    <tr class="bg-gray-50">
                        <th class="border border-gray-300 px-2 py-2 text-sm text-center">INICIO</th>
                        <th class="border border-gray-300 px-2 py-2 text-sm text-center">TÉRMINO</th>
                        <th class="border border-gray-300 px-2 py-2 text-sm text-center">T. HORAS</th>
                        <th class="border border-gray-300 px-2 py-2 text-sm text-center">INICIO</th>
                        <th class="border border-gray-300 px-2 py-2 text-sm text-center">TÉRMINO</th>
                        <th class="border border-gray-300 px-2 py-2 text-sm text-center">T. HORAS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-gray-300 px-2 py-2 text-sm font-medium">HORAS DE TRABAJO</td>
                        <td class="border border-gray-300 p-0">
                            <input type="text" wire:model="horaInicioManana" wire:change="calcularHorasManana" 
                                   class="w-full py-2 px-2 text-center border-none focus:ring-0" placeholder="HH:MM">
                        </td>
                        <td class="border border-gray-300 p-0">
                            <input type="text" wire:model="horaFinManana" wire:change="calcularHorasManana" 
                                   class="w-full py-2 px-2 text-center border-none focus:ring-0" placeholder="HH:MM">
                        </td>
                        <td class="border border-gray-300 p-0 text-center bg-teal-50">
                            <input type="text" wire:model="horasManana" readonly
                                   class="w-full py-2 px-2 text-center border-none bg-teal-50 focus:ring-0">
                        </td>
                        <td class="border border-gray-300 p-0">
                            <input type="text" wire:model="horaInicioTarde" wire:change="calcularHorasTarde" 
                                   class="w-full py-2 px-2 text-center border-none focus:ring-0" placeholder="HH:MM">
                        </td>
                        <td class="border border-gray-300 p-0">
                            <input type="text" wire:model="horaFinTarde" wire:change="calcularHorasTarde" 
                                   class="w-full py-2 px-2 text-center border-none focus:ring-0" placeholder="HH:MM">
                        </td>
                        <td class="border border-gray-300 p-0 text-center bg-teal-50">
                            <input type="text" wire:model="horasTarde" readonly
                                   class="w-full py-2 px-2 text-center border-none bg-teal-50 focus:ring-0">
                        </td>
                        <td class="border border-gray-300 p-0 text-center bg-teal-50 font-medium">
                            <input type="text" wire:model="totalHoras" readonly
                                   class="w-full py-2 px-2 text-center border-none bg-teal-50 focus:ring-0 font-medium">
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-2 py-2 text-sm font-medium">HORÓMETRO</td>
                        <td class="border border-gray-300 p-0">
                            <input type="text" wire:model="horometroInicioManana" wire:change="calcularDiferenciaHorometroManana" 
                                   class="w-full py-2 px-2 text-center border-none focus:ring-0" placeholder="Inicio">
                        </td>
                        <td class="border border-gray-300 p-0">
                            <input type="text" wire:model="horometroFinManana" wire:change="calcularDiferenciaHorometroManana" 
                                   class="w-full py-2 px-2 text-center border-none focus:ring-0" placeholder="Fin">
                        </td>
                        <td class="border border-gray-300 p-0 text-center bg-teal-50">
                            <input type="text" wire:model="diferenciaHorometroManana" readonly
                                   class="w-full py-2 px-2 text-center border-none bg-teal-50 focus:ring-0">
                        </td>
                        <td class="border border-gray-300 p-0">
                            <input type="text" wire:model="horometroInicioTarde" wire:change="calcularDiferenciaHorometroTarde" 
                                   class="w-full py-2 px-2 text-center border-none focus:ring-0" placeholder="Inicio">
                        </td>
                        <td class="border border-gray-300 p-0">
                            <input type="text" wire:model="horometroFinTarde" wire:change="calcularDiferenciaHorometroTarde" 
                                   class="w-full py-2 px-2 text-center border-none focus:ring-0" placeholder="Fin">
                        </td>
                        <td class="border border-gray-300 p-0 text-center bg-teal-50">
                            <input type="text" wire:model="diferenciaHorometroTarde" readonly
                                   class="w-full py-2 px-2 text-center border-none bg-teal-50 focus:ring-0">
                        </td>
                        <td class="border border-gray-300 p-0 text-center bg-teal-50 font-medium">
                            <input type="text" wire:model="diferencia" readonly
                                   class="w-full py-2 px-2 text-center border-none bg-teal-50 focus:ring-0 font-medium">
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-2 py-2 text-sm font-medium">INTERRUPCIONES</td>
                        <td class="border border-gray-300 bg-gray-50 text-sm text-center font-medium" colspan="7">
                            DESCRIPCIÓN DE INTERRUPCIONES (si las hubiera)
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-2 py-2 text-sm bg-gray-50"></td>
                        <td class="border border-gray-300 p-0" colspan="7">
                            <input type="text" wire:model="interrupciones" 
                                   class="w-full py-2 px-2 border-none focus:ring-0">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Tabla de consumo de combustible y valorización -->
        <div class="mb-8">
            <h3 class="text-sm font-bold pb-2 mb-2 text-teal-600 bg-gray-50 p-2 rounded">CONSUMO Y VALORIZACIÓN</h3>
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="border border-gray-300 px-2 py-2 text-sm text-center" colspan="5">CONSUMO DE COMBUSTIBLE</th>
                        <th class="border border-gray-300 px-2 py-2 text-sm text-center" rowspan="2">HORÓMETRO</th>
                        <th class="border border-gray-300 px-2 py-2 text-sm text-center" colspan="3">VALORIZACIÓN</th>
                    </tr>
                    <tr class="bg-gray-50">
                        <th class="border border-gray-300 px-2 py-2 text-sm text-center">COMBUSTIBLE</th>
                        <th class="border border-gray-300 px-2 py-2 text-sm text-center">LUBRICANTES</th>
                        <th class="border border-gray-300 px-2 py-2 text-sm text-center">INICIO</th>
                        <th class="border border-gray-300 px-2 py-2 text-sm text-center">TÉRMINO</th>
                        <th class="border border-gray-300 px-2 py-2 text-sm text-center">DIFERENCIA</th>
                        <th class="border border-gray-300 px-2 py-2 text-sm text-center">1 HORA TRABAJADA</th>
                        <th class="border border-gray-300 px-2 py-2 text-sm text-center">PRECIO/H</th>
                        <th class="border border-gray-300 px-2 py-2 text-sm text-center">IMPORTE A COBRAR</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-gray-300 p-0">
                            <input type="text" wire:model="combustible" 
                                   class="w-full py-2 px-2 text-center border-none focus:ring-0">
                        </td>
                        <td class="border border-gray-300 p-0">
                            <input type="text" wire:model="lubricantes" 
                                   class="w-full py-2 px-2 text-center border-none focus:ring-0">
                        </td>
                        <td class="border border-gray-300 p-0">
                            <input type="text" wire:model="horometroInicioConsumo" wire:change="calcularDiferenciaConsumo" 
                                   class="w-full py-2 px-2 text-center border-none focus:ring-0">
                        </td>
                        <td class="border border-gray-300 p-0">
                            <input type="text" wire:model="horometroFinConsumo" wire:change="calcularDiferenciaConsumo" 
                                   class="w-full py-2 px-2 text-center border-none focus:ring-0">
                        </td>
                        <td class="border border-gray-300 p-0 text-center bg-teal-50">
                            <input type="text" wire:model="diferenciaConsumo" readonly
                                   class="w-full py-2 px-2 text-center border-none bg-teal-50 focus:ring-0">
                        </td>
                        <td class="border border-gray-300 p-0 text-center">
                            <input type="text" wire:model="horaPorTrabajo" wire:change="calcularImporte" 
                                   class="w-full py-2 px-2 text-center border-none focus:ring-0">
                        </td>
                        <td class="border border-gray-300 p-0 text-center">
                            <input type="text" wire:model="precioPorHora" wire:change="calcularImporte" 
                                   class="w-full py-2 px-2 text-center border-none focus:ring-0">
                        </td>
                        <td class="border border-gray-300 p-0 text-center">
                            <input type="text" wire:model="precioPorHora" wire:change="calcularImporte" 
                                   class="w-full py-2 px-2 text-center border-none focus:ring-0">
                        </td>
                        <td class="border border-gray-300 px-3 py-2 text-sm font-medium bg-teal-50" colspan="3">
                            <div class="flex items-center justify-between">
                                <span class="text-teal-600"></span>
                                <input type="text" wire:model="importeACobrar" readonly
                                       class="w-full py-2 px-2 text-center border-none bg-teal-50 focus:ring-0 font-medium">
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Descripción del trabajo -->
        <div class="grid grid-cols-1 gap-6 mb-8">
            <div>
                <h3 class="text-sm font-bold pb-2 mb-2 text-teal-600 bg-gray-50 p-2 rounded">DESCRIPCIÓN DEL TRABAJO</h3>
                <div class="relative">
                    <select wire:model="descripcionTrabajo" 
                            class="w-full py-2 px-3 border border-gray-300 rounded focus:border-teal-500 focus:ring-0 bg-transparent appearance-none outline-none">
                        <option value="">Seleccione el tipo de venta</option>
                        @foreach($tiposVenta as $tipo)
                            <option value="{{ $tipo['id'] }}">{{ $tipo['descripcion'] }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4 text-teal-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <div>
                <h3 class="text-sm font-bold pb-2 mb-2 text-teal-600 bg-gray-50 p-2 rounded">OBSERVACIONES</h3>
                <div class="border border-gray-300 rounded overflow-hidden">
                    <textarea wire:model="observaciones" rows="3" 
                              class="w-full p-3 border-none focus:ring-0 resize-none"
                              placeholder="Agregue observaciones relevantes si las hay"></textarea>
                </div>
            </div>
            <div>
                <h3 class="text-sm font-bold pb-2 mb-2 text-teal-600 bg-gray-50 p-2 rounded">ESTADO DE PAGO</h3>
                <div class="relative">
                    <select wire:model="pagado" 
                            class="w-full py-2 px-3 border border-gray-300 rounded focus:border-teal-500 focus:ring-0 bg-transparent appearance-none outline-none">
                        <option value="0">PENDIENTE DE PAGO</option>
                        <option value="1">PAGADO</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4 text-teal-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="flex justify-end border-t border-gray-200 pt-6 mt-8 space-x-4">
            <button type="button" onclick="window.history.back()" 
                    class="px-5 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition font-medium">
                Cancelar
            </button>
            <button type="button" wire:click="guardar" 
                    class="px-5 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 transition font-medium">
                Guardar
            </button>
        </div>
    </div>
    @livewire('entidad-modal-generalizado')
</div> 