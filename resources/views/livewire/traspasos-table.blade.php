<div>
    <div class="p-4 bg-white rounded shadow-md">
        <!-- Alertas de WireUI -->
        @if(session()->has('message'))
            <x-alert title="Success Message!" positive padding="none">
                <x-slot name="slot">
                    {{ session('message') }} — <b>¡Éxito!</b>
                </x-slot>
            </x-alert>
        @endif

        @if(session()->has('error'))
            <x-alert title="Error Message!" negative padding="small">
                <x-slot name="slot">
                    {{ session('error') }} — <b>Error encontrado</b>
                </x-slot>
            </x-alert>
        @endif

        @if(session()->has('warning'))
            <x-alert title="Alert Message!" warning padding="medium">
                <x-slot name="slot">
                    {{ session('warning') }} — <b>Atención</b>
                </x-slot>
            </x-alert>
        @endif

      
        <div class="flex items-center space-x-4 mb-4">
            <!-- Filtro Mes -->
            <div>
                <x-select
                    label="Mes"
                    placeholder="Filtrar por mes"
                    :options="$meses"
                    option-label="descripcion"
                    option-value="id"
                    x-model="filtroMes"
                    @change="$wire.filterByMonth(filtroMes)"
                />
            </div>
            <!-- Filtro Año -->
            <div>
                <x-select
                    label="Año"
                    placeholder="Filtrar por año"
                    :options="$anios"
                    option-label="anio"
                    option-value="anio"
                    x-model="filtroAnio"
                    @change="$wire.filterByYear(filtroAnio)"
                />
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
                    @foreach($traspasos as $traspaso)
                        <tr>
                            <td class="px-4 py-2 border-b border-gray-300">{{ $traspaso['apl'] }}</td>
                            <td class="px-4 py-2 border-b border-gray-300">{{ $traspaso['fec'] }}</td>
                            <td class="px-4 py-2 border-b border-gray-300">{{ $traspaso['mov'] }}</td>
                            <td class="px-4 py-2 border-b border-gray-300">{{ $traspaso['monto'] }}</td>
                            <td class="px-4 py-2 border-b border-gray-300">{{ $traspaso['monto_do'] }}</td>
                            <td class="px-4 py-2 border-b border-gray-300">
                                <x-button 
                                    label="Detalles" 
                                    wire:click="$dispatch('setFec', '{{ $traspaso['fec'] }}')" 
                                    wire:navigate 
                                    href="{{ route('traspaso.show', ['traspasoId' => $traspaso['mov']]) }}" 
                                />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
