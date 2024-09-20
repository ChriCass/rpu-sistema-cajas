<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Mes;
Use App\Models\TipoDeComprobanteDePagoODocumento;
use App\Models\TipoDeMoneda;
use App\Models\TasaIgv;
use App\Models\User;
use App\Models\Documento;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
class RegistroCxp extends Component
{     
     
     
    public $mostrarAlerta = false;
 

    public function mostrarRegistro(){
        $this->dispatch('mostrarDocumentosCxp');
    }
   
  

    #[On('mostrarAlerta')]
    public function setMostrarAlerta()
    {
        $this->mostrarAlerta = true;
    }
     
    public function render()
    {
        return view('livewire.registro-cxp');
    }
}
