<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Familia;
use App\Models\TasaIgv;
use App\Models\TipoDeMoneda;
use App\Models\Detalle;


class RegistroDocumentosCxp extends Component
{
    
    public $familias = []; // Lista de familias
    public $subfamilias = []; // Lista de subfamilias filtradas
    public $detalles = []; // Lista de detalles filtrados
    public $tasasIgv = []; // Lista de tasas de IGV
    public $monedas = []; // Lista de monedas
    public $disableFields = false;
    public $tipoDocIdentidades;
    public $visible;
    public $destinatarioVisible = false; // Mostrar u ocultar destinatario


    #[On('mostrarDocumentosCxp')] 
    public function mostrar()
    {
        $this->visible = true;
    }

    public function loadInitialData()
    {
        $this->familias = Familia::where('id', 'like', '0%')->get();
        $this->tasasIgv = TasaIgv::all();
        $this->monedas = TipoDeMoneda::all();
        $this->detalles = Detalle::all();
    }

    public function render()
    {
        return view('livewire.registro-documentos-cxp');
    }
}
