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
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">ID</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">NUM ENTIDAD</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">DES ENTIDAD</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">T. DOC</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">SERIE</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">NUMERO</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">CUENTA</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">MONTO</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">OBSERVACIONES</th>
                    </tr>
                </thead>
                <tbody>
                   
                    @if(!empty($registros) && $registros->count())
                        @foreach ($registros as $registro)
                        <tr>
                            <td class="px-4 py-2 border-b">{{ $registro->id_documentos }}</td>
                            <td class="px-4 py-2 border-b">{{ $registro->id_entidades }}</td>
                            <td class="px-4 py-2 border-b">{{ $registro->entidades }}</td>
                            <td class="px-4 py-2 border-b">{{ $registro->tdoc }}</td>
                            <td class="px-4 py-2 border-b">{{ $registro->serie }}</td>
                            <td class="px-4 py-2 border-b">{{ $registro->numero }}</td>
                            <td class="px-4 py-2 border-b">{{ $registro->cuenta }}</td>
                            <td class="px-4 py-2 border-b">{{ number_format($registro->monto ?? 0, 2, '.', ',') }}</td>
                            <td class="px-4 py-2 border-b">{{ $registro->observaciones }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td class="px-4 py-2 border-b"></td>
                            <td class="px-4 py-2 border-b"></td>
                            <td class="px-4 py-2 border-b"></td>
                            <td class="px-4 py-2 border-b"></td>
                            <td class="px-4 py-2 border-b"></td>
                            <td class="px-4 py-2 border-b"></td>
                            <td class="px-4 py-2 border-b font-bold">TOTAL</td>
                            <td class="px-4 py-2 border-b font-bold">{{ number_format($totales ?? 0, 2, '.', ',') }}</td>
                            <td class="px-4 py-2 border-b"></td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="10" class="px-4 py-2 border-b text-center">No hay movimientos disponibles</td>
                        </tr>
                    @endif
                 
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
