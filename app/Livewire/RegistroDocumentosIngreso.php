<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Familia;
use App\Models\SubFamilia;
use App\Models\Detalle;
use App\Models\TasaIgv;
use App\Models\TipoDeMoneda;

class RegistroDocumentosIngreso extends Component
{
    public $aperturaId;
    public $familiaId; // Almacena el ID seleccionado de la familia
    public $subfamiliaId; // Almacena el ID seleccionado de la subfamilia
    public $detalleId; // Almacena el ID seleccionado del detalle
    public $tasaIgvId; // Almacena el ID de la tasa de IGV seleccionada
    public $monedaId; // Almacena el ID de la moneda seleccionada

    public $familias = []; // Lista de familias para el select
    public $subfamilias = []; // Lista de subfamilias filtradas
    public $detalles = []; // Lista de detalles filtrados
    public $tasasIgv = []; // Lista de tasas de IGV para el select
    public $monedas = []; // Lista de tipos de moneda para el select

    public function mount($aperturaId)
    {
        $this->aperturaId = $aperturaId;
        $this->loadInitialData();
    }

    // Cargar datos iniciales para los ComboBoxes
    public function loadInitialData()
    {
        // Aquí filtramos las familias para que el ID comience con '0'
        $this->familias = Familia::where('id', 'like', '0%')->get();
        $this->tasasIgv = TasaIgv::all();
        $this->monedas = TipoDeMoneda::all();
    }

    // Método que se ejecuta cuando se selecciona una familia
    public function updatedFamiliaId($value)
    {
        // Filtra las subfamilias basándose en la familia seleccionada
        $this->subfamilias = SubFamilia::where('id_familias', $value)->get();
        $this->reset('subfamiliaId', 'detalleId'); // Reiniciar subfamilia y detalle seleccionados
    }

    // Método que se ejecuta cuando se selecciona una subfamilia
    public function updatedSubfamiliaId($value)
    {
        // Filtra los detalles basándose en la subfamilia seleccionada
        $this->detalles = Detalle::where('id_subfamilia', $value)->get();
        $this->reset('detalleId'); // Reiniciar detalle seleccionado
    }

    public function render()
    {
        return view('livewire.registro-documentos-ingreso');
    }
}
