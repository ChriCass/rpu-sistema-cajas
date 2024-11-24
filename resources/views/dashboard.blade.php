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
                @include('partials.reportes')
            </x-card>
        </div>
    </div>
</x-app-layout>
