<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Parte Diario de Maquinaria') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <livewire:parte-diario-maquinaria :origen="$origen" :id="$id ?? null" />
        </div>
    </div>
</x-app-layout> 