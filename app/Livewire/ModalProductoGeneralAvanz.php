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
    public $tasaImpositiva;

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

    public function sendingProductoTabla()
    {
        $this->validate([
            'codigoProducto' => 'required',
            'productoSeleccionado' => 'required',
            'cantidad' => 'required|integer|min:1',
            'precioUnitario' => 'required|numeric|min:0.01',
            'tasaImpositiva' => 'required',
            'total' => 'required'
        ]);
    
        // Preparar los datos a enviar
        $data = [
             
            'codigoProducto' => $this->codigoProducto,
            'productoSeleccionado' => $this->productoSeleccionado,
            'cantidad' => $this->cantidad,
            'precioUnitario' => $this->precioUnitario,
            'total' => $this->total,
            'tasaImpositiva' => $this->tasaImpositiva,
        ];
    
        // Enviar los datos con un evento usando dispatch
        $this->dispatch('productoEnviado', $data);
    
        // Cerrar el modal y limpiar los campos si es necesario
        $this->reset(['productoSeleccionado', 'codigoProducto', 'cantidad', 'precioUnitario', 'total', 'tasaImpositiva']);
        $this->openModal = false;
    
        // También puedes mostrar un mensaje de éxito
        session()->flash('message', 'Producto enviado exitosamente.');
    }
    

    public function render()
    {
        return view('livewire.modal-producto-general-avanz');
    }
}
