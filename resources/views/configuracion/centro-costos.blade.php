<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __(ucfirst($routeName)) }}
        </h2>
    </x-slot>

    <div class="max-w-screen-xl mx-auto px-4 mt-12">
    

        <div class="flex flex-wrap justify-center -mx-4">
            <div class="w-8/12 mb-3" x-data="{ show: true }">
                @if (session()->has('message'))
                    <div x-show="show" class="relative">
                        <x-alert title="Éxito!" positive>
                            {{ session('message') }}
                            <button @click="show = false" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                              </svg></button>
                        </x-alert>
                    </div>
                @elseif (session()->has('error'))
                    <div x-show="show" class="relative">
                        <x-alert title="Error!" negative>
                            {{ session('error') }}
                            <button @click="show = false" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                              </svg></button>
                        </x-alert>
                    </div>
                @endif
            </div>
            <div class="w-full sm:w-8/12 px-4 mb-4">
                <x-card>  @livewire('centro-de-costos-table') </x-card>
            </div>
            <div class="w-full   px-4 mb-4">
                <div class="flex justify-center">
                    @livewire('costos-modal')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
