<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;
use App\Models\Apertura;
use DateTime;

class VaucherPagoCompras extends Component
{
    public $aperturaId;
    public $fechaApertura;
    public $moneda = "PEN";
    public $contenedor = []; // Variable para almacenar los datos recibidos
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

    #[On('sendingContenedor')]
    public function handleSendingContenedor($contenedor)
    {
        // Guardar los datos recibidos en la variable contenedor
        $this->contenedor = $contenedor;

        // Realizar los cálculos de "Debe" y "Haber"
        $this->calculateDebeHaber();

        Log::info('Datos recibidos en VaucherPagoCompras', ['contenedor' => $this->contenedor]);
    }
    
    public function calculateDebeHaber()
{
    $this->debe = 0.0;
    $this->haber = 0.0;

    // Verificar si el contenedor no está vacío
    if (!empty($this->contenedor)) {
        // Asignar directamente el "Debe" al monto del primer elemento del contenedor
        if (isset($this->contenedor[0]['monto'])) {
            $this->debe = $this->contenedor[0]['monto'];
            Log::info('Asignando Debe', ['debe' => $this->debe]);
        }

        // El "Haber" se calcula en base a si hay más de un elemento en el contenedor
        if (count($this->contenedor) > 1) {
            foreach ($this->contenedor as $item) {
                if (isset($item['monto'])) {
                    $this->haber += $item['monto'];
                    Log::info('Sumando al Haber', ['monto' => $item['monto'], 'haber_actual' => $this->haber]);
                }
            }
        } else {
            // Si hay un solo elemento, el "Haber" será igual al monto de ese único elemento
            $this->haber = $this->debe;
            Log::info('Asignando Haber igual al Debe por único elemento', ['haber_actual' => $this->haber]);
        }
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
            $this->debe = 0.0; // Limpiar el campo "Debe"
            Log::info('Fila deseleccionada', ['index' => $index]);
        } else {
            // Si es una nueva selección, se actualiza
            $this->selectedIndex = $index;
            $this->debe = $this->contenedor[$index]['monto'];
            Log::info('Fila seleccionada', ['index' => $index, 'debe' => $this->debe]);
        }
    }

    public function render()
    {
        return view('livewire.vaucher-pago-compras', ['fechaApertura' => $this->fechaApertura , 'moneda' => $this->moneda])->layout('layouts.app');
    }
}
