<?php

namespace App\Livewire;

use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
class ImportarExcel extends Component
{

    private $options;

    public function getOptions(){
        return $this->options;
    }

    public function setOptions($value)
    {
        $this->options = $value;
    }


    public function mount()
    {
        $this->setOptions([
            ['id' => 'cxc', 'name' => 'Cuentas por Cobrar (CXC)'],
            ['id' => 'cxp', 'name' => 'Cuentas por Pagar (CXP)'],
        ]);
    }


    public function procesar()
    {

    }


    public function render()
    {
        return view('livewire.importar-excel', [
            'options' => $this->getOptions(),  
        ]);
    }
}
