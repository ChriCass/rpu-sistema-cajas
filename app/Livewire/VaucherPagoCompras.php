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

    public function mount($aperturaId)
    {
        $this->aperturaId = $aperturaId;

        // Recuperar la fecha directamente usando el aperturaId
        $apertura = Apertura::findOrFail($aperturaId);
        $this->fechaApertura = (new DateTime($apertura->fecha))->format('d/m/Y');

        Log::info('Fecha de apertura establecida', ['fechaApertura' => $this->fechaApertura]);
    }

    public function render()
    {
        return view('livewire.vaucher-pago-compras', ['fechaApertura' => $this->fechaApertura])->layout('layouts.app');
    }
}
