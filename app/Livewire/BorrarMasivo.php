<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use App\Models\Mes;
use Livewire\Attributes\On;
use App\Models\Documento;
use App\Models\MovimientoDeCaja;
use Illuminate\Support\Facades\DB;
use App\Models\DDetalleDocumento;

class BorrarMasivo extends Component
{

    public $meses;
    public $mes;
    public $años;
    public $año;
    public $documentos;
    public $conteo;
    public $tipmov = [
        ['id' => 1, 'descripcion' => 'CXC'],
        ['id' => 2, 'descripcion' => 'CXP']
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
        
    }

    public function buscar()
    {
        // Validar que mes y año no sean nulos
        if (is_null($this->mes) || is_null($this->año)) {
            Log::warning('Búsqueda cancelada: mes o año son nulos.', [
                'mes' => $this->mes,
                'año' => $this->año
            ]);
            session()->flash('error', 'Debe seleccionar un mes y un año antes de buscar.');
            return;
        }

        Log::info('Ejecutando consulta de documentos', [
            'mes' => $this->mes,
            'año' => $this->año
        ]);

        $documentos = Documento::select(
                'CON1.id_documentos',
                'documentos.fechaEmi',
                'documentos.id_entidades',
                'entidades.descripcion',
                'documentos.serie',
                'documentos.numero',
                'documentos.precio',
                'documentos.observaciones',
                DB::raw('false as seleccionado')
            )
            ->fromSub(
                MovimientoDeCaja::selectRaw('distinct id_documentos')
                    ->whereIn('id_libro', ['1', '2', '7']),
                'CON1'
            )
            ->leftJoinSub(
                MovimientoDeCaja::selectRaw('distinct movimientosdecaja.id_documentos')
                    ->leftJoin('cuentas', 'movimientosdecaja.id_cuentas', '=', 'cuentas.id')
                    ->whereIn('id_libro', ['3', '4', '5', '6'])
                    ->where('id_tcuenta', '<>', '1'),
                'IN1',
                'CON1.id_documentos',
                '=',
                'IN1.id_documentos'
            )
            ->leftJoin('documentos', 'CON1.id_documentos', '=', 'documentos.id')
            ->leftJoin('entidades', 'documentos.id_entidades', '=', 'entidades.id')
            ->whereNull('IN1.id_documentos')
            ->whereRaw('MONTH(documentos.fechaEmi) = ?', [$this->mes])
            ->whereRaw('YEAR(documentos.fechaEmi) = ?', [$this->año])
            ->where('id_tipmov', $this->mov)
            ->get()->toarray();

         // Contar los registros en el array
        $conteo = count($documentos);
        $primerDocumento = reset($documentos); // Obtener el primer elemento o false si está vacío

        Log::info('Consulta ejecutada con éxito.', [
            'total_documentos' => $conteo,
            'primer_documento' => $primerDocumento
        ]);

        session()->flash('success', 'Consulta Exitosa');

        // Guardar los datos en la variable de Livewire
        $this->documentos = $documentos;
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

        foreach ($this->documentos as $documento) {
            if (in_array($documento['seleccionado'], [0, "0", false, "false"], true)) {
                $falsos[] = $documento; // Agregar solo los que cumplen la condición
            }
        }

        if (count($falsos) <> 0){
            $this->openModal = True;
            $this->dataAEliminar = $falsos;
            
        }else{
            session()->flash('error', 'No hay documentos seleccionados.');            
        }
    }

    public function BorrarDocumentos(){
        DB::beginTransaction();

        try {

            foreach($this->dataAEliminar as $falsos){
                
                // Eliminar los movimientos de caja asociados
                MovimientoDeCaja::where('id_documentos', $falsos['id_documentos'])->delete();

                // Eliminar los detalles del documento asociados
                DDetalleDocumento::where('id_referencia', $falsos['id_documentos'])->delete();

                // Eliminar el documento en sí
                Documento::where('id', $falsos['id_documentos'])->delete();

            }
            // Confirmar la transacción si todo fue exitoso
            DB::commit();
            $this->documentos = [];
            $this->openModal = false;
            // Mensaje de éxito
            session()->flash('success', 'Movimiento eliminado exitosamente.');
        } catch (\Exception $e) {
            // Revertir los cambios si hubo un error
            DB::rollBack();
            Log::error('Error eliminando el documento de CXC: ' . $e->getMessage());
            session()->flash('error', 'Ocurrió un error al intentar eliminar el documento. Intente de nuevo.');
        }

    }


    public function render()
    {
        return view('livewire.borrar-masivo');
    }
}
