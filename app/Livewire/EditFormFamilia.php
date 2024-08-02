<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Familia;
use App\Models\TipoFamilia;
class EditFormFamilia extends Component
{   
    public Familia $familia;
    public $tipoFamilia;
    public $selectedTipoFamilia;

    protected $rules = [
        'familia.descripcion' => 'required|string|max:255',
        'familia.id_tipofamilias' => 'required|integer',
    ];

    public function mount(Familia $familia)
    {
        $this->familia = $familia;
        $this->tipoFamilia = TipoFamilia::all();
        $this->selectedTipoFamilia = $familia->id_tipofamilias;
    }

    public function save()
    {
        $this->validate();
        $this->familia->save();

        session()->flash('message', 'Familia updated successfully.');
        
    }

    public function clearFields()
    {
        $this->resetValidation();
        $this->reset(['familia.descripcion', 'selectedTipoFamilia']);
    }

    public function render()
    {
        return view('livewire.edit-form-familia');
    }
}
