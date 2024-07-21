<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Familia;
use App\Models\SubFamilia;
use App\Models\Detalle;

class InputFamiliaDetalle extends Component
{   
    public $familias = [];
    public $subfamilias = [];
    public $selectedFamilia = null;

    public function mount()
    {
        $this->familias = Familia::all();
    }

    public function updatedSelectedFamilia($value)
    {
        $this->subfamilias = SubFamilia::where('id_familias', $value)->get();
    }

    public function render()
    {
        return view('livewire.input-familia-detalle', [
            'familias' => $this->familias,
            'subfamilias' => $this->subfamilias
        ]);
    }
}
