<div>
    @if($row->estado == 1)
        <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full">
            Activo
        </span>
    @else
        <span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full">
            Inactivo
        </span>
    @endif
</div> 