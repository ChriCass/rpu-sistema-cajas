<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use App\Models\Documento;
use Illuminate\Support\Facades\Log;
use App\Models\TipoDeComprobanteDePagoODocumento;
use App\Models\TipoDocumentoIdentidad;
use Illuminate\Support\Facades\Auth;
use App\Models\TasaIgv;
use App\Models\TipoDeMoneda;
use App\Models\Detalle;
use App\Models\Familia;
use App\Models\SubFamilia;
use Illuminate\Support\Carbon;
use App\Models\CentroDeCostos;

class EdRegistroDocumentosCxc extends Component
{
    public $visible = false;
    public $idcxc;
    public $documentoCxc;

    // Variables del documento
    public $familiaId;
    public $subfamiliaId;
    public $detalleId;
    public $tipoDocumento;
    public $serieNumero1;
    public $serieNumero2;
    public $tipoDocId;
    public $docIdent;
    public $fechaEmi;
    public $fechaVen;
    public $tipoDocDescripcion;
    public $observaciones;
    public $entidad;
    public $nuevoDestinatario;
    public $centroDeCostos;
    public  $monedaId;
    public        $tasaIgvId;

    // Variables para la consulta secundaria
    public $basImp;
    public $igv = 0;
    public $otrosTributos = 0;
    public $noGravado = 0;
    public $precio = 0;
    public $detraccion;
    public $porcetajeDetraccion;
    public $disableFieldsEspecial;
    public $lenIdenId;
    public  $CC;
    public $PruebaArray;
    // Variables de interfaz
    public $familias = [];
    public $subfamilias = [];
    public $detalles = [];
    public $tasasIgv = [];
    public $monedas = [];
    public $disableFields = false;
    public $tipoDocIdentidades;
    public $destinatarioVisible = false;
    public $user;


    ///tiene detraccion

    public $toggle = false;
    public $monto_detraccion;
    public $monto_neto;

    // Función para actualizar el estado de los inputs
    public function updatedToggle($value)
    {
        if (!$value) {
            $this->monto_detraccion = null; // Reiniciar valores si se desactiva el toggle
            $this->monto_neto = null;
        }
    }
    #[On('showEdCxc')]
    public function showEdcxc($idcxc)
    {
        $this->idcxc = $idcxc;
        $this->consultaBD();
        $this->consultaDetallesCxc();
        // Verificar si el tipo de documento es uno de los restringidos
        if (
            $this->documentoCxc &&
            ($this->documentoCxc->tipoDocumento === "Vaucher de Transferencia" ||
                $this->documentoCxc->tipoDocumento === "Comprobante de Anticipo" ||
                $this->documentoCxc->tipoDocumento === "Vaucher de Rendicion")
        ) {
            $this->dispatch('mostrarAlerta');
            $this->visible = false; // No mostrar el componente si es un documento restringido
        } else {
            $this->visible = true; // Mostrar el componente si el documento es válido
        }
    }

    public function consultaBD()
    {
        if ($this->idcxc) {
            $this->documentoCxc = Documento::select(
                'documentos.id',
                DB::raw("DATE_FORMAT(fechaEmi, '%d/%m/%Y') AS fechaEmi"),
                'tabla10_tipodecomprobantedepagoodocumento.descripcion AS tipoDocumento',
                'documentos.id_entidades as id_entidades',
                'entidades.descripcion AS entidadDescripcion',
                'documentos.serie',
                'documentos.numero',
                'documentos.id_t04tipmon',
                'tasas_igv.tasa',
                'documentos.precio',
                'users.name AS usuario'
            )
                ->leftJoin('entidades', 'documentos.id_entidades', '=', 'entidades.id')
                ->leftJoin('users', 'documentos.id_user', '=', 'users.id')
                ->leftJoin('tabla10_tipodecomprobantedepagoodocumento', 'documentos.id_t10tdoc', '=', 'tabla10_tipodecomprobantedepagoodocumento.id')
                ->leftJoin('tasas_igv', 'documentos.id_tasasIgv', '=', 'tasas_igv.id')
                ->where('documentos.id_tipmov', 1)
                ->where('documentos.id', $this->idcxc)
                ->first();
        }
    }

