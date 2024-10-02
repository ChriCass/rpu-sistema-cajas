<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Mes;
use App\Models\TipoDeCaja;
use App\Models\Apertura;


class MatrizDeCobrosView extends Component
{

    public $cajas;
    public $meses;
    public $mes;
    public $id_caja;
    public $numero;


    public function mount()
    {
        $this->meses = Mes::all();
        $this->cajas = TipoDeCaja::all();
    }

    public function render()
    {
        return view('livewire.matriz-de-cobros-view')->layout('layouts.app');
    }
}
