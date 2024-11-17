<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TipoDeMoneda;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use App\Models\MovimientoDeCaja;
use App\Models\TipoDeCambioSunat;
use Illuminate\Support\Facades\Http;
use App\Models\Cuenta;
use Illuminate\Support\Facades\DB;

class VaucherDeTraspasos extends Component
{   
     // Propiedad para controlar la visibilidad del contenido
     public $showContent = false;
     public $fecha;
     public $moneda = 'PEN';
  
     public $detalles = [];
 
     public $monedas;
 
     public $contenedor = []; // Contenedor para acumular los detalles seleccionados
     public $TotalDebe = 0;   // Inicializar TotalDebe
     public $TotalHaber = 0;  // Inicializar TotalHaber
     public $balance = 0;     // Inicializar el balance
 
     public $total;
    
     public $cuentas;
 
     public $editingIndex = null; // Índice para manejar qué fila está en edición
     public $editingMonto = null; // Monto temporal para la edición
     public $warningMessage = []; // Para manejar mensajes de advertencia en cada fila
 
 
     public function mount()
     {
         $this->monedas = TipoDeMoneda::all();
         $this->fecha = Carbon::now('America/Lima')->toDateString();
         $this->cuentas = Cuenta::all();
     }
 
     #[On('sendingContenedorAplicaciones')]
     public function receivingContenedorAplicaciones($detallesSeleccionados)
     {
         // Convertir stdClass a array si es necesario
         $detallesSeleccionados = array_map(function ($item) {
             return (array) $item;
         }, $detallesSeleccionados);
 
         Log::info("Detalles recibidos en receivingContenedorAplicaciones: ", $detallesSeleccionados);
 
         // Limpiar el contenedor que ya estaba previamente recibido a través del evento,
         // pero no tocar los detalles que ya estaban desde antes
         $this->contenedor = $this->detalles; // Mantener los detalles originales
 
         // Agregar los nuevos detalles recibidos al contenedor
         foreach ($detallesSeleccionados as $detalle) {
             $idtc = Cuenta:: where('descripcion',$detalle['Descripcion'])
                             -> get()
                             -> toarray();
             // Asignar valores a las columnas dependiendo del tipo de cuenta
             if ($idtc[0]['id_tcuenta'] == '2') {
                 $detalle['montodebe'] = null;
                 $detalle['montohaber'] = $detalle['monto'];
             } else {
                 $detalle['montodebe'] = $detalle['monto'];
                 $detalle['montohaber'] = null;
             }
 
             // Agregar el detalle al contenedor con las keys adaptadas
             $this->contenedor[] = [
                 'id' => $detalle['id_documentos'],  // Puedes asignar un valor si es necesario
                 'tdoc' => $detalle['tdoc'],
                 'id_entidades' => $detalle['id_entidades'],
                 'entidades' => $detalle['RZ'],  // Usar 'RZ' como 'entidades'
                 'num' => $detalle['Num'],
                 'id_t04tipmon' => $detalle['Mon'],
                 'cuenta' => $detalle['Descripcion'],
                 'monto' => $detalle['monto'],
                 'montodebe' => $detalle['montodebe'],
                 'montohaber' => $detalle['montohaber'],
             ];
         }
 
         // Recalcular dinámicamente los totales y el balance cada vez que cambie el contenedor
         $this->recalcularTotales();
         $this->recalcularBalance();
 
         Log::info("Contenedor actualizado: ", $this->contenedor);
         Log::info("TotalDebe: $this->TotalDebe, TotalHaber: $this->TotalHaber");
     }
 
     public function editMonto($index)
     {
         $this->editingIndex = $index;
         $this->editingMonto = $this->contenedor[$index]['monto'];
         $this->warningMessage[$index] = null;
         Log::info('Editando monto para la fila', ['index' => $index, 'monto' => $this->editingMonto]);
     }
 
