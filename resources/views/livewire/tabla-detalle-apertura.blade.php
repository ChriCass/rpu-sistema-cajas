<div>
    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto mt-5">
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">NumeroMovimiento</th>
                    <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Familia</th>
                    <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">SubFamilia</th>
                    <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Detalle</th>
                    <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">DetalleProducto</th>
                    <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Entidad</th>
                    <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">NumeroDocumento</th>
                    <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Monto</th>
                    <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Glosa</th>
                    <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if ($movimientos)
                    @foreach ($movimientos as $movimiento)
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $movimiento->NumeroMovimiento }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $movimiento->Familia }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $movimiento->SubFamilia }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $movimiento->Detalle }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $movimiento->DetalleProducto }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $movimiento->Entidad }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $movimiento->NumeroDocumento }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $movimiento->Monto }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $movimiento->Glosa }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">
                                <x-button wire:click="editarMovimiento({{ $movimiento->Monto }}, '{{ $movimiento->NumeroMovimiento }}')" label="Editar" warning />
                            </td>
                            
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="9" class="py-2 px-4 border-b border-gray-200">No hay movimientos</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
