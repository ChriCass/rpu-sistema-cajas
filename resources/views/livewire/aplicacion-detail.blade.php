<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Vaucher de Pago
        </h2>
    </x-slot>

    <div class="p-4">
        <x-card>
            @livewire('edit-aplicacion-detail', ['aplicacionesId' => $aplicacionesId])
            
         
        </x-card>
    </div>
{{-- 
@if($showFormEdit && $detallesRecibidos)
    <!-- Si la variable showFormEdit es verdadera y detallesRecibidos también es verdadero,
         se mostrará el formulario para editar la aplicación -->
    <div class="p-4">
        <!-- Renderiza el componente Livewire form-edit-aplicacion-detail, 
             pasando los detalles, fecha y aplicacionesId como parámetros -->
        @livewire('form-edit-aplicacion-detail', ['detalles' => $detalles, 'fecha' => $fecha, 'aplicacionesId' => $aplicacionesId])
    </div>
@endif
--}}


</div>
