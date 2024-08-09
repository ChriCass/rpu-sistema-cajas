<?php

namespace App\Livewire;

use App\Models\SubFamilia;
use App\Models\Familia;
use App\Models\Cuenta;
use Livewire\Component;
use App\Models\Detalle;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class EditDetalleModal extends ModalComponent
{
    public $detalleId;
    public $familia;
    public $subfamilia;
    public $cuenta;
    public $descripcion;
    public $familias;
    public $subfamilias;
    public $cuentas;

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function mount(int $detalleId)
    {
         

        try {
            $this->detalleId = $detalleId;
            $detalle = Detalle::findOrFail($detalleId);
            $this->descripcion = $detalle->descripcion;
            $this->familia = $detalle->id_familias;
            $this->subfamilia = (int) $detalle->id_subfamilia;
            $this->cuenta = $detalle->id_cuenta;

           

            $this->familias = Familia::all()->map(function ($familia) {
                return ['id' => $familia->id, 'descripcion' => $familia->descripcion];
            })->toArray();

            $this->subfamilias = SubFamilia::all()->map(function ($subfamilia) {
                return ['id' => $subfamilia->id, 'descripcion' => $subfamilia->desripcion];
            })->toArray();
            
            $this->cuentas = Cuenta::all()->map(function ($cuenta) {
                return ['id' => $cuenta->id, 'descripcion' => $cuenta->descripcion];
            })->toArray();

           
        } catch (\Exception $e) {
            Log::error("Error loading detalle: " . $e->getMessage());
            session()->flash('error', 'Error al cargar el detalle.');
            $this->closeModal();
        }
    }

    public function save()
{
    $this->validate([
        'descripcion' => 'required|string|max:255',
        'familia' => 'required|exists:familias,id',
        'subfamilia' => 'required|exists:subfamilias,id',
        'cuenta' => 'required|exists:cuentas,id'
    ]);

    DB::transaction(function () {
        // Bloquear la fila para evitar concurrencia
        $detalle = Detalle::lockForUpdate()->findOrFail($this->detalleId);

        $detalle->descripcion = $this->descripcion;
        $detalle->id_familias = $this->familia;
        $detalle->id_subfamilia = $this->subfamilia;
        $detalle->id_cuenta = $this->cuenta;

        $detalle->save();

        Log::info("Successfully saved detalle: ", $detalle->toArray());

        session()->flash('message', 'Detalle actualizado exitosamente.');
        $this->dispatch('detalleUpdated');
    });
}


    public function render()
    {
        return view('livewire.edit-detalle-modal');
    }
}
