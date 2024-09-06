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

    public function mount($aperturaId)
    {   
        $this->meses = Mes::all();
        $this->aperturaId = $aperturaId;
    
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
        return view('livewire.registro-cxc') ;
    }
}
