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
                    <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">N</th>
                    <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Familia</th>
                    <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Sub-Familia</th>
                    <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Detalle</th>
                    <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Entidad</th>
                    <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Numero</th>
                    <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Monto</th>
                    <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Glosa</th>
                    <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Detalle</th>
                </tr>
            </thead>
            <tbody>
                @if($movimientos)
                    @foreach($movimientos as $movimiento)
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $movimiento->mov_id }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $movimiento->familia }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $movimiento->subfamilia }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $movimiento->detalle }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $movimiento->entidad }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $movimiento->numero }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $movimiento->monto }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $movimiento->glosa }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">
                                @php
                                    $this->determinarFormulario($movimiento);
                                @endphp
                                <a href="{{ $this->rutaFormulario }}">
                                    <x-button label="Detalle" />
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8" class="py-2 px-4 border-b border-gray-200">No hay movimientos</td>
                    </tr>
                @endif
            </tbody>
            
        </table>
    </div>
</div>
