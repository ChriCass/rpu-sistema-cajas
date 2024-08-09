<?php

namespace App\Livewire;

use LivewireUI\Modal\ModalComponent;
use Livewire\Component;
use App\Models\Familia;
use App\Models\SubFamilia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
class EditSubFamiliaModal extends ModalComponent
{
    public $subfamiliaId;
    public $descripcion;
    public $idFamilia;
    public $familias;

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function mount(int $subfamiliaId)
    {
        Log::info("Mounting EditSubFamiliaModal with subfamiliaId: {$subfamiliaId}");

        $this->subfamiliaId = $subfamiliaId;
        $subfamilia = SubFamilia::findOrFail($subfamiliaId);
        $this->descripcion = $subfamilia->desripcion;
        $this->idFamilia = $subfamilia->id_familias;
        $this->familias = Familia::all()->map(function ($familia) {
            return ['id' => $familia->id, 'descripcion' => $familia->descripcion];
        })->toArray();
    }

    public function save()
{
    Log::info("Attempting to save subfamilia with id: {$this->subfamiliaId}");

    $this->validate([
        'descripcion' => 'required|string|max:255',
    ]);

    DB::transaction(function () {
        // Bloquear la fila para evitar concurrencia
        $subfamilia = SubFamilia::lockForUpdate()->findOrFail($this->subfamiliaId);

        $subfamilia->desripcion = $this->descripcion;
        $subfamilia->id_familias = $this->idFamilia;
        $subfamilia->save();

        Log::info("Successfully saved subfamilia: ", $subfamilia->toArray());

        session()->flash('message', 'Subfamilia actualizada exitosamente.');
        $this->dispatch('subfamiliaUpdated');
    });
}



    public function render()
    {
        return view('livewire.edit-sub-familia-modal');
    }
}
