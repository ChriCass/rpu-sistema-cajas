<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use App\Models\TipoDeCaja;
use Illuminate\Support\Facades\DB;

class EditCajaModal extends ModalComponent
{
    public $cajaId; // Cambiado de cuentaId a cajaId
    public $descripcion;

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function mount(int $cajaId) // Cambiado de cuentaId a cajaId
    {
        Log::info("Mounting EditCajaModal with cajaId: {$cajaId}");

        $this->cajaId = $cajaId;
        $caja = TipoDeCaja::findOrFail($cajaId); // Usamos el modelo TipoDeCaja
        $this->descripcion = $caja->descripcion;
    }

    public function save()
    {
        Log::info("Attempting to save caja with id: {$this->cajaId}");

        $this->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        DB::transaction(function () {
            // Bloquear la fila para evitar concurrencia
            $caja = TipoDeCaja::lockForUpdate()->findOrFail($this->cajaId);

            $caja->descripcion = $this->descripcion;
            $caja->save();

            Log::info("Successfully saved caja: ", $caja->toArray());

            session()->flash('message', 'Caja actualizada exitosamente.');
            $this->dispatch('cajaUpdated'); // Cambiado de cuentaUpdated a cajaUpdated
        }, 5); // 5 segundos de tiempo de espera para el bloqueo
    }

    public function render()
    {
        return view('livewire.edit-caja-modal');
    }
}
