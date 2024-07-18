<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __(ucfirst($routeName)) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1>hellooooo</h1>

            @component('components.alert', ['type' => 'success', 'title' => 'esta es mi alerta!', 'message' => 'algo bueno o malo puede ocurrir'])
            @endcomponent
        </div>
    </div>
</x-app-layout>
