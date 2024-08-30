<div>
    <div class="p-4 bg-white rounded shadow-md">
        <!-- Filtros de Fecha y Moneda -->
        <div class="flex items-center justify-between space-x-4 mb-4">
            <!-- Campo Fecha -->
            <div>
                <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha:</label>
                <input 
                    type="date" 
                    id="fecha" 
                    name="fecha" 
                    wire:model.live="fecha" 
                    class="mt-1 block w-full pl-3 pr-3 py-2 border-gray-300 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm rounded-md" 
                    value="2024-05-27"
                />
                    <!-- Campo Moneda -->
            <div>
                <label for="moneda" class="block text-sm font-medium text-gray-700">Moneda:</label>
                <select 
                    id="moneda" 
                    name="moneda" 
                    wire:model="moneda" 
                    class="mt-1 block w-full pl-3 pr-3 py-2 border-gray-300 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm rounded-md">
                    <option value="PEN">PEN</option>
                    <!-- Otras opciones de moneda -->
                </select>
            </div>
            </div>
        

            <!-- Botón Nuevo -->
            <div class="ml-auto">
                <x-button label="Nuevo" positive />
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="flex space-x-2 mb-4">
            <x-button label="Ingreso" primary />
            <x-button label="Gasto" secondary />
            <!-- Input de búsqueda -->
            <x-input placeholder="Buscar..." class="w-full" />
        </div>

        <!-- Tabla -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Id</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">T.doc</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Entidades</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Descripcion</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Num</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Moneda</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Cuenta</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Monto</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Debe</th>
                        <th class="px-4 py-2 border-b border-gray-300 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Haber</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-4 py-2 border-b border-gray-300">39</td>
                        <td class="px-4 py-2 border-b border-gray-300">Voucher de Tra</td>
                        <td class="px-4 py-2 border-b border-gray-300">20606566558</td>
                        <td class="px-4 py-2 border-b border-gray-300">TECNICOS MECANICOS TAMB</td>
                        <td class="px-4 py-2 border-b border-gray-300">0000-4</td>
                        <td class="px-4 py-2 border-b border-gray-300">PEN</td>
                        <td class="px-4 py-2 border-b border-gray-300">TRANSFERENCI</td>
                        <td class="px-4 py-2 border-b border-gray-300">6160.7</td>
                        <td class="px-4 py-2 border-b border-gray-300">6160.7</td>
                        <td class="px-4 py-2 border-b border-gray-300">6160.7</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 border-b border-gray-300">340</td>
                        <td class="px-4 py-2 border-b border-gray-300">Voucher de Tra</td>
                        <td class="px-4 py-2 border-b border-gray-300">20606566558</td>
                        <td class="px-4 py-2 border-b border-gray-300">TECNICOS MECANICOS TAMB</td>
                        <td class="px-4 py-2 border-b border-gray-300">0000-3</td>
                        <td class="px-4 py-2 border-b border-gray-300">PEN</td>
                        <td class="px-4 py-2 border-b border-gray-300">TRANSFERENCI</td>
                        <td class="px-4 py-2 border-b border-gray-300">6160.7</td>
                        <td class="px-4 py-2 border-b border-gray-300">6160.7</td>
                        <td class="px-4 py-2 border-b border-gray-300">6160.7</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Totales y Botones de acción -->
        <div class="flex justify-between items-center mt-4">
            <div>
                <x-input readonly value="0" />
                <x-input readonly value="6160.7" />
                <x-input readonly value="6160.7" />
            </div>
            <div class="flex space-x-2">
                <x-button label="Cancelar" class="bg-gray-300 hover:bg-gray-400 text-gray-700" />
                <x-button label="Aceptar" class="bg-teal-500 hover:bg-teal-600 text-white" />
            </div>
        </div>
    </div>
</div>
