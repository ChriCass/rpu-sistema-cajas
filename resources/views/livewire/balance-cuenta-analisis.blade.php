<div>
   
    <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __(ucfirst(str_replace('.', ' ', request()->route()->getName()))) }}
    </h2>
    
    </x-slot>
<main class="w-full px-4 mx-auto mt-5">
    <x-card >
        <div class="  overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">x</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">x</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">x</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">x</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">x</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">x</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">x</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">x</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">x</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">x</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">x</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">x</th>
                    </tr>
                </thead>
                <tbody>
                   
                    <tr>
                        <td class="px-4 py-2 border-b border-gray-300">x</td>
                        <td class="px-4 py-2 border-b border-gray-300">x</td>
                        <td class="px-4 py-2 border-b border-gray-300">x</td>
                        <td class="px-4 py-2 border-b border-gray-300">x</td>
                        <td class="px-4 py-2 border-b border-gray-300">x</td>
                        <td class="px-4 py-2 border-b border-gray-300">x</td>
                        <td class="px-4 py-2 border-b border-gray-300">x</td>
                        <td class="px-4 py-2 border-b border-gray-300">x</td>
                        <td class="px-4 py-2 border-b border-gray-300">x</td>
                        <td class="px-4 py-2 border-b border-gray-300">x</td>
                        <td class="px-4 py-2 border-b border-gray-300">x</td>
                        <td class="px-4 py-2 border-b border-gray-300">x</td>
                    </tr>
                 
                </tbody>
            </table>
        </div>
       <!-- Botones -->
       <div class="flex justify-end mt-4 space-x-2">
        <x-button label="Cancelar" outline secondary wire:navigate href="{{ route('dashboard') }}" />
    
    
         <!-- Aqui habria algun boton de eliminado(?) -->
      </x-card>
</main>
  
     
</div>
