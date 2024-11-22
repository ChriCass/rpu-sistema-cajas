<?php

namespace App\Livewire;

use App\Models\DDetalleDocumento;
use App\Models\Documento;
use App\Models\MovimientoDeCaja;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class DeleteModalFormAvanz extends Component
{
    public $openModal;
 
    public $origen; // Declarar la propiedad pública

    public function mount($origen)
    {
        $this->origen = $origen; // Inicializar el parámetro recibido
    }

    public function delete()
{
    
}
    public function render()
    {
        return view('livewire.delete-modal-form-avanz');
    }
}
