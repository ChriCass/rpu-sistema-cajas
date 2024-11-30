<?php

namespace App\Livewire;

use Livewire\Component;

class BalanceCuentaAnalisis extends Component
{
    public $tipoCuenta;
    public $cuenta;

    public function mount($tipoDeCuenta)
    {
        $this->tipoCuenta = $tipoDeCuenta;
        $this->cuenta = str_replace('_', ' ', request()->get('cuenta', 'balance'));  
       
    }


    public function render()
    {
        return view('livewire.balance-cuenta-analisis')->layout('layouts.app');
    }
}
