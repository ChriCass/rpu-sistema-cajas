<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Familia;
use Illuminate\Support\Facades\Log;
use App\Models\SubFamilia;
use Illuminate\Support\Facades\DB;

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

        DB::transaction(function () {
            // Verificar si ya existe una subfamilia con la misma descripción para la familia seleccionada
            $existingSubFamilia = SubFamilia::where('id_familias', $this->selectedFamilia)
                ->where('desripcion', $this->nuevaSubfamilia)
                ->first();

            if ($existingSubFamilia) {
                session()->flash('error', 'Esta subfamilia ya está registrada bajo la familia seleccionada.');
                throw new \Exception('Subfamilia duplicada.');
            }

            // Bloquear fila para evitar concurrencia
            $this->nuevoId = SubFamilia::lockForUpdate()->max('id') + 1;
            // Crear la nueva subfamilia
            SubFamilia::create([
                'id_familias' => $this->selectedFamilia,
                'id' => str_pad($this->nuevoId, 3, '0', STR_PAD_LEFT),
                'desripcion' => $this->nuevaSubfamilia,
            ]);

            // Emitir el evento para refrescar la tabla
            $this->dispatch('subfamilia-created');

            // Limpiar campos después de insertar
            $this->reset(['selectedFamilia', 'nuevaSubfamilia']);

            // Emitir un mensaje de éxito
            session()->flash('message', 'SubFamilia creada exitosamente.');
        }, 5); // Tiempo de espera de la transacción
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
