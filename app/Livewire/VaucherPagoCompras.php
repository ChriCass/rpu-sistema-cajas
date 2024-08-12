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
    
        Log::info('Iniciando cálculo de Debe y Haber', ['contenedor' => $this->contenedor]);
    
        foreach ($this->contenedor as $item) {
            // Verificar si existe la clave 'monto' en el item
            if (isset($item['monto'])) {
                // Calcular "Debe" como el valor de "monto"
                $this->debe += $item['monto'];
                Log::info('Sumando al Debe', ['monto' => $item['monto'], 'debe_actual' => $this->debe]);
            } else {
                Log::warning('Item sin la clave "monto"', ['item' => $item]);
            }
        }
    
        // Calcular "Haber"
        if (count($this->contenedor) > 1) {
            foreach ($this->contenedor as $item) {
                if (isset($item['monto'])) {
                    $this->haber += $item['monto'];
                    Log::info('Sumando al Haber', ['monto' => $item['monto'], 'haber_actual' => $this->haber]);
                } else {
                    Log::warning('Item sin la clave "monto"', ['item' => $item]);
                }
            }
        } else {
            // Extraer el primer elemento del contenedor sin importar el índice
            $firstItem = reset($this->contenedor);
    
            if (isset($firstItem['monto'])) {
                $this->haber = $firstItem['monto'];
                Log::info('Haber calculado con un solo item', ['monto' => $firstItem['monto'], 'haber_final' => $this->haber]);
            } else {
                $this->haber = 0;
                Log::info('Haber establecido a 0 debido a contenedor vacío o sin clave "monto"');
            }
        }
    
        Log::info('Cálculo finalizado', ['debe' => $this->debe, 'haber' => $this->haber]);
    }
    
    
    public function render()
    {
        return view('livewire.vaucher-pago-compras', ['fechaApertura' => $this->fechaApertura , 'moneda' => $this->moneda])->layout('layouts.app');
    }
}
