<div>
    <div class="p-4 bg-white rounded shadow-md">
        <div class="flex items-center space-x-4 mb-4">
            <!-- Filtro Mes -->
            <div>
                <label for="mes" class="block text-sm font-medium text-gray-700">Mes:</label>
                <select id="mes" name="mes" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm rounded-md">
                    <option>Todos</option>
                    <!-- Opciones adicionales -->
                </select>
            </div>
            <!-- Filtro Año -->
            <div>
                <label for="anio" class="block text-sm font-medium text-gray-700">Año:</label>
                <select id="anio" name="anio" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm rounded-md">
                    <option>2024</option>
                    <!-- Opciones adicionales -->
                </select>
            </div>
        </div>
    
        <!-- Tabla -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Cuenta</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Fecha</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Mov</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Monto</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Monto Do</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($aplicaciones as $aplicacion)
                        <tr>
                            <td class="px-4 py-2 border-b border-gray-300">{{ $aplicacion['apl'] }}</td>
                            <td class="px-4 py-2 border-b border-gray-300">{{ $aplicacion['fec'] }}</td>
                            <td class="px-4 py-2 border-b border-gray-300">{{ $aplicacion['mov'] }}</td>
                            <td class="px-4 py-2 border-b border-gray-300">{{ $aplicacion['monto'] }}</td>
                            <td class="px-4 py-2 border-b border-gray-300">{{ $aplicacion['monto_do'] }}</td>
                            <td class="px-4 py-2 border-b border-gray-300"> <x-button 
                                label="Detalles" wire:click="$dispatch('setFec', '{{ $aplicacion['fec'] }}')" wire:navigate 
                                href="{{ route('aplicacion.show', ['aplicacionesId' => $aplicacion['mov']]) }}"
                            /></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
</div>
