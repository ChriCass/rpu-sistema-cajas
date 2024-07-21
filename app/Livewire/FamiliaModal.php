<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Familia;
use App\Models\TipoFamilia;
use Illuminate\Support\Facades\Log;
class FamiliaModal extends Component
{

    public $openModal = false;
    public $tipoFamilia;
    public $nuevafamilia;
    public $newId;
    public $selectedTipoFamilia;

    protected $rules = [
        'nuevafamilia' => 'required|string|max:255',
        'selectedTipoFamilia' => 'required',
    ];

    protected $messages = [
        'nuevafamilia.required' => 'El campo descripción es obligatorio.',
        'nuevafamilia.max' => 'La descripción no puede tener más de 255 caracteres.',
        'selectedTipoFamilia.required' => 'Debe seleccionar un tipo de familia.',
        'selectedTipoFamilia.exists' => 'El tipo de familia seleccionado no es válido.',
    ];

    public function mount()
    {
        $this->tipoFamilia = TipoFamilia::all();
    }

    public function insertNewFamilia()
    {
        $this->validate();

        $this->newId = Familia::max('id') + 1;

        try {
            Familia::create([
                'id' => $this->newId,
                'descripcion' => $this->nuevafamilia,
                'id_tipofamilias' => $this->selectedTipoFamilia,
            ]);

            Log::info('Familia creada exitosamente.', [
                'id' => $this->newId,
                'descripcion' => $this->nuevafamilia,
                'id_tipofamilias' => $this->selectedTipoFamilia,
            ]);

             // Emitir el evento para refrescar la tabla
             $this->dispatch('familia-created');


            // Limpiar campos después de insertar
            $this->reset(['nuevafamilia', 'selectedTipoFamilia']);

          

            // Emitir un evento o mensaje de éxito (opcional)
            session()->flash('message', 'Familia creada exitosamente.');

        } catch (\Exception $e) {
            Log::error('Error al crear la familia.', ['error' => $e->getMessage()]);
            session()->flash('error', 'Ocurrió un error al crear la familia.');
        }
    }
    public function render()
    {

        return view('livewire.familia-modal');
    }
}
