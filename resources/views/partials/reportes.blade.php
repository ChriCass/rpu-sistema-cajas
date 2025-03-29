<!-- Encabezado -->
<div class="bg-teal-600 text-white p-4 rounded-t-lg shadow-md mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold uppercase">{{ auth()->user()->empresa->nombre ?? 'METAMSUR S.A.C.' }}</h2>
            <p class="text-sm">RUC: {{ auth()->user()->empresa->ruc ?? '20506666558' }}</p>
        </div>
        <div class="flex-1 text-center">
            <h1 class="text-xl font-bold uppercase">GESTIÓN DE REPORTES</h1>
        </div>
    </div>
</div>

<!-- Contenido Principal -->
<div class="bg-white overflow-hidden shadow-xl rounded-b-lg p-6">
    <!-- Sección de filtros -->
    <div class="bg-gray-50 p-4 rounded-lg mb-6 border border-gray-200">
        <h3 class="text-lg font-medium text-gray-700 mb-3">REPORTES</h3>
    </div>

    <!-- Tarjetas de reportes -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <!-- Tarjeta Reporte Caja -->
        <div class="bg-white shadow-md rounded-lg hover:shadow-lg transition-shadow duration-300">
            <div class="border-t-4 border-teal-500 rounded-t-lg"></div>
            <a href="{{ route('reportes.reporte.caja') }}" wire:navigate class="block p-5">
                <div class="flex items-center space-x-4">
                    <div class="bg-teal-100 text-teal-700 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6.012 18H21V4a2 2 0 0 0-2-2H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.988 5 19.805 5 19s.55-.988 1.012-1zM8 6h9v2H8V6z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Caja</h3>
                        <p class="text-sm text-gray-600 mt-1">Reporte detallado de caja</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Tarjeta Reporte Caja por Mes -->
        <div class="bg-white shadow-md rounded-lg hover:shadow-lg transition-shadow duration-300">
            <div class="border-t-4 border-teal-500 rounded-t-lg"></div>
            <a href="{{ route('reportes.reporte.caja.mes') }}" wire:navigate class="block p-5">
                <div class="flex items-center space-x-4">
                    <div class="bg-teal-100 text-teal-700 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6.012 18H21V4a2 2 0 0 0-2-2H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.988 5 19.805 5 19s.55-.988 1.012-1zM8 6h9v2H8V6z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Caja x Mes</h3>
                        <p class="text-sm text-gray-600 mt-1">Reporte mensual de caja</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Tarjeta Reporte Caja por Año -->
        <div class="bg-white shadow-md rounded-lg hover:shadow-lg transition-shadow duration-300">
            <div class="border-t-4 border-teal-500 rounded-t-lg"></div>
            <a href="{{ route('reportes.reporte.caja.anio') }}" wire:navigate class="block p-5">
                <div class="flex items-center space-x-4">
                    <div class="bg-teal-100 text-teal-700 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6.012 18H21V4a2 2 0 0 0-2-2H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.988 5 19.805 5 19s.55-.988 1.012-1zM8 6h9v2H8V6z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Caja x Año</h3>
                        <p class="text-sm text-gray-600 mt-1">Reporte anual de caja</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Tarjeta Matriz de Cobros -->
        <div class="bg-white shadow-md rounded-lg hover:shadow-lg transition-shadow duration-300">
            <div class="border-t-4 border-teal-500 rounded-t-lg"></div>
            <a href="{{ route('reportes.matriz.cobros') }}" wire:navigate class="block p-5">
                <div class="flex items-center space-x-4">
                    <div class="bg-teal-100 text-teal-700 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6.012 18H21V4a2 2 0 0 0-2-2H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.988 5 19.805 5 19s.55-.988 1.012-1zM8 6h9v2H8V6z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Matriz de Cobros</h3>
                        <p class="text-sm text-gray-600 mt-1">Gestión de cobros</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Tarjeta Matriz de Pagos -->
        <div class="bg-white shadow-md rounded-lg hover:shadow-lg transition-shadow duration-300">
            <div class="border-t-4 border-teal-500 rounded-t-lg"></div>
            <a href="{{ route('reportes.matriz.pagos') }}" wire:navigate class="block p-5">
                <div class="flex items-center space-x-4">
                    <div class="bg-teal-100 text-teal-700 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6.012 18H21V4a2 2 0 0 0-2-2H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.988 5 19.805 5 19s.55-.988 1.012-1zM8 6h9v2H8V6z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Matriz de Pagos</h3>
                        <p class="text-sm text-gray-600 mt-1">Gestión de pagos</p>
                    </div>
                </div>
            </a>
        </div>
        
        <!-- Resultado por Centro de Costos -->
        <div class="bg-white shadow-md rounded-lg hover:shadow-lg transition-shadow duration-300">
            <div class="border-t-4 border-teal-500 rounded-t-lg"></div>
            <a href="{{ route('resultado.por.centro.de.costos') }}" wire:navigate class="block p-5">
                <div class="flex items-center space-x-4">
                    <div class="bg-teal-100 text-teal-700 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6.012 18H21V4a2 2 0 0 0-2-2H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.988 5 19.805 5 19s.55-.988 1.012-1zM8 6h9v2H8V6z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Centro de Costos</h3>
                        <p class="text-sm text-gray-600 mt-1">Resultados por centro de costos</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Registro de Compras -->
        <div class="bg-white shadow-md rounded-lg hover:shadow-lg transition-shadow duration-300">
            <div class="border-t-4 border-teal-500 rounded-t-lg"></div>
            <a href="{{ route('reporte.registro.compras') }}" wire:navigate class="block p-5">
                <div class="flex items-center space-x-4">
                    <div class="bg-teal-100 text-teal-700 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6.012 18H21V4a2 2 0 0 0-2-2H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.988 5 19.805 5 19s.55-.988 1.012-1zM8 6h9v2H8V6z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Registro de Compras</h3>
                        <p class="text-sm text-gray-600 mt-1">Reporte de compras realizadas</p>
                    </div>
                </div>
            </a>
        </div>
        
        <!-- Registro de Ventas -->
        <div class="bg-white shadow-md rounded-lg hover:shadow-lg transition-shadow duration-300">
            <div class="border-t-4 border-teal-500 rounded-t-lg"></div>
            <a href="{{ route('reporte.registro.ventas') }}" wire:navigate class="block p-5">
                <div class="flex items-center space-x-4">
                    <div class="bg-teal-100 text-teal-700 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6.012 18H21V4a2 2 0 0 0-2-2H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.988 5 19.805 5 19s.55-.988 1.012-1zM8 6h9v2H8V6z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Registro de Ventas</h3>
                        <p class="text-sm text-gray-600 mt-1">Reporte de ventas realizadas</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Balance de Cuentas -->
        <div class="bg-white shadow-md rounded-lg hover:shadow-lg transition-shadow duration-300">
            <div class="border-t-4 border-teal-500 rounded-t-lg"></div>
            <a href="{{ route('balance.cuentas') }}" wire:navigate class="block p-5">
                <div class="flex items-center space-x-4">
                    <div class="bg-teal-100 text-teal-700 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6.012 18H21V4a2 2 0 0 0-2-2H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.988 5 19.805 5 19s.55-.988 1.012-1zM8 6h9v2H8V6z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Balance de Cuentas</h3>
                        <p class="text-sm text-gray-600 mt-1">Estado de cuentas</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Reporte analítico de costos -->
        <div class="bg-white shadow-md rounded-lg hover:shadow-lg transition-shadow duration-300">
            <div class="border-t-4 border-teal-500 rounded-t-lg"></div>
            <a href="{{ route('reporte.analitico.costo') }}" wire:navigate class="block p-5">
                <div class="flex items-center space-x-4">
                    <div class="bg-teal-100 text-teal-700 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6.012 18H21V4a2 2 0 0 0-2-2H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.988 5 19.805 5 19s.55-.988 1.012-1zM8 6h9v2H8V6z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Análisis de Costos</h3>
                        <p class="text-sm text-gray-600 mt-1">Reporte analítico de costos</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Inconsistencias -->
        <div class="bg-white shadow-md rounded-lg hover:shadow-lg transition-shadow duration-300">
            <div class="border-t-4 border-teal-500 rounded-t-lg"></div>
            <a href="{{ route('reporte.inconsistencias') }}" wire:navigate class="block p-5">
                <div class="flex items-center space-x-4">
                    <div class="bg-teal-100 text-teal-700 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6.012 18H21V4a2 2 0 0 0-2-2H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.988 5 19.805 5 19s.55-.988 1.012-1zM8 6h9v2H8V6z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Inconsistencias</h3>
                        <p class="text-sm text-gray-600 mt-1">Reporte de inconsistencias</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Diario Matriz -->
        <div class="bg-white shadow-md rounded-lg hover:shadow-lg transition-shadow duration-300">
            <div class="border-t-4 border-teal-500 rounded-t-lg"></div>
            <a href="{{ route('reporte.diario.matriz') }}" wire:navigate class="block p-5">
                <div class="flex items-center space-x-4">
                    <div class="bg-teal-100 text-teal-700 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6.012 18H21V4a2 2 0 0 0-2-2H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.988 5 19.805 5 19s.55-.988 1.012-1zM8 6h9v2H8V6z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Diario Matriz</h3>
                        <p class="text-sm text-gray-600 mt-1">Reporte diario matriz</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
