<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __(ucfirst($routeName)) }}
        </h2>
    </x-slot>
  
    <div class="container mx-auto p-4">
      <x-card>

        @livewire('acciones-de-caja')                  
      
      </x-card>

      
  </x-app-layout>

