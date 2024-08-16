<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __(ucfirst($routeName)) }}
        </h2>
    </x-slot>

    <div class="max-w-screen-xl mx-auto px-4 mt-12">
        <div class="flex flex-wrap justify-center -mx-4">
            <div class="w-full sm:w-8/12 px-4 mb-4">
                <div class="bg-white rounded-md p-4 shadow-lg">
        
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Codigo</th>
                                    <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Familia</th>
                                    <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Sub-Familia</th>
                                    <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Detalle</th>
                                    <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Descripcion</th>
                                </tr>
                            </thead>
                            <tbody>
                      
                            </tbody>
                            
                        </table>
       
                </div>
            </div>

        </div>
        <div class="w-full px-4 mb-4">
            <div class="flex justify-center">
                @livewire('producto-modal')
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
