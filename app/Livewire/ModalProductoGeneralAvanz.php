<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Producto;
use App\Models\CentroDeCostos;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class ModalProductoGeneralAvanz extends Component
{   
    public $productos;
    public $productoSeleccionado;  // ID del producto seleccionado
    public $productoSelecDescripcion;
    public $codigoProducto;        // Código del producto
    public $openModal = false;
    public $cantidad;
    public $precioUnitario;
    public $total;
    public $tasaImpositiva;
    public $CentroDeCostos;
    public $CC;

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
        try {
            $cantidad = empty($this->cantidad) ? 0 : $this->cantidad;
            $precioUnitario = empty($this->precioUnitario) ? 0 : $this->precioUnitario;
    
            $this->total = $cantidad * $precioUnitario;
        } catch (\Throwable $e) {
            // Manejar el error, por ejemplo, registrar el error y establecer un valor predeterminado
            $this->total = 0;
            Log::error("Error al calcular el total: " . $e->getMessage());
        }
     }

     public function mount()
     {
         $this->productos = DB::table('l_productos')
         ->select('l_productos.id',DB::raw("CONCAT(l_productos.descripcion, '/', detalle.descripcion, '/', familias.descripcion) as descripcion"))
         ->leftJoin('detalle', 'detalle.id', '=', 'l_productos.id_detalle')
         ->leftJoin('familias', 'familias.id', '=', 'detalle.id_familias')
         ->leftJoin('cuentas', 'cuentas.id', '=', 'detalle.id_cuenta')
         ->whereIn('id_tcuenta', [2, 3])
         ->where('id_tipofamilias', '=', 2)
         ->where(DB::raw("LEFT(familias.id, 1)"), '<>', '0')
         ->get();
         $this->CentroDeCostos = CentroDeCostos::all();
     }
     

    // Hook de Livewire que se ejecuta cuando una propiedad cambia
    public function updatedProductoSeleccionado($valor)
    {
        // Buscar el producto y su detalle usando Query Builder
        $producto = DB::table('l_productos as p')
            ->join('detalle as d', 'd.id', '=', 'p.id_detalle')
            ->select('p.id', 'p.descripcion as producto_descripcion', 'd.descripcion as detalle_descripcion')
            ->where('p.id', $valor)
            ->first();  // Usamos first() para obtener un solo resultado
    
        // Asignar los valores a las propiedades correspondientes
        if ($producto) {
            $this->codigoProducto = $producto->id;
            $this->productoSelecDescripcion = "{$producto->producto_descripcion} / {$producto->detalle_descripcion}";
        } else {
            // Resetear si no se encuentra el producto
            $this->codigoProducto = '';
            $this->productoSelecDescripcion = '';
        }
    }
    
    public function sendingProductoTabla()
    {
        $this->validate([
            'codigoProducto' => 'required',
            'productoSelecDescripcion' => 'required',
            'cantidad' => 'required|numeric|min:0.01',
            'precioUnitario' => 'required|numeric|min:0.01',
            'tasaImpositiva' => 'required',
            'total' => 'required',
            'CC' => 'nullable',
        ]);
    
        // Preparar los datos a enviar
        $data = [
            'codigoProducto' => $this->codigoProducto,
            'productoSeleccionado' => $this->productoSelecDescripcion,
            'cantidad' => $this->cantidad,
            'precioUnitario' => $this->precioUnitario,
            'total' => $this->total,
            'tasaImpositiva' => $this->tasaImpositiva,
            'CC' => $this->CC,
        ];
    
        // Registrar los datos en el log
        Log::info('Enviando producto a la tabla:', $data);
    
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
