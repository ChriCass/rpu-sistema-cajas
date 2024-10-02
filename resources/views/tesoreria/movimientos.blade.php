<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __(ucfirst($routeName)) }}
        </h2>
    </x-slot>

    <div class="max-w-screen-xl mx-auto px-4 mt-12">
   
            <div class="w-full w-6/12 px-4 mb-4">
                
                 <x-card>
                   
                        @if (session()->has('message'))
                        <x-alert title="Felicidades!" positive>
                            {{ session('message') }}
                        </x-alert>
                    @elseif (session()->has('error'))
                        <x-alert title="Error!" negative>
                            {{ session('error') }}
                        </x-alert>
                    @endif

                    @livewire('apertura-table')
                
                 </x-card>
                    
                    
               
            </div>

        
        <div class="w-full px-4 mb-4">
          <div class="flex justify-center">
            @livewire('movimiento-modal')
          </div>
        </div>
    </div>
</x-app-layout>
