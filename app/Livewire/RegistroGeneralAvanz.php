<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
class RegistroGeneralAvanz extends Component
{

    public $aperturaId;
    public $origen;
 
    public function mount()
    {
        $this->aperturaId = request()->get('aperturaId');
        $this->origen = request()->get('origen' ); // Valor por defecto: 'ingreso'

        
    }
    

    public function render()
    {
        return view('livewire.registro-general-avanz')->layout('layouts.app');

    }
}
