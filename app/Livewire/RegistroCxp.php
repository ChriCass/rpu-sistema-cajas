<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Mes;
Use App\Models\TipoDeComprobanteDePagoODocumento;
use App\Models\TipoDeMoneda;
use App\Models\TasaIgv;
use App\Models\User;


class RegistroCxp extends Component
{     
    public $aperturaId;
    public $meses;
    public $comprobantes;
    public $monedas;
    public $tasas;
    public $usuarios;
    public $mostrarRegistroDocumentos = false;


    public function mostrarRegistro(){
        $this->dispatch('mostrarDocumentosCxp');
    }
    public function mount()
    {   
        $this->meses = Mes::all();
       
    
        // Recuperar los comprobantes, garantizando que todos tengan descripción
        $this->comprobantes = TipoDeComprobanteDePagoODocumento::whereNotIn('id', ['70', '71', '72', '73', '80'])
                                ->whereNotNull('descripcion')
                                ->get();
        $this->monedas = TipoDeMoneda::all();
        $this->tasas = TasaIgv::all();
        $this->usuarios = User::all();
    }
    
    public function render()
    {
        return view('livewire.registro-cxp');
    }
}
