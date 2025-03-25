<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Unidades') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Mensajes de éxito/error -->
                    <div class="mb-4">
                        @if (session()->has('message'))
                            <x-alert title="Éxito!" positive>
                                {{ session('message') }}
                            </x-alert>
                        @elseif (session()->has('error'))
                            <x-alert title="Error!" negative>
                                {{ session('error') }}
                            </x-alert>
                        @endif
                    </div>

                    <livewire:unidad-table />
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 