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
class DetalleModal extends Component
{
    public $openModal = false;
    public $cuentas = [];
 
     
    public function consultas()
    {
        $this->cuentas = Cuenta::all() ;
        
    }
 

    public function render()
    {
        $this->consultas();
        return view('livewire.detalle-modal', ['cuentas' => $this->cuentas]  );
    }
}
