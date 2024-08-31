<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

class FormEditAplicacionDetail extends Component
{   
    public $aplicacionesId;
    public $detalles = [];

    public function mount($detalles)
    {
        // Asignar los detalles a la propiedad de la clase al montar el componente
        $this->detalles = $detalles;

        // Log para verificar que los detalles han sido asignados correctamente al montar
        Log::info("Detalles recibidos en mount: ", $this->detalles);
    }

    public function render()
    {
        return view('livewire.form-edit-aplicacion-detail');
    }
}
