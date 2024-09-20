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
            <strong>Detalles del Documento con ID {{$documentoCxp->id}}</strong>
            <p>Fecha de Emisión: {{$documentoCxp->fechaEmi}}</p>
            <p>Tipo de Documento: {{$documentoCxp->tipoDocumento}}</p>
            <p>Entidad: {{$documentoCxp->entidadDescripcion}}</p>
            <p>Serie y Número: {{$documentoCxp->serie}} - {{$documentoCxp->numero}}</p>
            <p>Moneda: {{$documentoCxp->id_t04tipmon}}</p>
            <p>Tasa: {{$documentoCxp->tasa}}</p>
            <p>Precio: {{$documentoCxp->precio}}</p>
            <p>Usuario: {{$documentoCxp->usuario}}</p>
        </div>
    </x-card>
    @endif
</div>
