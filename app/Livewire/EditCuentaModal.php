<?php

namespace App\Livewire;

use App\Models\Cuenta;
use App\Models\TipoDeCuenta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class EditCuentaModal extends ModalComponent
{
    public $cuentaId;
    public $descripcion;
    public $idTipoCuenta;
    public $tipoCuentas;

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function mount(int $cuentaId)
    {
        Log::info("Mounting EditCuentaModal with cuentaId: {$cuentaId}");

        $this->cuentaId = $cuentaId;
        $cuenta = Cuenta::findOrFail($cuentaId);
        $this->descripcion = $cuenta->descripcion;
        $this->idTipoCuenta = $cuenta->id_tcuenta;
        $this->tipoCuentas = TipoDeCuenta::all()->map(function ($tipoCuenta) {
            return ['id' => $tipoCuenta->id, 'descripcion' => $tipoCuenta->descripcion];
        })->toArray();
    }

    public function save()
    {
        Log::info("Attempting to save cuenta with id: {$this->cuentaId}");

        $this->validate([
            'descripcion' => 'required|string|max:255',
            'idTipoCuenta' => 'required|exists:tipodecuenta,id', // Validar que el tipo de cuenta exista
        ]);

        DB::transaction(function () {
            // Bloquear la fila para evitar concurrencia
            $cuenta = Cuenta::lockForUpdate()->findOrFail($this->cuentaId);

            $cuenta->descripcion = $this->descripcion;
            $cuenta->id_tcuenta = $this->idTipoCuenta;
            $cuenta->save();

            Log::info("Successfully saved cuenta: ", $cuenta->toArray());

            session()->flash('message', 'Cuenta actualizada exitosamente.');
            $this->dispatch('cuentaUpdated');
        }, 5); // 5 segundos de tiempo de espera para el bloqueo
    }


    public function render()
    {
        return view('livewire.edit-cuenta-modal');
    }
}
