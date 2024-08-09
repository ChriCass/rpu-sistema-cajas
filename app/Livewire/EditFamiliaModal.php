<?php

namespace App\Livewire;
use App\Models\Familia;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use App\Models\TipoFamilia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
class EditFamiliaModal   extends ModalComponent
{  public $familiaId;
    public $descripcion;
    public $idTipofamilias;
    public $tipoFamilias;

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function mount(int $familiaId)
    {
        Log::info("Mounting EditFamiliaModal with familiaId: {$familiaId}");
        
        $this->familiaId = $familiaId;
        $familia = Familia::findOrFail($familiaId);
        $this->descripcion = $familia->descripcion;
        $this->idTipofamilias = $familia->id_tipofamilias;
        $this->tipoFamilias = TipoFamilia::all()->map(function ($tipoFamilia) {
            return ['id' => $tipoFamilia->id, 'descripcion' => $tipoFamilia->descripcion];
        })->toArray();

        
    }

    public function save()
{
    Log::info("Attempting to save familia with id: {$this->familiaId}");

    $this->validate([
        'descripcion' => 'required|string|max:255',
    ]);

    DB::transaction(function () {
        // Bloquear la fila para evitar concurrencia
        $familia = Familia::lockForUpdate()->findOrFail($this->familiaId);

        $familia->descripcion = $this->descripcion;
        $familia->id_tipofamilias = $this->idTipofamilias;
        $familia->save();

        Log::info("Successfully saved familia: ", $familia->toArray());

        session()->flash('message', 'Familia actualizada exitosamente.');
        $this->dispatch('familiaUpdated');
    }, 5); // 5 segundos de tiempo de espera para el bloqueo

}

    public function render()
    {
        return view('livewire.edit-familia-modal');
    }
}
