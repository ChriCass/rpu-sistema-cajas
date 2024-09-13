<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Familia;
use App\Models\SubFamilia;
use App\Models\Cuenta;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Models\Detalle;
use Livewire\Attributes\On;
class DetalleModal extends Component
{
    public $openModal = false;
    public $cuentas = [];
    public $familia_id;
    public $subfamilia_id;
    public $cuenta_id;
    public $nuevo_producto;
    public $nuevo_id;

    
    protected $rules = [
        'familia_id' => 'required',
        'subfamilia_id' => 'required',
        'nuevo_producto' => 'required|string|max:255',
        'cuenta_id' => 'required',
    ];

    protected $messages = [
        'nuevo_producto.required' => 'El campo descripción es obligatorio.',
        'nuevo_producto.max' => 'La descripción no puede tener más de 255 caracteres.',
        'familia_id.required' => 'Debe seleccionar un tipo de familia.',
        'subfamilia_id.required' => 'Debe seleccionar un tipo de subfamilia.',
        'cuenta_id.required' => 'Debe seleccionar un tipo de cuenta',
         
    ];

    #[On('famysub selected')]
    public function asignarIdFamiliasIdsubfamilias($familiaId, $subfamiliaId)
    {
        Log::info('Evento recibido: famysub selected', ['familiaId' => $familiaId, 'subfamiliaId' => $subfamiliaId]);
        $this->familia_id = $familiaId;
        $this->subfamilia_id = $subfamiliaId;
    }


    public function insertNewProducto()
    {
        if (empty($this->familia_id) || empty($this->subfamilia_id)) {
            session()->flash('error', 'Debe seleccionar una familia y una subfamilia.');
            return;
        }
    
        $this->validate();
    
        DB::beginTransaction();
    
        try {
            // Obtener el id de la familia seleccionada
            $fam = (string) $this->familia_id;
    
            // Obtener el id de la subfamilia seleccionada
            $subfam = (string) $this->subfamilia_id;
    
            Log::info('Valores de familia y subfamilia', ['fam' => $fam, 'subfam' => $subfam]);
    
            // Obtener el último ID existente para la combinación de familia y subfamilia
            $lastId = Detalle::lockForUpdate() // Bloqueo para evitar concurrencia
                ->where('id_familias', $fam)
                ->where('id_subfamilia', $subfam)
                ->orderByRaw('CAST(id AS UNSIGNED) DESC')
                ->value('id');
    
            Log::info('Último ID obtenido', ['lastId' => $lastId]);
    
            if ($lastId) {
                // Incrementar el último ID en 1
                $newIdNumber = (int) substr($lastId, strlen($fam . $subfam)) + 1;
                // Formatear el nuevo ID
                $this->nuevo_id = $fam . $subfam . str_pad($newIdNumber, 3, '0', STR_PAD_LEFT);
            } else {
                // Si no hay registros existentes, comenzar desde "001"
                $this->nuevo_id = $fam . $subfam . "001";
            }
    
            Log::info('Nuevo ID generado', ['nuevo_id' => $this->nuevo_id]);
    
            // Crear el nuevo producto en la tabla detalle
            Detalle::create([
                'id_familias' => $this->familia_id,
                'id_subfamilia' => $this->subfamilia_id,
                'id' => $this->nuevo_id,
                'descripcion' => $this->nuevo_producto,
                'id_cuenta' => $this->cuenta_id,
            ]);
        ////////////////////////////////
            // Confirmar la transacción
            DB::commit();
    
            // Emitir el evento para refrescar la tabla
            $this->dispatch('producto-created');
    
            // Limpiar campos después de insertar
            $this->reset(['familia_id', 'subfamilia_id', 'nuevo_id', 'nuevo_producto', 'cuenta_id']);
    
            // Emitir un mensaje de éxito
            session()->flash('message', 'Producto creado exitosamente.');
    
            Log::info('Producto creado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear el producto', ['exception' => $e]);
            session()->flash('error', 'Ocurrió un error al crear el producto.');
        }
    }
    
     
    public function mount()
    {
        $this->cuentas = Cuenta::all() ;
        
    }
 

    public function render()
    {
         
        return view('livewire.detalle-modal'  );
    }
}
