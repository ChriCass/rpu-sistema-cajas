<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
class AplicacionDetail extends Component
{
    public $aplicacionesId;

    public function mount($aplicacionesId)
    {
        $this->aplicacionesId = $aplicacionesId;
    }

    public function render()
    {
        return view('livewire.aplicacion-detail')->layout('layouts.app');
    }
}
