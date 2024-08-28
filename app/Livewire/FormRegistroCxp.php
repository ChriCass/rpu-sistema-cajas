<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SubFamilia;
use App\Models\Detalle;
use App\Models\Familia;
use App\Models\TasaIgv;
use App\Models\TipoDeMoneda;
use App\Models\TipoDocumentoIdentidad;
use Illuminate\Support\Facades\Log;
use App\Models\CompraDocumento;
use App\Models\TipoCambioSunat;
use App\Models\MovimientoDeCaja;

class FormRegistroCxp extends Component
{
    public $aperturaId;
    public $documentos;
    public $monedas;
    public $igvs;
    public $familias;
    public $subfamilias = [];
    public $detalles = [];

    public $selectedTipoDocumento;
    public $labelDoc = 'doc';

    public $selectedFamilia = null;
    public $selectedSubfamilia = null;
    public $selectedDetalle = null;
    public $selectedTasaIgv = null;
    public $selectedMoneda = null;

    public $baseImponible;
    public $igv = null; // Inicialmente null
    public $otroTributo = null; // Inicialmente null
    public $noGravadas = null; // Inicialmente null
    public $total = null; // Inicialmente null

    public $fechaEmi;
    public $fechaVen;
    public $serie;
    public $numero;
    public $observaciones;
    public $idDestTipCaja;
    public $selectedDescripcionCaja;
    public $tipoDocumento;
    public $numeroDocumento;
    public $entidad;
    public $documentoIdentidad;
    public $data;
    public $checkBox1;
    public $netoDetra;
    public $detraccion;
    public function mount($aperturaId)
    {
        $this->aperturaId = $aperturaId;
        $this->documentos = TipoDocumentoIdentidad::whereIn('id', ['1', '6'])->get();
        $this->monedas = TipoDeMoneda::all();
        $this->igvs = TasaIgv::all();
        $this->familias = Familia::where('id', 'not like', '0%')->get();

        Log::info('ESTE ES UN LOG DE PRUEBA');
    }

    public function updatedSelectedTipoDocumento($value)
    {
        $documento = TipoDocumentoIdentidad::find($value);
        $this->labelDoc = $documento ? $documento->abreviado : 'doc';
    }

    public function updatedSelectedFamilia($value)
    {
        $this->subfamilias = Subfamilia::where('id_familias', $value)->get();
        $this->selectedSubfamilia = null;
        $this->detalles = [];
    }

    public function updatedSelectedSubfamilia($value)
    {
        $this->detalles = Detalle::where('id_familias', $this->selectedFamilia)
            ->where('id_subfamilia', $value)
            ->get();
        $this->selectedDetalle = null;
    }

    public function updatedBaseImponible($value)
    {
        $this->calculateIgv();
    }

    public function updatedSelectedTasaIgv($value)
    {
        $this->calculateIgv();
    }

    public function calculateIgv()
    {
        // Asegúrate de que baseImponible sea numérico
        $baseImponible = floatval($this->baseImponible);

        if ($this->selectedTasaIgv && $baseImponible) {
            $tasa = TasaIgv::where('id', $this->selectedTasaIgv)->first();

            if ($tasa) {
                if ($tasa->numero <= 1) {
                    $this->igv = round($baseImponible * $tasa->numero, 2);
                } else {
                    $this->igv = round($baseImponible * ($tasa->numero / 100), 2);
                }
            } else {
                $this->igv = null;
            }
        } else {
            $this->igv = null;
        }

        $this->otroTributo = $this->otroTributo ?? 0;
        $this->noGravadas = $this->noGravadas ?? 0;

        // Asegúrate de que todos los valores involucrados sean numéricos
        $this->total = $baseImponible + $this->igv + floatval($this->otroTributo) + floatval($this->noGravadas);
    }

