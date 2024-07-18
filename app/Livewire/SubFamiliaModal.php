<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Familia;
use Illuminate\Support\Facades\Log;
class SubFamiliaModal extends Component
{
    public $openModal = false;
    public $familia = [];

    public function consulta()
    {
        $this->familia = Familia::all();
        Log::info("Número de registros después de aplicar la consulta: {$this->familia}");
    }

    public function render()
    {
        $this->consulta();
        return view('livewire.sub-familia-modal', [
            'subfamilia' => $this->familia
        ]);

    }
}
