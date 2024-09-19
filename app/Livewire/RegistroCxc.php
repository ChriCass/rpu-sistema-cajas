<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TipoDeMoneda;
use App\Models\Mes;
use App\Models\TasaIgv;
use App\Models\TipoDeComprobanteDePagoODocumento;
use App\Models\User;

class RegistroCxc extends Component
{   public $aperturaId;
    public $meses;
    public $comprobantes;
    public $monedas;
    public $tasas;
    public $usuarios;

    public function mount()
    {   
         
    }

    public function mostrarRegistro(){
        $this->dispatch('mostrarDocumentosCxc');
    }
   

    
    public function render()
    {
        return view('livewire.registro-cxc') ;
    }
}
