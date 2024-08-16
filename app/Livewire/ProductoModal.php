<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SubFamilia;
use App\Models\Familia;
use App\Models\Detalle;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
class ProductoModal extends Component
{   
    public $openModal = false;

    public $familias = [];
    public $subfamilias = [];
    public $detalles = [];
    
    public $selectedFamilia = null;
    public $selectedSubfamilia = null;
    public $selectedDetalle = null;

    public $codigo;


    public $producto;

    public function mount()
    {
        // Cargar todas las familias al montar el componente
        $this->familias = Familia::all();
        Log::info('Componente montado, familias cargadas', ['familias' => $this->familias]);
    }

    public function updatedSelectedFamilia($familiaId)
    {
        // Asegurarse de que el ID de la familia se maneje como cadena
        $familiaId = (string) $familiaId;
    
        // Actualizar subfamilias cuando se selecciona una familia
        $this->subfamilias = SubFamilia::where('id_familias', $familiaId)
            ->selectRaw('CAST(id AS CHAR) AS id, id_familias, desripcion')
            ->get();
        
        $this->detalles = collect(); // Limpiar detalles cuando cambia la familia
        Log::info('Familia seleccionada', ['familiaId' => $familiaId, 'subfamilias' => $this->subfamilias]);
    
        // Resetear las selecciones
        $this->selectedSubfamilia = null;
        $this->selectedDetalle = null;
        Log::info('Subfamilia y detalle reseteados');
    }
    
    public function updatedSelectedSubfamilia($subfamiliaId)
    {
        // Asegurarse de que el ID de la subfamilia se maneje como cadena
        $subfamiliaId = (string) $subfamiliaId;
        
        // Actualizar detalles cuando se selecciona una subfamilia
        $this->detalles = Detalle::where('id_subfamilia', $subfamiliaId)
            ->selectRaw('CAST(id AS CHAR) AS id, id_subfamilia, descripcion')
            ->get();
    
        Log::info('Subfamilia seleccionada', ['subfamiliaId' => $subfamiliaId, 'detalles' => $this->detalles]);
    
        // Resetear la selección de detalle
        $this->selectedDetalle = null;
        Log::info('Detalle reseteado');
    }
    
     // Nueva función para generar un código único alfanumérico de 6 caracteres
     public function generateUniqueCode()
     {
         // Generar un código alfanumérico de 6 caracteres y convertirlo a mayúsculas
         $this->codigo = strtoupper(Str::random(6));
     
         Log::info('Código generado', ['codigo' => $this->codigo]);
     }
     

     public function showModal()
     {
         // Generar un nuevo código al abrir el modal
         $this->generateUniqueCode();
         $this->openModal = true;
     }

 

    public function render()
    {
        return view('livewire.producto-modal', [
            'familias' => $this->familias,
            'subfamilias' => $this->subfamilias,
            'detalles' => $this->detalles,
        ]);
    }
}
