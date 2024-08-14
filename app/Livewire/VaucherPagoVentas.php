<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use App\Models\Apertura;
use DateTime;
use Livewire\Attributes\On;
class VaucherPagoVentas extends Component
{
    public $aperturaId;
    public $fechaApertura;
    public $moneda = "PEN";
    public $contenedor = [];
    public $debe = 0.0; // Variable para almacenar el total del debe
    public $haber = 0.0; // Variable para almacenar el total del haber
    public $selectedIndex = null;

    public function mount($aperturaId)
    {
        $this->aperturaId = $aperturaId;

        // Recuperar la fecha directamente usando el aperturaId
        $apertura = Apertura::findOrFail($aperturaId);
        $this->fechaApertura = (new DateTime($apertura->fecha))->format('d/m/Y');

        Log::info('Fecha de apertura establecida', ['fechaApertura' => $this->fechaApertura]);
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
    
        Log::info('Cálculo finalizado', ['debe' => $this->debe, 'haber' => $this->haber]);
    }
    
    public function selectDebe($index)
    {
        if ($this->selectedIndex === $index) {
            // Si el índice seleccionado es el mismo, se deselecciona
            $this->selectedIndex = null;
            Log::info('Fila deseleccionada', ['index' => $index]);
        } else {
            // Si es una nueva selección, se actualiza
            $this->selectedIndex = $index;
            Log::info('Fila seleccionada', ['index' => $index]);
        }
    
        // Recalcula el debe y el haber
        $this->calculateDebeHaber();
    }
    

    public function render()
    {
        return view('livewire.vaucher-pago-ventas', ['fechaApertura' => $this->fechaApertura, 'moneda'=> $this->moneda])->layout('layouts.app');
    }
}
