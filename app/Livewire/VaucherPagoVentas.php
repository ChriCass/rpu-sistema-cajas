<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use App\Models\Apertura;
use DateTime;
use Livewire\Attributes\On;
use App\Models\TipoDeCaja;
use App\Models\MovimientoDeCaja;
use App\Models\Cuenta;
use App\Models\TipoDeCambioSunat;



class VaucherPagoVentas extends Component
{
    public $aperturaId;
    public $fechaApertura;
    public $moneda = "PEN";
    public $contenedor = [];
    public $debe = 0.0; // Variable para almacenar el total del debe
    public $haber = 0.0; // Variable para almacenar el total del haber
    public $selectedIndex = null;
    public $editingIndex = null; // Para manejar qué fila está en edición
    public $editingMonto = null; // Para almacenar temporalmente el monto que se está editando
    public $warningMessage = []; // Para manejar mensajes de advertencia por cada fila
    public $balance = 0.0; // Nueva variable para almacenar el balance (Debe - Haber)
    public $caja;
    public $tipoCaja;


    public function mount($aperturaId)
    {
        $this->aperturaId = $aperturaId;

        // Recuperar la fecha directamente usando el aperturaId
        $apertura = Apertura::findOrFail($aperturaId);
        $this->fechaApertura = (new DateTime($apertura->fecha))->format('d/m/Y');

        Log::info('Fecha de apertura establecida', ['fechaApertura' => $this->fechaApertura]);

        $this->caja = $apertura->id_tipo;

        // Obtener el tipo de caja relacionado con el ID de caja
        $this->tipoCaja = TipoDeCaja::where('id', $this->caja)->first();

          // Generar valores de prueba si el contenedor está vacío
     /**     if (empty($this->contenedor)) {
            $this->generateTestValues();
        } */ 
    }

     // Función para generar valores de prueba
   /*  public function generateTestValues()
     {
         $this->contenedor = [
             [
                 'id_documentos' => 1,
                 'tdoc' => 'Factura',
                 'id_entidades' => 1001,
                 'RZ' => 'Empresa Ficticia S.A.',
                 'Num' => 'F001-00012345',
                 'Mon' => 'PEN',
                 'Descripcion' => 'Venta de productos',
                 'monto' => 1000.00
             ],
             [
                 'id_documentos' => 2,
                 'tdoc' => 'Boleta',
                 'id_entidades' => 1002,
                 'RZ' => 'Cliente Ficticio',
                 'Num' => 'B001-00054321',
                 'Mon' => 'PEN',
                 'Descripcion' => 'Venta de servicios',
                 'monto' => 500.00
             ]
         ];
 
         Log::info('Valores de prueba generados en el contenedor', ['contenedor' => $this->contenedor]);
 
         // Calcular debe y haber con los valores generados
         $this->calculateDebeHaber();
     } */

    public function editMonto($index)
    {
        $this->editingIndex = $index;
        $this->editingMonto = $this->contenedor[$index]['monto'];
        $this->warningMessage[$index] = null; // Limpiar advertencia al entrar en modo de edición para esta fila
        Log::info('Editando monto para la fila', ['index' => $index, 'monto' => $this->editingMonto]);
    }

    public function saveMonto($index)
    {
        if ($this->editingMonto === null || $this->editingMonto === '') {
            $this->warningMessage[$index] = "Necesitas añadir un monto"; // Mensaje de advertencia para la fila actual
            return;
        }

        // Si el monto es válido, guardamos el valor y limpiamos advertencias
        $this->contenedor[$index]['monto'] = $this->editingMonto;
        $this->warningMessage[$index] = null; // Limpiar advertencia
        $this->editingIndex = null; // Deshabilitar la edición
        $this->editingMonto = null; // Limpiar el valor temporal
        $this->calculateDebeHaber(); // Recalcular debe y haber con el nuevo monto
    }

    public function cancelEdit()
    {
        // Limpiar el índice de edición y el monto editado
        $this->editingIndex = null;
        $this->editingMonto = null;
        $this->warningMessage = null; // Limpiar advertencia si existía
    }

    #[On('sendingContenedorVentas')]
    public function handleSendingContenedor($contenedor)
    {
        // Guardar los datos recibidos en la variable contenedor
        $this->contenedor = $contenedor;
        
        // Calcular Debe y Haber
        $this->calculateDebeHaber();

        Log::info('Datos recibidos en VaucherPagoVentas', ['contenedor' => $this->contenedor]);
    }

    public function calculateDebeHaber()
    {
        $this->debe = 0.0;
        $this->haber = 0.0;
    
        // Verificar si el contenedor no está vacío
        if (!empty($this->contenedor)) {
            // Sumamos todos los montos al "Debe"
            foreach ($this->contenedor as $item) {
                if (isset($item['monto'])) {
                    $this->debe += $item['monto'];
                    Log::info('Sumando al Debe', ['monto' => $item['monto'], 'debe_actual' => $this->debe]);
                }
            }
    
            // El "Haber" es igual al monto de la primera fila seleccionada (o el primero si ninguno está seleccionado)
            if ($this->selectedIndex !== null) {
                $this->haber = $this->contenedor[$this->selectedIndex]['monto'];
            } else {
                $this->haber = $this->contenedor[0]['monto'];
            }
            
            Log::info('Asignando Haber', ['haber_actual' => $this->haber]);
        } else {
            Log::info('El contenedor está vacío, no se realiza cálculo de Debe y Haber');
        }
         // Calcular el balance como la diferencia entre Debe y Haber
         $this->calculateBalance();

        Log::info('Cálculo finalizado', ['debe' => $this->debe, 'haber' => $this->haber]);
    }

