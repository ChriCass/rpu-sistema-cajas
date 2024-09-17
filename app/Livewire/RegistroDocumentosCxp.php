<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Familia;
use App\Models\TasaIgv;
use App\Models\TipoDeMoneda;
use App\Models\Detalle;
use App\Models\SubFamilia;
use App\Models\TipoDocumentoIdentidad;

use App\Models\TipoDeComprobanteDePagoODocumento;

use Illuminate\Support\Facades\Auth;

class RegistroDocumentosCxp extends Component
{

    public $familiaId; // ID de la familia seleccionada
    public $subfamiliaId; // ID de la subfamilia seleccionada
    public $detalleId; // ID del detalle seleccionado
    public $tasaIgvId; // ID de la tasa de IGV seleccionada
    public $monedaId; // ID de la moneda seleccionada
    public $tipoDocumento; // ID del tipo de documento seleccionado
    public $serieNumero1; // Parte 1 del número de serie
    public $serieNumero2; // Parte 2 del número de serie
    public $tipoDocId; // Tipo de documento de identificación
    public $docIdent; // Documento de identidad
    public $fechaEmi; // Fecha de emisión
    public $fechaVen; // Fecha de vencimiento
    public $tipoDocDescripcion;
    public $observaciones;
    public $entidad;
    public $nuevoDestinatario;
    public $centroDeCostos; // Abelardo = Recoje el centro de costos

    public $familias = []; // Lista de familias
    public $subfamilias = []; // Lista de subfamilias filtradas
    public $detalles = []; // Lista de detalles filtrados
    public $tasasIgv = []; // Lista de tasas de IGV
    public $monedas = []; // Lista de monedas
    public $disableFields = false;
    public $tipoDocIdentidades;
    public $visible;
    public $destinatarioVisible = false; // Mostrar u ocultar destinatario

    public $user;

    public $detraccion;
    public $porcetajeDetraccion;


    public $basImp;
    public $igv = 0;
    public $otrosTributos = 0;
    public $noGravado = 0;
    public $precio = 0;
    

    #[On('mostrarDocumentosCxp')]
    public function mostrar()
    {
        $this->visible = true;
    }

    public function calculateIgv()
{
    // Ensure the baseImponible is not null or zero
    if (!$this->basImp || !$this->tasaIgvId) {
        return;
    }

    // Calculate IGV based on the selected tasa
    switch ($this->tasaIgvId) {
        case '18%':
            $this->igv = round($this->basImp * 0.18, 2);
            break;
        case '10%':
            $this->igv = round($this->basImp * 0.10, 2);
            break;
        case 'No Gravado':
        default:
            $this->igv = 0; // No IGV applied
            break;
    }
}

// Function to calculate the total price dynamically
public function calculatePrecio()
{
    if (is_numeric($this->basImp) && is_numeric($this->igv) && is_numeric($this->otrosTributos) && is_numeric($this->noGravado)) {
        $this->precio = round($this->basImp + $this->igv + $this->otrosTributos + $this->noGravado, 2);
    }
}

// Livewire hooks for triggering the functions when fields are updated
public function updatedBasImp()
{
    // Calculate IGV based on the updated base imponible
    $this->calculateIgv();

    // Calculate the total price
    $this->calculatePrecio();
}

public function updatedTasaIgvId()
{
    // Recalculate IGV based on the updated tasa
    $this->calculateIgv();

    // Recalculate the total price
    $this->calculatePrecio();
}

public function updatedOtrosTributos()
{
    // Recalculate the total price whenever otros tributos is updated
    $this->calculatePrecio();
}

public function updatedNoGravado()
{
    // Recalculate the total price whenever no gravado is updated
    $this->calculatePrecio();
}
public function buscarDescripcionTipoDocumento()
{
    // Buscar el tipo de documento en la base de datos
    $tipoComprobante = TipoDeComprobanteDePagoODocumento::where('id', $this->tipoDocumento)->first();

    // Si se encuentra el tipo de documento, actualizamos la descripción
    if ($tipoComprobante) {
        $this->tipoDocDescripcion = $tipoComprobante->descripcion;
    } else {
        // Si no se encuentra, puedes asignar un mensaje de error o dejar vacío = Abelardo = Modifique estos datos adaptarlo a la idea
        $this->tipoDocumento = '';
        session()->flash('error', 'Descripción no encontrada');
    }
}


    public function mount()
    {
        $this->tipoDocIdentidades = TipoDocumentoIdentidad::whereIn('id', ['1', '6'])->get();
        $this->user = Auth::user()->id;
        $this->loadInitialData();
    }

    public function updatedFamiliaId($value)
    {
        // Actualizar las subfamilias según la familia seleccionada
        $this->subfamilias = SubFamilia::where('id_familias', $value)->get();
        $this->reset('subfamiliaId', 'detalleId'); // Reiniciar las selecciones
         
    }

    // Método que se ejecuta cuando se selecciona una subfamilia
    public function updatedSubfamiliaId($value)
    {
        // Filtrar los detalles según la subfamilia seleccionada
        $this->detalles = Detalle::where('id_subfamilia', $value)->get();
        $this->reset('detalleId'); // Reiniciar detalle
    }

    public function loadInitialData()
    {
        $this->familias = Familia::where('id', 'not like', '0%')->get();
        $this->tasasIgv = TasaIgv::all();
        $this->monedas = TipoDeMoneda::all();
        $this->detalles = Detalle::all();
    }

    public function render()
    {
        return view('livewire.registro-documentos-cxp');
    }
}
