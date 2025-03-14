<?php

namespace App\Livewire;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Apertura;
use App\Models\TipoDeCaja;
use App\Models\Mes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DeleteModalAccionesDeCaja extends Component
{
    public $openModal = false;
    public $mov;
    public $documentos;

    #[On('abrirModal')]
    public function abrir($data)
    {
        $this->openModal = true;
        $this->mov = $data['mov'];
        $this->documentos = $data['documentos'];
    }

    public function AccionesDocumentos(){
        
        DB::beginTransaction();

        try {
            foreach ($this->documentos as $doc) {

                // Obtener IDs fuera del foreach para evitar consultas repetidas
                $cajaid = TipoDeCaja::where('descripcion', $doc['tipo'])->first();
                $mesid = Mes::where('descripcion', $doc['mes'])->first(); 

                if ($this->mov == '1') {


                    // Log de los valores obtenidos
                    Log::info('Valores obtenidos:', [
                        'tipo' => $doc['tipo'],
                        'mes' => $doc['mes'],
                        'cajaid' => $cajaid ? $cajaid->id : 'No encontrado',
                        'mesid' => $mesid ? $mesid->id : 'No encontrado',
                    ]);

                    // Validar que existan los registros antes de usarlos
                    if (!$cajaid || !$mesid) {
                        Log::error('Error: No se encontró Tipo de Caja o Mes', [
                            'tipo' => $doc['tipo'],
                            'mes' => $doc['mes'],
                        ]);
                        throw new \Exception('No se encontró Tipo de Caja o Mes para el documento.');
                    }

                    // Log antes de la inserción
                    Log::info('Insertando Apertura', [
                        'id_tipo' => $cajaid->id,
                        'numero' => $doc['numero'],
                        'año' => $doc['anno'],
                        'id_mes' => $mesid->id,
                        'fecha' => $doc['fecha'],
                    ]);

                    // Insertar en la base de datos
                    Apertura::create([
                        'id_tipo' => $cajaid->id,
                        'numero' => $doc['numero'],
                        'año' => $doc['anno'],
                        'id_mes' => $mesid->id,
                        'fecha' => $doc['fecha'],
                    ]);

                    // Confirmar que se insertó correctamente
                    Log::info('Apertura creada exitosamente.');
                }else{
                    Apertura::where('id_tipo', $cajaid->id)
                        ->where('numero', $doc['numero'])
                        ->where('año', $doc['anno'])
                        ->where('id_mes', $mesid->id)
                        ->where('fecha', $doc['fecha'])
                        ->delete();

                }
            }

            // Confirmar la transacción después de procesar todo el foreach
            DB::commit();
            
            // Limpiar datos y cerrar modal
            $this->openModal = false;
            if ($this->mov == '1'){
                $mensaje = 'Aperturas generadas exitosamente.';
            }else{
                $mensaje = 'Aperturas eliminadas exitosamente.';
            }
            // Mensaje de éxito
            $this->dispatch('mensaje', $mensaje);
        } catch (\Exception $e) {
            // Revertir los cambios si hubo un error
            DB::rollBack();
            Log::error('Error generando el movimiento: ' . $e->getMessage());

            // Cerrar el modal y mostrar error
            $this->openModal = false;
            session()->flash('error', 'Ocurrió un error al generar el movimiento. Intente de nuevo.');
        }

    }

    public function render()
    {
        return view('livewire.delete-modal-acciones-de-caja');
    }
}
