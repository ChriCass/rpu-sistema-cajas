<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use App\Models\Mes;
use Livewire\Attributes\On;
use App\Models\Documento;
use App\Models\TipoDeCaja;
use App\Models\Apertura;
use App\Models\MovimientoDeCaja;
use Illuminate\Support\Facades\DB;
use App\Models\DDetalleDocumento;

class AccionesDeCaja extends Component
{   
    public $caja;
    public $meses;
    public $mes;
    public $años;
    public $año;
    public $documentos;
    public $conteo;
    public $tipodecaja;
    public $tipmov = [
        ['id' => 1, 'descripcion' => 'Aperturar Cajas'],
        ['id' => 2, 'descripcion' => 'Borrar Cajas']
    ];
    public $openModal = false;
    public $mov;
    public $dataAEliminar;

    public function mount(){
        $this->meses = Mes::all();
        $currentYear = now()->year;
        $this->años = [
            $currentYear - 1,
            $currentYear,
            $currentYear + 1,
            $currentYear + 2
        ];
        $this->tipodecaja = TipoDeCaja::all();
        Log::info($this->tipodecaja);
    }

    public function buscar()
    {
        // Validar que mes y año no sean nulos
        if (is_null($this->mes) || is_null($this->año) || is_null($this->caja) || is_null($this->mov)) {
            session()->flash('error', 'Debe seleccionar los parametros antes de buscar.');
            return;
        }

        if ($this->mov == '1'){
            $diasEnMes = range(1,cal_days_in_month(CAL_GREGORIAN, $this->mes, $this->año));
            $consultaCaja = Apertura::selectRaw('DAY(fecha) as dia')
                ->whereYear('fecha', $this->año)
                ->where('id_mes', $this->mes)
                ->where('id_tipo', $this->caja)
                ->pluck('dia');
                $diasNoEnCaja = []; // Array para guardar los días que no están en caja

                
            foreach ($diasEnMes as $dia) {
                $encontrado = false; // Bandera para saber si el día está en caja
        
                foreach ($consultaCaja as $caja) {
                    if ($dia == $caja) {
                        $encontrado = true; // Si el día está en caja, marcamos como encontrado
                        break; // Salimos del bucle interno
                    }
                }
        
                // Si el día no se encontró en caja, lo agregamos al array
                if (!$encontrado) {
                    $diasNoEnCaja[] = $dia;
                }
            }
        }else{
            $diasNoEnCaja = Apertura::leftJoin(DB::raw('(SELECT DISTINCT id_apertura FROM movimientosdecaja WHERE id_apertura IS NOT NULL) AS C1'), 'aperturas.id', '=', 'C1.id_apertura')
            ->leftJoin('tipodecaja', 'aperturas.id_tipo', '=', 'tipodecaja.id')
            ->leftJoin('meses', 'aperturas.id_mes', '=', 'meses.id')
            ->select('tipodecaja.descripcion as tipo_caja', 'aperturas.numero', 'aperturas.año as anno', 'meses.descripcion as mes', 'aperturas.fecha')
            ->whereNull('C1.id_apertura')
            ->where('aperturas.año', $this->año)
            ->where('aperturas.id_tipo', $this->caja)
            ->where('meses.id', $this->mes)
            ->get();
        }
            
            $apertura = [];    
            // Log para verificar los días que no están en caja
            foreach($diasNoEnCaja as $NoEnCaja){
                $cajatipo = TipoDeCaja::where('id', $this->caja)->value("descripcion");
                $mes = Mes::where('id',$this->mes)->value("descripcion");
                $aperturaIndividual['seleccionado'] = $this->mov == '1'? true : false;
                $aperturaIndividual['tipo'] = $this->mov == '1'? $cajatipo:$NoEnCaja['tipo_caja'];
                $aperturaIndividual['numero'] = $this->mov == '1'? $NoEnCaja:$NoEnCaja['numero'];
                $aperturaIndividual['anno'] = $this->mov == '1'?$this->año:$NoEnCaja['anno'];
                $aperturaIndividual['mes'] = $this->mov == '1'? $mes:$NoEnCaja['mes'];
                $aperturaIndividual['fecha'] = $this->mov == '1'? sprintf('%04d-%02d-%02d', $this->año, $this->mes, $NoEnCaja):$NoEnCaja['fecha'];
                $apertura[] = $aperturaIndividual;
            }
        
        
         // Contar los registros en el array
        $conteo = count($apertura);
        $primerDocumento = reset($apertura); // Obtener el primer elemento o false si está vacío


        session()->flash('success', 'Consulta Exitosa');

        // Guardar los datos en la variable de Livewire
        $this->documentos = $apertura;
        $this->conteo = ($conteo == 0) ? 'No' : $conteo;
        
    }

    public function toggleEstado($index)
    {
        // Log para verificar si la función se ejecuta y qué datos recibe
        Log::info("Ejecutando toggleEstado para el índice: " . $index);
        Log::info("Estado anterior: " . json_encode($this->documentos[$index]));

        // Cambio del estado
        $this->documentos[$index]['seleccionado'] = !$this->documentos[$index]['seleccionado'];

        // Log para verificar si el estado realmente cambia
        Log::info("Estado actualizado: " . json_encode($this->documentos[$index]));

    }

    public function DeleteModal(){
        

        $falsos = [];

        if ($this->mov == '1'){
            foreach ($this->documentos as $documento) {
                if (in_array($documento['seleccionado'], [1, "1", true, "true"], true)) {
                    $falsos[] = $documento; // Agregar solo los que son verdaderos
                }
            }
        }else{
            foreach ($this->documentos as $documento) {
                if (in_array($documento['seleccionado'], [0, "0", false, "false"], true)) {
                    $falsos[] = $documento; // Agregar solo los que cumplen la condición
                }
            }
        }
        

        if (count($falsos) <> 0){
            $this->openModal = True;
            $data['documentos'] = $falsos;
            $data['mov'] = $this->mov;
            $this->dispatch('abrirModal',$data);
            $this->dataAEliminar = $falsos;
            
        }else{
            session()->flash('error', 'No hay documentos seleccionados.');            
        }
        
    }

    #[On('mensaje')]
    public function mensaje($mensaje){
        $this->documentos = [];
        session()->flash('success', $mensaje);
    }

    public function render()
    {
        return view('livewire.acciones-de-caja')->with([
            'documentos' => $this->documentos
        ]);
    }
}
