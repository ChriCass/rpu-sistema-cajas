<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Familia;
use App\Models\SubFamilia;
use App\Models\Detalle;
use Illuminate\Support\Facades\Log;
class InputFamiliaDetalle extends Component
{   
    public $familias = [];
    public $subfamilias = [];
    public $selectedFamilia = null;
    public $selectedSubfamiliaid;

    public function mount()
    {
        $this->familias = Familia::all();
    }

    public function updatedSelectedFamilia($value)
    {
        $this->subfamilias = SubFamilia::where('id_familias', $value)->get();
        $selectedSubfamiliaId = $this->subfamilias->isNotEmpty() ? (string) $this->subfamilias->first()->id : null;
    
        Log::info('Emitiendo evento: famysub selected', ['familiaId' => (string) $value, 'subfamiliaId' => $selectedSubfamiliaId]);
        $this->dispatch('famysub selected', (string) $value, $selectedSubfamiliaId);
    }
    
    public function render()
    {
        return view('livewire.input-familia-detalle', [
            'familias' => $this->familias,
            'subfamilias' => $this->subfamilias
        ]);
    }
}
