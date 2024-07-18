<?php

namespace App\Livewire;

use Livewire\Component;

class FamiliaModal extends Component
{

        public $openModal = false;
    public function render()
    {
        return view('livewire.familia-modal');
    }
}