    public function consultaDetallesCxc()
    {
        if ($this->idcxc) {
            $result = DB::selectOne("
            SELECT 
                CO1.id, 
                familias.id AS familia_id, -- ID de la familia
                subfamilias.id AS subfamilia_id, -- ID de la subfamilia
                CO1.id_detalle AS detalle_id, -- ID del detalle
                CO1.id_t10tdoc AS tipo_documento_id, 
                tabla10_tipodecomprobantedepagoodocumento.descripcion AS tipo_documento_descripcion, 
                CO1.serie, 
                CO1.numero, 
                CO1.id_t02tcom AS tipo_comprobante_id, 
                CO1.id_entidades AS entidad_id, 
                entidades.descripcion AS entidad_descripcion, 
                CO1.id_t04tipmon AS tipo_moneda_id, 
                tasas_igv.tasa AS tasa_igv, 
                DATE_FORMAT(CO1.fechaEmi, '%d/%m/%Y') AS fechaEmision, 
                DATE_FORMAT(CO1.fechaVen, '%d/%m/%Y') AS fechaVencimiento, 
                CO1.observaciones, 
                CO1.id_dest_tipcaja AS tipo_caja_descripcion, 
                CO1.basImp AS base_imponible, 
                CO1.IGV, 
                CO1.noGravadas, 
                CO1.otroTributo, 
                CO1.precio, 
                CO1.detalle_producto, -- Detalle del producto
                CO1.id_centroDeCostos
            FROM 
                (SELECT 
                    documentos.id, 
                    detalle.id_familias, 
                    detalle.id_subfamilia, 
                    detalle.id AS id_detalle, -- Incluimos el ID del detalle
                    documentos.id_t10tdoc, 
                    documentos.id_tipmov,
                    documentos.serie, 
                    documentos.numero, 
                    documentos.id_t02tcom, 
                    documentos.id_entidades, 
                    documentos.id_t04tipmon, 
                    documentos.id_tasasIgv, 
                    documentos.fechaEmi, 
                    documentos.fechaVen, 
                    documentos.observaciones, 
                    documentos.id_dest_tipcaja, 
                    documentos.basImp, 
                    documentos.IGV, 
                    documentos.noGravadas, 
                    documentos.otroTributo, 
                    documentos.precio,
                    detalle.descripcion AS detalle_producto, -- Descripción del producto
                    d_detalledocumentos.id_centroDeCostos
                FROM 
                    documentos 
                LEFT JOIN 
                    d_detalledocumentos ON documentos.id = d_detalledocumentos.id_referencia -- Relación con el detalle de documentos
                LEFT JOIN 
                    l_productos ON d_detalledocumentos.id_producto = l_productos.id -- Relación con los productos
                LEFT JOIN 
                    detalle ON detalle.id = l_productos.id_detalle -- Relación con el detalle
                ) CO1
            LEFT JOIN 
                familias ON CO1.id_familias = familias.id 
            LEFT JOIN 
                subfamilias ON CONCAT(CO1.id_familias, CO1.id_subfamilia) = CONCAT(subfamilias.id_familias, subfamilias.id) 
            LEFT JOIN 
                tabla10_tipodecomprobantedepagoodocumento ON CO1.id_t10tdoc = tabla10_tipodecomprobantedepagoodocumento.id 
            LEFT JOIN 
                entidades ON CO1.id_entidades = entidades.id 
            LEFT JOIN 
                tasas_igv ON CO1.id_tasasIgv = tasas_igv.id 
            LEFT JOIN 
                tipoDeCaja ON CO1.id_dest_tipcaja = tipoDeCaja.id 
            WHERE 
                CO1.id_tipmov = 1
                AND CO1.id = ?
        ", [$this->idcxc]);
            Log::info('Resultado de la consulta de documentoCxc:', ['documentoCxc' => $result]);
            // Asignar los resultados a las variables del componente
            $this->familiaId = $result->familia_id; // ID de la familia
            Log::info('log para la familia', ['familia' => $this->familiaId]);
            $this->updatedFamiliaId($this->familiaId);
            $this->subfamiliaId = $result->subfamilia_id; // ID de la subfamilia
            $this->updatedSubfamiliaId($this->subfamiliaId);
            $this->detalleId = $result->detalle_id; // ID del detalle
            $this->tasaIgvId = $result->tasa_igv; // Tasa de IGV seleccionada
            $this->monedaId = $result->tipo_moneda_id; // ID de la moneda seleccionada
            $this->tipoDocumento = $result->tipo_documento_id; // ID del tipo de documento seleccionado
            $this->serieNumero1 = $result->serie; // Parte 1 del número de serie
            $this->serieNumero2 = $result->numero; // Parte 2 del número de serie
            $this->tipoDocId = $result->tipo_comprobante_id; // Tipo de documento de identificación
            $this->docIdent = $result->entidad_id; // Documento de identidad
            $this->fechaEmi = Carbon::createFromFormat('d/m/Y', $result->fechaEmision)->format('Y-m-d'); // Fecha de emisión
            $this->fechaVen = Carbon::createFromFormat('d/m/Y', $result->fechaVencimiento)->format('Y-m-d'); // Fecha de emisión; // Fecha de vencimiento
            $this->tipoDocDescripcion = $result->tipo_documento_descripcion; // Descripción del tipo de documento
            $this->observaciones = $result->observaciones; // Observaciones
            $this->entidad = $result->entidad_descripcion; // Descripción de la entidad
            $this->nuevoDestinatario = $result->tipo_caja_descripcion; // Destinatario o tipo de caja
            $this->centroDeCostos = $result->id_centroDeCostos;

            // Variables financieras
            $this->basImp = $result->base_imponible; // Base imponible
            $this->igv = $result->IGV; // IGV
            $this->noGravado = $result->noGravadas; // No gravado
            $this->otrosTributos = $result->otroTributo; // Otros tributos
            $this->precio = $result->precio; // Precio total
        }
    }

    public function updatedFamiliaId($value)
    {
        // Actualizar las subfamilias según la familia seleccionada
        $this->subfamilias = SubFamilia::select( // Abelardo = Hice cambios para que funcione el select
            'id_familias',
            'id as ic',  // Renombramos el campo 'id' a 'ic'
            'desripcion'
        )
            ->where('id_familias', $value)
            ->get();
        Log::info($this->subfamilias);
        $this->reset('subfamiliaId', 'detalleId'); // Reiniciar las selecciones

    }

    // Método que se ejecuta cuando se selecciona una subfamilia
    public function updatedSubfamiliaId($value)
    {
        // Filtrar los detalles según la subfamilia seleccionada
        $this->detalles = Detalle::where('id_subfamilia', $value)
            ->where('id_familias', $this->familiaId)
            ->get();

        $this->reset('detalleId'); // Reiniciar detalle
    }


    public function calculateIgv()
    {
        // Cálculo del IGV en función de la tasa seleccionada
        if (!$this->basImp || !$this->tasaIgvId) {
            return;
        }

        switch ($this->tasaIgvId) {
            case '18%':
                $this->igv = round($this->basImp * 0.18, 2);
                break;
            case '10%':
                $this->igv = round($this->basImp * 0.10, 2);
                break;
            case 'No Gravado':
            default:
                $this->igv = 0; // No se aplica IGV
                break;
        }
    }

    public function calculatePrecio()
    {
        // Calcular el precio total dinámicamente
        if (is_numeric($this->basImp) && is_numeric($this->igv) && is_numeric($this->otrosTributos) && is_numeric($this->noGravado)) {
            $this->precio = round($this->basImp + $this->igv + $this->otrosTributos + $this->noGravado, 2);
        }
    }

    public function updatedBasImp()
    {
        // Recalcular IGV y precio cuando la base imponible cambie
        $this->calculateIgv();
        $this->calculatePrecio();
    }

    public function updatedTasaIgvId()
    {
        // Recalcular IGV y precio cuando cambie la tasa de IGV
        $this->calculateIgv();
        $this->calculatePrecio();
    }

    public function updatedOtrosTributos()
    {
        // Recalcular el precio total cuando cambie "otros tributos"
        $this->calculatePrecio();
    }

    public function updatedNoGravado()
    {
        // Recalcular el precio total cuando cambie "no gravado"
        $this->calculatePrecio();
    }

    public function buscarDescripcionTipoDocumento()
    {
        // Buscar la descripción del tipo de documento
        $tipoComprobante = TipoDeComprobanteDePagoODocumento::where('id', $this->tipoDocumento)->first();

        if ($tipoComprobante) {
            $this->tipoDocDescripcion = $tipoComprobante->descripcion;
        } else {
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

    public function loadInitialData()
    {
        $this->familias = Familia::all();
        $this->tasasIgv = TasaIgv::all();
        $this->monedas = TipoDeMoneda::all();
        $this->detalles = Detalle::all();
        $this->CC = CentroDeCostos::all();
    }

    public function render()
    {
        return view('livewire.ed-registro-documentos-cxc');
    }
}
