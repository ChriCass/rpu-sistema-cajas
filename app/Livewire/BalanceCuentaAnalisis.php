<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\BalanceCuentasAnalisisService;
use Illuminate\Support\Facades\Log;

class BalanceCuentaAnalisis extends Component
{
    public $tipoCuenta;
    public $cuenta;
    public $año;
    public $mes;
    public $registros;
    public $totales;
    protected $balanceCuentasAnalisisService;



    public function hydrate(BalanceCuentasAnalisisService $balanceCuentasAnalisisService)
    {
        $this->balanceCuentasAnalisisService = $balanceCuentasAnalisisService;
    }


    public function mount(BalanceCuentasAnalisisService $balanceCuentasAnalisisService,$tipoDeCuenta)
    {
        $this->balanceCuentasAnalisisService = $balanceCuentasAnalisisService;
        $this->tipoCuenta = $tipoDeCuenta;
        $this->cuenta = str_replace('_', ' ', request()->get('cuenta', 'balance'));  
        $this->año = request()->get('annio');
        $this->mes = request()->get('mes');
        $this->registros = collect($this->balanceCuentasAnalisisService->BalanceAnalisis($this->mes,$this->año,$this->cuenta));
        $this->totales = $this->balanceCuentasAnalisisService->totales($this->registros);

    }




    public function render()
    {
        return view('livewire.balance-cuenta-analisis')->layout('layouts.app');
    }
}