     // Función para calcular el balance
     public function calculateBalance()
     {
         $this->balance = $this->debe - $this->haber;
         Log::info('Balance calculado', ['balance' => $this->balance]);
     }
    
     public function submit()
     {
         Log::info('Iniciando el proceso de submit en VaucherPagoCompras.');
 
         // Validación de campos
         if (empty($this->fechaApertura) || empty($this->contenedor)) {
             Log::warning('Falta llenar campos: fecha o contenedor están vacíos.');
             session()->flash('error', 'Falta llenar campos');
             return;
         }
         Log::info(count($this->contenedor));
         if (count($this->contenedor) <= 0) {
             Log::warning('El contenedor no tiene suficientes detalles.');
             session()->flash('error', 'Debe haber más de un detalle en la transacción.');
             return;
         }
 
         Log::info('Campos validados correctamente.');
         
         
         // Obtener idapt de la apertura
         try {
             $idapt = $this -> aperturaId;
             Log::info("idapt obtenido correctamente: {$idapt}");
         } catch (\Exception $e) {
             Log::error('Error obteniendo idapt: ' . $e->getMessage());
             session()->flash('error', 'Error al obtener la apertura.');
             return;
         }
 
         // Obtener el número de movimiento (movc)
         try {
             $movc = MovimientoDeCaja::where('id_apertura', $idapt)
                 ->orderBy('mov', 'desc')
                 ->first()
                 ->mov ?? 1;
             $movc++; // Incrementa para el siguiente movimiento
             Log::info("Número de movimiento generado correctamente: {$movc}");
         } catch (\Exception $e) {
             Log::error('Error obteniendo movc: ' . $e->getMessage());
             session()->flash('error', 'Error al generar el número de movimiento.');
             return;
         }
 
         // Si la moneda no es PEN, calcular el tipo de cambio
         $tipoCambio = 1;
         if ($this->moneda !== 'PEN') {
             try {
                 $tipoCambio = TipoDeCambioSunat::where('fecha', $this->fechaApertura)->value('venta');
                 Log::info("Tipo de cambio obtenido: {$tipoCambio}");
             } catch (\Exception $e) {
                 Log::error('Error obteniendo el tipo de cambio: ' . $e->getMessage());
                 session()->flash('error', 'Error al obtener el tipo de cambio.');
                 return;
             }
         }
         Log::info($this->contenedor);
 
         // Procesar cada detalle en el contenedor
         try {

            $ctaCaja = Cuenta::where('Descripcion', $this ->tipoCaja['descripcion'])
            ->get()
            ->toarray();

            Log::info($ctaCaja);
        
            MovimientoDeCaja::create([
                'id_libro' => 3,
                'id_apertura' => $idapt,
                'mov' => $movc,
                'fec' => DateTime::createFromFormat('d/m/Y', $this->fechaApertura)->format('Y-m-d'),
                'id_documentos' => null,
                'id_cuentas' => $ctaCaja[0]['id'],
                'id_dh' => 1,
                'monto' => $this -> debe,
                'montodo' => null,
                'glosa' => 'PAGO DE CXC',
            ]);
             foreach ($this->contenedor as $detalle) {
 
                 $iddoc = $detalle['id_documentos'] ?? 'NULL';
                 $glo = $detalle['RZ'].' '.$detalle['Num'];
                 Log::info(DateTime::createFromFormat('d/m/Y', $this->fechaApertura)->format('Y-m-d'));
                 Log::info("Procesando detalle: ID Documento: {$iddoc}, Glosa: {$glo}");
                 // Obtener la cuenta
                 $cta = Cuenta::where('Descripcion', $detalle['Descripcion'])->firstOrFail()->id;
                 Log::info("Cuenta obtenida: {$cta}");
 
                 // Determinar si es Debe o Haber y calcular el monto
                 $dh = 2; // Debe
                 $monto = $detalle['monto'];
                 // Insertar el movimiento en la base de datoss
                 MovimientoDeCaja::create([
                     'id_libro' => 3,
                     'id_apertura' => $idapt,
                     'mov' => $movc,
                     'fec' => DateTime::createFromFormat('d/m/Y', $this->fechaApertura)->format('Y-m-d'),
                     'id_documentos' => $iddoc,
                     'id_cuentas' => $cta,
                     'id_dh' => $dh,
                     'monto' => $monto,
                     'montodo' => null,
                     'glosa' => $glo,
                 ]);
                 
                 Log::info("Movimiento de caja insertado: ID Cuenta: {$cta}, Debe/Haber: {$dh}, Monto: {$monto}");
             }
         
         } catch (\Exception $e) {
             Log::error('Error insertando movimiento de caja: ' . $e->getMessage());
             session()->flash('error', 'Error al procesar los detalles.');
             return;
         }
 
         /* 
         // Cálculo del balance
         $this->balance = $this->TotalDebe - $this->TotalHaber;
         Log::info("Balance calculado: {$this->balance}");
 
         if ($this->balance != 0) {
             Log::warning("El balance no cuadra: {$this->balance}");
             session()->flash('error', 'El asiento no cuadra');
             return;
         }
         */
         
         // Si todo salió bien
         session()->flash('message', 'Transacción Exitosa.');
         Log::info('Transacción procesada exitosamente.');
         return $this->redirect(route('apertura.edit', ['aperturaId' => $this->aperturaId]), navigate: true);
 
         // Resetear los campos después de procesar la transacción
         $this->reset(['fechaApertura', 'contenedor', 'debe', 'haber', 'balance']);
         Log::info('Formulario reseteado.');
     }
 
     

    public function render()
    {
        return view('livewire.vaucher-pago-ventas', ['fechaApertura' => $this->fechaApertura, 'moneda'=> $this->moneda]);
    }
}
