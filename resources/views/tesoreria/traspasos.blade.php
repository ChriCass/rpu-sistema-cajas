<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __(ucfirst($routeName)) }}
        </h2>
    </x-slot>
  
    <div class="max-w-screen-xl mx-auto px-4 mt-12">
        <div class="flex flex-wrap -mx-4">
          <div class="w-full w-6/12 px-4 mb-4">
            @livewire('traspasos-table')
          </div>
          <div class="w-full   px-4 mb-4">
           
              @livewire('vaucher-de-traspasos') 
              
         
          </div>
        </div>
      </div>
  </x-app-layout>