<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Producto;


class ModalProductoGeneralAvanz extends Component
{   
    public $productos;
    public $productoSeleccionado;  // ID del producto seleccionado
    public $codigoProducto;        // Código del producto
    public $openModal = false;
    public $cantidad = 0;
    public $precioUnitario = 0;
    public $total = 0;

    // Hook de Livewire para detectar cambios en las propiedades y calcular el total
     // Se ejecuta cuando cambia la propiedad 'cantidad'
     public function updatedCantidad()
     {
         $this->calcularTotal();
     }
 
     // Se ejecuta cuando cambia la propiedad 'precioUnitario'
     public function updatedPrecioUnitario()
     {
         $this->calcularTotal();
     }
 
     // Método que calcula el total
     private function calcularTotal()
     {
         $this->total = $this->cantidad * $this->precioUnitario;
     }

    public function mount()
    {
        $this->productos = Producto::all();
    }

    // Hook de Livewire que se ejecuta cuando una propiedad cambia
    public function updatedProductoSeleccionado($valor)
    {
        $producto = Producto::find($valor);
        $this->codigoProducto = $producto ? $producto->id : '';
    }


    public function render()
    {
        return view('livewire.modal-producto-general-avanz');
    }
}
