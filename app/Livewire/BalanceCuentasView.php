<?php

namespace App\Livewire;

use Livewire\Component;

class BalanceCuentasView extends Component
{
    public function render()
    {
        return view('livewire.balance-cuentas-view')->layout('layouts.app');
    }
}