     public function saveMonto($index)
     {
         if ($this->editingMonto === null || $this->editingMonto === '') {
             $this->warningMessage[$index] = "Necesitas añadir un monto";
             return;
         }
 
         if($this->contenedor[$index]['monto'] <> null){
             $this->contenedor[$index]['monto'] = $this->editingMonto;
         }else{
             $this->contenedor[$index]['monto'] = $this->editingMonto;
         }
         
         $this->warningMessage[$index] = null;
         $this->editingIndex = null;
         $this->editingMonto = null;
         $this->recalcularTotales();
         $this->recalcularBalance();
         Log::info($this->contenedor);
     }
 
     public function cancelEdit()
     {
         $this->editingIndex = null;
         $this->editingMonto = null;
         $this->warningMessage = null;
     }
 
 
     public function submit()
     {
         Log::info('Submit iniciado');
     
         // Verificar que el balance esté equilibrado
         if ($this->balance !== 0) {
             Log::warning('El balance no está equilibrado: ' . $this->balance);
             session()->flash('error', 'El balance no está equilibrado. Verifique los montos.');
             return;
         }
     
         // Verificar si el contenedor tiene detalles
         if (empty($this->contenedor)) {
             Log::warning('El contenedor está vacío. No hay detalles seleccionados.');
             session()->flash('error', 'No hay detalles seleccionados para procesar.');
             return;
         }
     
         // Iniciar una transacción para asegurar atomicidad
         DB::beginTransaction();
     
         try {
 
             $mov = MovimientoDeCaja::where('id_libro', '4')
                         ->distinct('mov')
                         ->orderBy('mov', 'desc')
                         ->limit(1)
                         ->value('mov');
             $mov = $mov + 1;
 
             foreach ($this->contenedor as $detalle) {
                 // Obtener la cuenta con bloqueo pesimista
                 $cuenta = Cuenta::where('descripcion', $detalle['cuenta'])
                             ->lockForUpdate()
                             ->firstOrFail();
     
                 $monto = ($detalle['montodebe'] !== null) ? $detalle['montodebe'] : $detalle['montohaber'];
                 $id_dh = ($detalle['montodebe'] !== null) ? 1 : 2;
 
             
                 // Crear movimiento de caja
                 MovimientoDeCaja::create([
                     'id_libro' => 4,
                     'mov' => $mov?? 1,
                     'fec' => $this->fecha,
                     'id_documentos' => $detalle['id'],
                     'id_cuentas' => $cuenta->id,
                     'id_dh' => $id_dh,
                     'monto' => $monto,
                     'montodo' => null,
                     'glosa' => $detalle['entidades'] . " " . $detalle['num'],
                 ]);
     
                 Log::info("Detalle procesado exitosamente: ", $detalle);
             }
     
             // Confirmar la transacción si todo fue exitoso
             DB::commit();
             session()->flash('message', 'Datos procesados correctamente.');
     
             // Resetear campos después del envío
             $this->reset(['contenedor', 'TotalDebe', 'TotalHaber', 'balance']);
             Log::info("Formulario reseteado tras el envío exitoso.");
     
             // Redirigir a la ruta 'aplicaciones' con navigate:true
             return $this->redirect(route('aplicaciones'), navigate: true);
     
         } catch (\Exception $e) {
             // Si algo falla, se deshacen los cambios
             DB::rollBack();
             Log::error("Error al procesar el detalle: ", ['error' => $e->getMessage(), 'detalle' => $detalle]);
             session()->flash('error', 'Error al procesar los detalles.');
             return;
         }
     }
     
     
 
     // Función para alternar la visibilidad
     public function toggleContent()
     {
         $this->showContent = !$this->showContent;
     }
 
     private function recalcularTotales()
{
    $this->total = 0; // Reiniciar el total

    foreach ($this->contenedor as $detalle) {
        if ($detalle['monto'] !== null) {
            $this->total += $detalle['monto'];
        }
    }
 
}

 
     private function recalcularBalance()
     {
         $this->balance = $this->TotalDebe - $this->TotalHaber;  
         Log::info("Balance actualizado: $this->balance");
     }
 
    public function render()
    {
        return view('livewire.vaucher-de-traspasos');
    }
}
