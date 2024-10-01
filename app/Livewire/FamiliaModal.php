<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Familia;
use App\Models\TipoFamilia;
use Illuminate\Support\Facades\DB;

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

        DB::transaction(function () {
            // Verificar si la familia ya existe con la misma descripción y tipo de familia
            $existingFamilia = Familia::where('descripcion', $this->nuevafamilia)
                ->where('id_tipofamilias', $this->selectedTipoFamilia)
                ->first();

            if ($existingFamilia) {
                session()->flash('error', 'Esta familia ya está registrada con la misma descripción y tipo.');
                throw new \Exception('Familia duplicada.');
            }

            // Bloquear fila para evitar lecturas concurrentes
            $this->newId = Familia::lockForUpdate()->max('id') + 1;

            // Inserta la nueva familia
            Familia::create([
                'id' => $this->newId,
                'descripcion' => $this->nuevafamilia,
                'id_tipofamilias' => $this->selectedTipoFamilia,
            ]);

            // Emitir el evento para refrescar la tabla
            $this->dispatch('familia-created');

            // Limpiar campos después de insertar
            $this->reset(['nuevafamilia', 'selectedTipoFamilia']);

            // Emitir un evento o mensaje de éxito
            session()->flash('message', 'Familia creada exitosamente.');
        }, 5); // Tiempo de espera de la transacción (5 segundos)
    }

    public function closeModal()
    {
        $this->resetValidation();
        $this->reset(['nuevafamilia', 'selectedTipoFamilia']);
        $this->openModal = false;
    }

    public function clearFields()
    {
        $this->resetValidation();
        $this->reset(['nuevafamilia', 'selectedTipoFamilia']);
    }
    public function render()
    {

        return view('livewire.familia-modal');
    }
}
