<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;



class RegistroDocumentosCxp extends Component
{
    
    public $familias = []; // Lista de familias
    public $subfamilias = []; // Lista de subfamilias filtradas
    public $detalles = []; // Lista de detalles filtrados
    public $tasasIgv = []; // Lista de tasas de IGV
    public $monedas = []; // Lista de monedas
    public $disableFields = false;

    public $visible;

    #[On('mostrarDocumentosCxp')] 
    public function mostrar()
    {
        $this->visible = true;
    }

     

    public function render()
    {
        return view('livewire.registro-documentos-cxp');
    }
}
