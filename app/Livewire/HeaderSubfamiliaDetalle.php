<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
class HeaderSubfamiliaDetalle extends Component
{
    public $selectedSubfamilia = '';
    #[On('subfamiliaSelected')]
    public function updateSelectedSubfamilia($subfamilia)
    {
        $this->selectedSubfamilia = $subfamilia;
    }

    public function render()
    {
        return view('livewire.header-subfamilia-detalle');
    }
}
