<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Familia;
use App\Models\SubFamilia;
use App\Models\Detalle;
use App\Models\TasaIgv;
use App\Models\TipoDeMoneda;
use App\Models\TipoDeComprobanteDePagoODocumento;
use Illuminate\Support\Facades\Log;
use App\Models\Documento;
use App\Models\Apertura;
use DateTime;
use App\Models\Entidad;
use App\Models\TipoDocumentoIdentidad;
use App\Models\TipoDeCaja;
use App\Models\TipoDeCambioSunat;
use App\Models\MovimientoDeCaja;
use Illuminate\Support\Facades\Auth;

class RegistroDocumentosEgreso extends Component
{
    public $aperturaId;
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
    public $destinatarioVisible = false; // Mostrar u ocultar destinatario
    public $tipoDocDescripcion;
    public $observaciones;
    public $entidad;
     public $nuevoDestinatario;


    public $familias = []; // Lista de familias
    public $subfamilias = []; // Lista de subfamilias filtradas
    public $detalles = []; // Lista de detalles filtrados
    public $tasasIgv = []; // Lista de tasas de IGV
    public $monedas = []; // Lista de monedas

    public $tipoDocIdentidades;
    public $disableFields = false; // Para manejar el estado de desactivación de campos
    public $destinatarios;

    public $user;

    /////

    public $apertura;

    public $basImp = 0;
    public $igv = 0;
    public $otrosTributos = 0;
    public $noGravado = 0;
    public $precio = 0;
    
    public function mount($aperturaId)
    {
        $this->aperturaId = $aperturaId;
    }
    public function render()
    {
        return view('livewire.registro-documentos-egreso');
    }
}
