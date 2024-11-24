<div class="flex flex-wrap -mx-4">
    <!-- Tarjeta Reporte Caja -->
    <div class="w-full sm:w-1/2 px-4 mb-6">
        <div class="bg-white shadow-md rounded-lg p-4 border border-teal-500 hover:bg-teal-50">
            <a href="{{ route('reportes.reporte.caja') }}" wire:navigate class="block">
                <div class="flex items-center space-x-4">
                    <div class="bg-teal-500 text-white p-2 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;">
                            <path
                                d="M6.012 18H21V4a2 2 0 0 0-2-2H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.988 5 19.805 5 19s.55-.988 1.012-1zM8 6h9v2H8V6z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">Caja</h3>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Tarjeta Reporte Caja por Mes -->
    <div class="w-full sm:w-1/2 px-4 mb-6">
        <div class="bg-white shadow-md rounded-lg p-4 border border-teal-500 hover:bg-teal-50">
            <a href="{{ route('reportes.reporte.caja.mes') }}" wire:navigate class="block">
                <div class="flex items-center space-x-4">
                    <div class="bg-teal-500 text-white p-2 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;">
                            <path
                                d="M6.012 18H21V4a2 2 0 0 0-2-2H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.988 5 19.805 5 19s.55-.988 1.012-1zM8 6h9v2H8V6z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">Caja x Mes</h3>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Tarjeta Reporte Caja por Año -->
    <div class="w-full sm:w-1/2 px-4 mb-6">
        <div class="bg-white shadow-md rounded-lg p-4 border border-teal-500 hover:bg-teal-50">
            <a href="{{ route('reportes.reporte.caja.anio') }}" wire:navigate class="block">
                <div class="flex items-center space-x-4">
                    <div class="bg-teal-500 text-white p-2 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;">
                            <path
                                d="M6.012 18H21V4a2 2 0 0 0-2-2H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.988 5 19.805 5 19s.55-.988 1.012-1zM8 6h9v2H8V6z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">Caja x Año</h3>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Tarjeta Matriz de Cobros -->
    <div class="w-full sm:w-1/2 px-4 mb-6">
        <div class="bg-white shadow-md rounded-lg p-4 border border-teal-500 hover:bg-teal-50">
            <a href="{{ route('reportes.matriz.cobros') }}" wire:navigate class="block">
                <div class="flex items-center space-x-4">
                    <div class="bg-teal-500 text-white p-2 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;">
                            <path
                                d="M6.012 18H21V4a2 2 0 0 0-2-2H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.988 5 19.805 5 19s.55-.988 1.012-1zM8 6h9v2H8V6z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">Matriz de Cobros</h3>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Tarjeta Matriz de Pagos -->
    <div class="w-full sm:w-1/2 px-4 mb-6">
        <div class="bg-white shadow-md rounded-lg p-4 border border-teal-500 hover:bg-teal-50">
            <a href="{{ route('reportes.matriz.pagos') }}" wire:navigate class="block">
                <div class="flex items-center space-x-4">
                    <div class="bg-teal-500 text-white p-2 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;">
                            <path
                                d="M6.012 18H21V4a2 2 0 0 0-2-2H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.988 5 19.805 5 19s.55-.988 1.012-1zM8 6h9v2H8V6z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">Matriz de Pagos</h3>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <!-- Resultado por Centro de Costos -->
    <div class="w-full sm:w-1/2 px-4 mb-6">
        <div class="bg-white shadow-md rounded-lg p-4 border border-teal-500 hover:bg-teal-50">
            <a href="{{ route('resultado.por.centro.de.costos') }}" wire:navigate class="block">
                <div class="flex items-center space-x-4">
                    <div class="bg-teal-500 text-white p-2 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;">
                            <path
                                d="M6.012 18H21V4a2 2 0 0 0-2-2H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.988 5 19.805 5 19s.55-.988 1.012-1zM8 6h9v2H8V6z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">Resultado por Centro de Costos</h3>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Botón y lógica para mostrar más reportes -->
    <div x-data="{ showMore: false }" class="w-full text-center mt-4">

        <div x-show="showMore" x-transition class="w-full flex flex-wrap ">
            <!-- Registro de Compras -->
            <div class="w-full sm:w-1/2 px-4 mb-6">
                <div class="bg-white shadow-md rounded-lg p-4 border border-teal-500 hover:bg-teal-50">
                    <a href="{{ route('reporte.registro.compras') }}" wire:navigate class="block">
                        <div class="flex items-center space-x-4">
                            <div class="bg-teal-500 text-white p-2 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;">
                                    <path
                                        d="M6.012 18H21V4a2 2 0 0 0-2-2H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.988 5 19.805 5 19s.55-.988 1.012-1zM8 6h9v2H8V6z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-700">Registro de Compras</h3>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Registro de Ventas -->
            <div class="w-full sm:w-1/2 px-4 mb-6">
                <div class="bg-white shadow-md rounded-lg p-4 border border-teal-500 hover:bg-teal-50">
                    <a href="{{ route('reporte.registro.ventas') }}" wire:navigate class="block">
                        <div class="flex items-center space-x-4">
                            <div class="bg-teal-500 text-white p-2 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;">
                                    <path
                                        d="M6.012 18H21V4a2 2 0 0 0-2-2H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.988 5 19.805 5 19s.55-.988 1.012-1zM8 6h9v2H8V6z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-700">Registro de Ventas</h3>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Balance de Cuentas -->
            <div class="w-full sm:w-1/2 px-4 mb-6">
                <div class="bg-white shadow-md rounded-lg p-4 border border-teal-500 hover:bg-teal-50">
                    <a href="{{ route('balance.cuentas') }}" wire:navigate class="block">
                        <div class="flex items-center space-x-4">
                            <div class="bg-teal-500 text-white p-2 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;">
                                    <path
                                        d="M6.012 18H21V4a2 2 0 0 0-2-2H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.988 5 19.805 5 19s.55-.988 1.012-1zM8 6h9v2H8V6z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-700">Balance de Cuentas</h3>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Reporte analitico de costos -->
            <div class="w-full sm:w-1/2 px-4 mb-6">
                <div class="bg-white shadow-md rounded-lg p-4 border border-teal-500 hover:bg-teal-50">
                    <a href="{{ route('reporte.analitico.costo') }}" wire:navigate class="block">
                        <div class="flex items-center space-x-4">
                            <div class="bg-teal-500 text-white p-2 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;">
                                    <path
                                        d="M6.012 18H21V4a2 2 0 0 0-2-2H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.988 5 19.805 5 19s.55-.988 1.012-1zM8 6h9v2H8V6z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-700">Reporte analitico de costos</h3>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Inconsistencias -->
            <div class="w-full sm:w-1/2 px-4 mb-6">
                <div class="bg-white shadow-md rounded-lg p-4 border border-teal-500 hover:bg-teal-50">
                    <a href="{{ route('reporte.inconsistencias') }}" wire:navigate class="block">
                        <div class="flex items-center space-x-4">
                            <div class="bg-teal-500 text-white p-2 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;">
                                    <path
                                        d="M6.012 18H21V4a2 2 0 0 0-2-2H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.988 5 19.805 5 19s.55-.988 1.012-1zM8 6h9v2H8V6z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-700">Inconsistencias</h3>
                            </div>
                        </div>
                    </a>
                </div>
            </div>


            <!-- Diario Matriz -->
            <div class="w-full sm:w-1/2 px-4 mb-6">
                <div class="bg-white shadow-md rounded-lg p-4 border border-teal-500 hover:bg-teal-50">
                    <a href="{{ route('reporte.diario.matriz') }}" wire:navigate class="block">
                        <div class="flex items-center space-x-4">
                            <div class="bg-teal-500 text-white p-2 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;">
                                    <path
                                        d="M6.012 18H21V4a2 2 0 0 0-2-2H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.988 5 19.805 5 19s.55-.988 1.012-1zM8 6h9v2H8V6z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-700">Diario Matriz</h3>
                            </div>
                        </div>
                    </a>
                </div>
            </div>


        </div>
        <button 
        x-on:click="showMore = !showMore"
        :class="showMore ? 'bg-yellow-500' : 'bg-blue-500'"
        class="px-6 py-3 text-white font-bold rounded-lg shadow-lg transition duration-300 transform hover:scale-105 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50">
        <span x-text="showMore ? 'Ver menos reportes' : 'Ver más reportes'"></span>
    </button>
    </div>


</div>
