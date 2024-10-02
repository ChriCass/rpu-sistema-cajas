<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __(ucfirst($routeName)) }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @component('components.alert', [
                'type' => 'success',
                'title' => '¡Bienvenido!',
                'message' => 'Estamos encantados de tenerte',
                'username' => Auth::user()->name,
            ])
            @endcomponent

            <x-card>
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-teal-600">Reportes</h1>
                </div>
                <div class="flex flex-wrap -mx-4">
                    <!-- Tarjeta Reporte Caja -->
                    <div class="w-full sm:w-1/2 px-4 mb-6">
                        <div class="bg-white shadow-md rounded-lg p-4 border border-teal-500 hover:bg-teal-50">
                            <a href="{{ route('reportes.reporte.caja') }}" wire:navigate class="block">
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
                                        <h3 class="text-lg font-semibold text-gray-700">Caja</h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Tarjeta Reporte Caja por Mes -->
                    <div class="w-full sm:w-1/2 px-4 mb-6">
                        <div class="bg-white shadow-md rounded-lg p-4 border border-teal-500 hover:bg-teal-50">
                            <a href="{{ route('reportes.reporte.caja.mes') }}"  wire:navigate class="block">
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
                                        <h3 class="text-lg font-semibold text-gray-700">Caja x Mes</h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Tarjeta Reporte Caja por Año -->
                    <div class="w-full sm:w-1/2 px-4 mb-6">
                        <div class="bg-white shadow-md rounded-lg p-4 border border-teal-500 hover:bg-teal-50">
                            <a href="{{ route('reportes.reporte.caja.anio') }}"  wire:navigate class="block">
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
                                        <h3 class="text-lg font-semibold text-gray-700">Caja x Año</h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Tarjeta Matriz de Cobros -->
                    <div class="w-full sm:w-1/2 px-4 mb-6">
                        <div class="bg-white shadow-md rounded-lg p-4 border border-teal-500 hover:bg-teal-50">
                            <a href="{{ route('reportes.matriz.cobros') }}"  wire:navigate class="block">
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
                                        <h3 class="text-lg font-semibold text-gray-700">Matriz de Cobros</h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Tarjeta Matriz de Pagos -->
                    <div class="w-full sm:w-1/2 px-4 mb-6">
                        <div class="bg-white shadow-md rounded-lg p-4 border border-teal-500 hover:bg-teal-50">
                            <a href="{{ route('reportes.matriz.pagos') }}"  wire:navigate class="block">
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
                                        <h3 class="text-lg font-semibold text-gray-700">Matriz de Pagos</h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
