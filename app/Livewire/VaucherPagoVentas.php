<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use App\Models\Apertura;
use DateTime;
use Livewire\Attributes\On;
use App\Models\TipoDeCaja;


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
    
 

    public function render()
    {
        return view('livewire.vaucher-pago-ventas', ['fechaApertura' => $this->fechaApertura, 'moneda'=> $this->moneda]);
    }
}
