<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TipoDocumentoIdentidad;
use App\Models\TipoDeMoneda;
use App\Models\TasaIgv;
use App\Models\Familia;
use App\Models\SubFamilia;
use App\Models\Detalle;
use App\Models\Documento;
use App\Models\TipoDeCaja;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
            Log::info('Iniciando el proceso de guardado.');
    
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
    
            Log::info('Validación completada con éxito.', [
                'entidad' => $this->entidad,
                'tipoDocumento' => $this->tipoDocumento,
                'numeroDocumento' => $this->numeroDocumento,
                'selectedTipoDocumento' => $this->selectedTipoDocumento,
                'documentoIdentidad' => $this->documentoIdentidad,
                'numero' => $this->numero,
                'serie' => $this->serie,
                'selectedMoneda' => $this->selectedMoneda,
                'selectedTasaIgv' => $this->selectedTasaIgv,
                'fechaEmi' => $this->fechaEmi,
                'fechaVen' => $this->fechaVen,
                'baseImponible' => $this->baseImponible,
                'igv' => $this->igv,
                'noGravadas' => $this->noGravadas,
                'total' => $this->total,
                'observaciones' => $this->observaciones,
                'selectedFamilia' => $this->selectedFamilia,
                'selectedSubfamilia' => $this->selectedSubfamilia,
                'selectedDetalle' => $this->selectedDetalle,
            ]);
    
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
                Log::warning('Detalle no encontrado.', [
                    'selectedFamilia' => $this->selectedFamilia,
                    'selectedSubfamilia' => $this->selectedSubfamilia,
                    'selectedDetalle' => $this->selectedDetalle,
                ]);
                session()->flash('error', 'Detalle no encontrado');
                return;
            }
    
            Log::info('Detalle encontrado con éxito.', ['detalle_id' => $detalle->id]);
    
            $documentoExistente = Documento::where('id_entidades', $this->entidad)
                ->where('id_t10tdoc', $this->selectedTipoDocumento)
                ->where('serie', $this->serie)
                ->where('numero', $this->numero)
                ->first();
    
            if ($documentoExistente) {
                Log::warning('Documento ya registrado.', [
                    'entidad' => $this->entidad,
                    'tipoDocumento' => $this->selectedTipoDocumento,
                    'serie' => $this->serie,
                    'numero' => $this->numero,
                ]);
                session()->flash('error', 'Documento ya registrado');
                return;
            }
    
            Log::info('Documento no existente, listo para registrar.', [
                'entidad' => $this->entidad,
                'tipoDocumento' => $this->selectedTipoDocumento,
                'serie' => $this->serie,
                'numero' => $this->numero,
            ]);
    
            $this->data = [
                'entidad'  => $this->entidad,
                'tipoDocumento' => $this->tipoDocumento,
                'numeroDocumento' => $this->numeroDocumento,
                'selectedTipoDocumento' => $this->selectedTipoDocumento,
                'documentoIdentidad' => $this->documentoIdentidad,
                'numero' => $this->numero,
                'serie' => $this->serie,
                'selectedMoneda' => $this->selectedMoneda,
                'selectedTasaIgv' => $this->selectedTasaIgv,
                'fechaEmi' => $this->fechaEmi,
                'fechaVen' => $this->fechaVen,
                'baseImponible' => $this->baseImponible,
                'igv' => $this->igv,
                'noGravadas' => $this->noGravadas,
                'total' => $this->total,
                'observaciones' => $this->observaciones,
                'selectedFamilia' => $this->selectedFamilia,
                'selectedSubfamilia' => $this->selectedSubfamilia,
                'selectedDetalle' => $this->selectedDetalle,
                'id_detalle' => $detalle->id
            ];
    
            Log::info('Datos preparados para inserción en la base de datos.', $this->data);
    
            // Aquí iría el código para guardar los datos en la base de datos
            // Por ahora, solo estamos registrando en el log
    
            session()->flash('success', 'Datos registrados en el log exitosamente.');
            
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
        return view('livewire.form-registro-cxp', ['data' => $this->data])->layout('layouts.app');
    }
}
