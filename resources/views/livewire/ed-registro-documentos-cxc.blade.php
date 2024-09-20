<div>
    @if ($visible)
    <div class="flex justify-center mb-4">
        <x-button warning wire:click="$set('visible', false)">
            Cancelar
        </x-button>
    </div>
    <x-card>
        <!-- Componente principal de detalle de apertura -->
        <div class="p-4">
            <strong>Detalles del Documento con ID {{$documentoCxc->id}}</strong>
            <p>Fecha de Emisión: {{$documentoCxc->fechaEmi}}</p>
            <p>Tipo de Documento: {{$documentoCxc->tipoDocumento}}</p>
            <p>Entidad: {{$documentoCxc->entidadDescripcion}}</p>
            <p>Serie y Número: {{$documentoCxc->serie}} - {{$documentoCxc->numero}}</p>
            <p>Moneda: {{$documentoCxc->id_t04tipmon}}</p>
            <p>Tasa: {{$documentoCxc->tasa}}</p>
            <p>Precio: {{$documentoCxc->precio}}</p>
            <p>Usuario: {{$documentoCxc->usuario}}</p>
        </div>
    </x-card>
    @endif
</div>
