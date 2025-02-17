<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __(ucfirst($routeName)) }}
        </h2>
    </x-slot>


    <div class="max-w-screen-xl mx-auto px-4 mt-12">
  
        <div class="flex flex-wrap -mx-4">
            <div class="w-full mb-3" x-data="{ show: true }">
                @if (session()->has('message'))
                    <div x-show="show" class="relative">
                        <x-alert title="Éxito!" positive>
                            {{ session('message') }}
                            <button @click="show = false" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">&times;</button>
                        </x-alert>
                    </div>
                @elseif (session()->has('error'))
                    <div x-show="show" class="relative">
                        <x-alert title="Error!" negative>
                            {{ session('error') }}
                            <button @click="show = false" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">&times;</button>
                        </x-alert>
                    </div>
                @endif
            </div>
            
            <div class="w-full  px-4 mb-4">
              
                <x-card> @livewire('cuenta-table') </x-card>
            </div>
            <div class="w-full   px-4 mb-4">
                <div class="flex justify-center">
                    @livewire('cuenta-modal')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
