<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Familia;
use Illuminate\Support\Facades\Log;
use App\Models\SubFamilia;

class SubFamiliaModal extends Component
{
    public $openModal = false;
    public $familia = [];
    public $selectedFamilia;
    public $nuevaSubfamilia;
    public $nuevoId;

    protected $rules = [
        'selectedFamilia' => 'required',
        'nuevaSubfamilia' => 'required|string|max:255'
    ];

    protected $messages = [
        'nuevaSubfamilia.required' => 'El campo descripción es obligatorio.',
        'nuevaSubfamilia.max' => 'La descripción no puede tener más de 255 caracteres.',
        'selectedFamilia.required' => 'Debe seleccionar un tipo de familia.',
        'selectedFamilia.exists' => 'El tipo de familia seleccionado no es válido.',
    ];

    public function insertNewSubFamilia()
    {
        $this->validate();
        $this->nuevoId = SubFamilia::max('id') + 1;

        try {
            SubFamilia::create([
                'id_familias' => $this->selectedFamilia,
                'id' => $this->nuevoId,
                'desripcion' => $this->nuevaSubfamilia,
            ]);

          

             // Emitir el evento para refrescar la tabla
             $this->dispatch('subfamilia-created');


            // Limpiar campos después de insertar
            $this->reset(['selectedFamilia', 'nuevaSubfamilia']);

          

            // Emitir un evento o mensaje de éxito (opcional)
            session()->flash('message', 'SubFamilia creada exitosamente.');

        } catch (\Exception $e) {
            
            session()->flash('error', 'Ocurrió un error al crear la familia.');
        }

    }

    public function mount()
    {
        $this->familia = Familia::all();
        
    }

    public function render()
    {
        
        return view('livewire.sub-familia-modal');
    }
}