    public function save()
    {
        try {
            Log::info('Iniciando proceso de validación de campos.');
    
            // Validación de campos
            $this->validate([
                'entidad' => 'required',
                'tipoDocumento' => 'required',
                'numeroDocumento' => 'required',
                'selectedTipoDocumento' => 'required',
                'documentoIdentidad' => 'required|max:11',
                'numero' => 'required|string|max:10',
                'serie' => 'required|string|max:4',
                'selectedMoneda' => 'required',
                'selectedTasaIgv' => 'required',
                'fechaEmi' => 'required|date',
                'fechaVen' => 'required|date',
                'baseImponible' => 'required|numeric|min:0',
                'igv' => 'required|numeric|min:0',
                'noGravadas' => 'nullable|numeric|min:0',
                'total' => 'required|numeric|min:0|not_in:0',
                'observaciones' => 'nullable|string',
                'selectedFamilia' => 'required',
                'selectedSubfamilia' => 'required',
                'selectedDetalle' => 'required',
            ]);
    
            Log::info('Validación de campos exitosa.');
    
            // Verificación y operaciones adicionales
            if ($this->total == 0) {
                session()->flash('error', 'No puede ser el monto cero');
                return;
            }
    
            $detalle = Detalle::join('familias', 'detalle.id_familias', '=', 'familias.id')
                ->join('subfamilias', function ($join) {
                    $join->on('detalle.id_familias', '=', 'subfamilias.id_familias')
                        ->on('detalle.id_subfamilia', '=', 'subfamilias.id');
                })
                ->where('subfamilias.desripcion', $this->selectedSubfamilia)
                ->where('familias.descripcion', $this->selectedFamilia)
                ->where('detalle.descripcion', $this->selectedDetalle)
                ->first();
    
            if (!$detalle) {
                Log::warning('Detalle no encontrado.', ['selectedSubfamilia' => $this->selectedSubfamilia, 'selectedFamilia' => $this->selectedFamilia, 'selectedDetalle' => $this->selectedDetalle]);
                session()->flash('error', 'Detalle no encontrado');
                return;
            }
    
            $detId = $detalle->id;
    
            Log::info('Detalle encontrado.', ['detId' => $detId]);
    
            $documentoExistente = CompraDocumento::where('id_entidades', $this->entidad)
                ->where('id_t10tdoc', $this->selectedTipoDocumento)
                ->where('serie', $this->serie)
                ->where('numero', $this->numero)
                ->first();
    
            if ($documentoExistente) {
                Log::warning('Documento ya registrado.', ['entidad' => $this->entidad, 'tipoDocumento' => $this->selectedTipoDocumento, 'serie' => $this->serie, 'numero' => $this->numero]);
                session()->flash('error', 'Documento ya registrado');
                return;
            }
    
            Log::info('Preparando datos para la inserción en la tabla compras_documentos.');
    
            // Datos preparados para inserción en la tabla compras_documentos
            $dataDocumento = [
                'id_entidades' => $this->entidad,
                'id_t10tdoc' => $this->tipoDocumento,
                'numeroDocumento' => $this->numeroDocumento,
                'id_t02doc' => $this->selectedTipoDocumento,
                'documentoIdentidad' => $this->documentoIdentidad,
                'serie' => $this->serie,
                'numero' => $this->numero,
                'id_t04tipmon' => $this->selectedMoneda,
                'id_tasasIgv' => $this->selectedTasaIgv,
                'fechaEmi' => $this->fechaEmi,
                'fechaVen' => $this->fechaVen,
                'basImp' => $this->baseImponible,
                'IGV' => $this->igv,
                'noGravadas' => $this->noGravadas,
                'total' => $this->total,
                'observaciones' => $this->observaciones,
                'id_detalle' => $detId,
                'id_Usuario' => auth()->user()->id, // Registro del usuario
            ];
    
            Log::info('Insertando documento en la tabla compras_documentos.', $dataDocumento);
    
            // Inserción en la tabla compras_documentos
            $documento = CompraDocumento::create($dataDocumento);
    
            // Recuperar el ID del documento insertado
            $documentoId = $documento->id;
    
            Log::info('Documento insertado correctamente.', ['documentoId' => $documentoId]);
    
            // Inicialización de $movcxp
            $movcxp = MovimientoDeCaja::max('mov') + 1;
    
            Log::info('Movimiento de caja inicializado.', ['movcxp' => $movcxp]);
    
            if ($this->checkBox1) {
                Log::info('Detracción activada, procesando detracción.');
    
                $netodetrado = null;
                $detracciondo = null;
                $netodetra = $this->netoDetra;
                $detraccion = $this->detraccion;
    
                if ($this->selectedMoneda == 'USD') {
                    $tipoCambio = TipoCambioSunat::whereDate('fecha', $this->fechaEmi)->value('venta');
                    $netodetrado = round($this->netoDetra, 2);
                    $detracciondo = round($this->detraccion, 2);
                    $netodetra = round($this->netoDetra * $tipoCambio, 2);
                    $detraccion = round($this->detraccion * $tipoCambio, 2);
    
                    Log::info('Calculando detracción en USD.', ['tipoCambio' => $tipoCambio, 'netodetra' => $netodetra, 'detraccion' => $detraccion]);
                }
    
                $cuenta = Detalle::where('id', $detId)->value('id_cuenta');
    
                $dataMovimientoCaja1 = [
                    'id_libro' => 2,
                    'mov' => $movcxp,
                    'fec' => $this->fechaEmi,
                    'id_documentos' => $documentoId,
                    'id_cuentas' => $cuenta,
                    'id_dh' => 2,
                    'monto' => $netodetra,
                    'montodo' => $netodetrado,
                    'glosa' => $this->observaciones,
                ];
    
                $dataMovimientoCaja2 = [
                    'id_libro' => 2,
                    'mov' => $movcxp,
                    'fec' => $this->fechaEmi,
                    'id_documentos' => $documentoId,
                    'id_cuentas' => 4, // Cuenta para detracción
                    'id_dh' => 2,
                    'monto' => $detraccion,
                    'montodo' => $detracciondo,
                    'glosa' => $this->observaciones,
                ];
    
                Log::info('Insertando movimientos de caja para detracción.', ['dataMovimientoCaja1' => $dataMovimientoCaja1, 'dataMovimientoCaja2' => $dataMovimientoCaja2]);
    
                // Inserción en la tabla movimientos_de_caja
                MovimientoDeCaja::create($dataMovimientoCaja1);
                MovimientoDeCaja::create($dataMovimientoCaja2);
            } else {
                Log::info('Detracción no activada, procesando movimientos de caja normales.');
    
                $lib = ($this->ComboBox6 != 'TRANSFERENCIAS') ? 2 : 5;
                $cuenta = Detalle::where('id', $detId)->value('id_cuenta');
    
                $precio = $this->total;
                $preciodo = null;
    
                if ($this->selectedMoneda == 'USD') {
                    $tipoCambio = TipoCambioSunat::whereDate('fecha', $this->fechaEmi)->value('venta');
                    $preciodo = $precio;
                    $precio = round($this->total * $tipoCambio, 2);
    
                    Log::info('Calculando precio en USD.', ['tipoCambio' => $tipoCambio, 'precio' => $precio]);
                }
    
                $dataMovimientoCaja = [
                    'id_libro' => $lib,
                    'mov' => $movcxp,
                    'fec' => $this->fechaEmi,
                    'id_documentos' => $documentoId,
                    'id_cuentas' => $cuenta,
                    'id_dh' => 2,
                    'monto' => $precio,
                    'montodo' => $preciodo,
                    'glosa' => $this->observaciones,
                ];
    
                Log::info('Insertando movimiento de caja normal.', $dataMovimientoCaja);
    
                // Inserción en la tabla movimientos_de_caja
                MovimientoDeCaja::create($dataMovimientoCaja);
            }
    
            // Mensaje de éxito para el usuario
            session()->flash('success', 'Datos registrados exitosamente.');
    
            Log::info('Datos registrados exitosamente.');
    
            // Limpieza de campos
            $this->reset([
                'entidad',
                'tipoDocumento',
                'numeroDocumento',
                'selectedTipoDocumento',
                'documentoIdentidad',
                'serie',
                'numero',
                'selectedMoneda',
                'selectedTasaIgv',
                'fechaEmi',
                'fechaVen',
                'baseImponible',
                'igv',
                'noGravadas',
                'total',
                'observaciones',
                'selectedFamilia',
                'selectedSubfamilia',
                'selectedDetalle',
                'checkBox1',
                'netoDetra',
                'detraccion'
            ]);
    
            // Ocultar el componente
            $this->dispatchBrowserEvent('closeModal');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Error en la base de datos: ' . $e->getMessage());
            session()->flash('error', 'Error en la base de datos: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Ocurrió un error: ' . $e->getMessage());
            session()->flash('error', 'Ocurrió un error: ' . $e->getMessage());
        }
    }
    


    public function render()
    {
        return view('livewire.form-registro-cxp')->layout('layouts.app');
    }
}
