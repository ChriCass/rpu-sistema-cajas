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

class RegistroCxp extends Component
{     
    
    public $mostrarRegistroDocumentos = false;
    public $documentosCXP;

    public function mostrarRegistro(){
        $this->dispatch('mostrarDocumentosCxp');
    }
   

     
    public function render()
    {
        return view('livewire.registro-cxp');
    }
}
