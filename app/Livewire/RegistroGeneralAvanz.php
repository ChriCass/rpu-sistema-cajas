<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;


class RegistroGeneralAvanz extends Component
{

    public $aperturaId;
    public $origen;
    public $productos;
    public function mount()
    {
        $this->aperturaId = request()->get('aperturaId');
        $this->origen = request()->get('origen' ); // Valor por defecto: 'ingreso'

        
        // Verificar si hay productos en la sesión y cargarlos
        $this->productos = Session::get('productos', []);
    }

     // Escuchar el evento 'productoEnviado' utilizando #[On] 
     #[On('productoEnviado')]
     public function procesarProducto($data)
     {
         Log::info('Datos del producto recibidos:', $data);
 
         // Agregar el producto a la lista de productos en la sesión
         $this->productos[] = $data;
 
         // Guardar la lista actualizada en la sesión
         Session::put('productos', $this->productos);
  
     }

        // Función para eliminar un producto específico por su índice
    public function eliminarProducto($index)
    {
        // Eliminar el producto de la lista
        unset($this->productos[$index]);

        // Reindexar el array para evitar huecos
        $this->productos = array_values($this->productos);

        // Guardar la lista actualizada en la sesión
        Session::put('productos', $this->productos);
    }


    public function render()
    {
        return view('livewire.registro-general-avanz')->layout('layouts.app');

    }
}
