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

    <!-- Tarjetas de reportes -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        
        @if(auth()->user()->hasRole(['admin', 'tesorero']))
            <!-- REPORTES PARA ADMIN Y TESORERO -->
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
                        </div>
                    </div>
                </a>
            </div>
        @endif

        @if(auth()->user()->hasRole(['supervisor maquinaria']))
            <!-- REPORTES PARA SUPERVISOR MAQUINARIA -->
            
            <!-- Matriz Maquinaria -->
            <div class="bg-white shadow-md rounded-lg hover:shadow-lg transition-shadow duration-300">
                <div class="border-t-4 border-teal-500 rounded-t-lg"></div>
                <a href="{{ route('reportes.matriz.maquinaria') }}" wire:navigate class="block p-5">
                    <div class="flex items-center space-x-4">
                        <div class="bg-teal-100 text-teal-700 p-3 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M13 18v2h6v2H5v-2h6v-2H2V3h20v15h-9zm7-13H4v11h16V5zM9 7h6v2H9V7zm0 4h6v2H9v-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Matriz Maquinaria</h3>
                        </div>
                    </div>
                </a>
            </div>
            
            <!-- Gráficos -->
            <div class="bg-white shadow-md rounded-lg hover:shadow-lg transition-shadow duration-300">
                <div class="border-t-4 border-teal-500 rounded-t-lg"></div>
                <a href="{{ route('reportes.graficos') }}" wire:navigate class="block p-5">
                    <div class="flex items-center space-x-4">
                        <div class="bg-teal-100 text-teal-700 p-3 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M5 21h14c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2zM5 5h14l.001 14H5V5z"></path>
                                <path d="M13.553 11.658a1.002 1.002 0 0 0-1.302.084l-2.038 1.876-1.099-1.099a1 1 0 0 0-1.414 0l-2.032 2.031 1.414 1.414 1.318-1.317 1.1 1.1a1 1 0 0 0 1.414-.001l2.724-2.724.707.707 1.414-1.414-1.414-1.414-.792-.243z"></path>
                                <path d="M19.545 14.586l-1.039-1.039-1.414 1.414 2.038 2.039 4.095-4.094-1.413-1.414z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Gráficos</h3>
                        </div>
                    </div>
                </a>
            </div>
            
            <!-- Ejemplo de tarjeta para reportes de supervisor maquinaria 
            <div class="bg-white shadow-md rounded-lg hover:shadow-lg transition-shadow duration-300">
                <div class="border-t-4 border-teal-500 rounded-t-lg"></div>
                <a href="#" wire:navigate class="block p-5">
                    <div class="flex items-center space-x-4">
                        <div class="bg-teal-100 text-teal-700 p-3 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M6.012 18H21V4a2 2 0 0 0-2-2H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.988 5 19.805 5 19s.55-.988 1.012-1zM8 6h9v2H8V6z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Reporte Maquinaria</h3>
                        </div>
                    </div>
                </a>
            </div>
            -->
        @endif
    </div>
</div>
