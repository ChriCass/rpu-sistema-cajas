<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

class AplicacionDetail extends Component
{
    public $aplicacionesId;
    public $showFormEdit = false;
    public $detalles = [];
    public $detallesRecibidos = false; // Nueva variable para confirmar que se han recibido los detalles
    public $fecha;

    public function toggleEdit()
    {
        $this->showFormEdit = !$this->showFormEdit;

        // Log para verificar que el método toggleEdit se ejecuta y el valor de showFormEdit
        Log::info("toggleEdit ejecutado. Valor de showFormEdit: " . ($this->showFormEdit ? 'true' : 'false'));
    }

    public function mount($aplicacionesId)
    {
        $this->aplicacionesId = $aplicacionesId;

        // Log para confirmar que se ejecuta el método mount y el valor de aplicacionesId
        Log::info("Mount ejecutado en AplicacionDetail con aplicacionesId: " . $this->aplicacionesId);
    }

    // Método para recibir los detalles del componente hijo
    #[On('sendDetallesToParent')]
    public function receiveDetalles($detalles)
    {
        // Log antes de asignar los detalles
        Log::info("Evento sendDetallesToParent recibido con detalles: ", $detalles);

        $this->detalles = $detalles;
        $this->detallesRecibidos = true; // Confirmamos que hemos recibido los detalles

        // Log para confirmar que los detalles han sido asignados
        Log::info("Detalles asignados en receiveDetalles: ", $this->detalles);
    }

    #[On('sendingFecha')]
    public function receiveFecha($fecha)
    {
        // Log antes de asignar los detalles

        $this->fecha = $fecha;
        $this->detallesRecibidos = true; // Confirmamos que hemos recibido los detalles

 
    }



    public function render()
    {
        return view('livewire.aplicacion-detail')->layout('layouts.app');
    }
}
