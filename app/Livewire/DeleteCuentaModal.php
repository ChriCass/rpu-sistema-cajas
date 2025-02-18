<?php

namespace App\Livewire;

use App\Models\Cuenta;
use App\Models\MovimientoDeCaja;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class DeleteCuentaModal extends ModalComponent
{
    public $openModal = false;
    public $cuentaId;
    public $hasMovimientos = false;

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function mount($cuentaId)
    {
        $this->cuentaId = $cuentaId;
        $this->hasMovimientos();
    }

    public function hasMovimientos()
    {
        // Verifica si la cuenta tiene movimientos asociados en MovimientoDeCaja
        $movimientos = MovimientoDeCaja::where('id_cuentas', $this->cuentaId)->exists();

        // Si hay movimientos, cambia hasMovimientos a true
        $this->hasMovimientos = $movimientos;
    }

    public function deleteCuenta()
    {
        // Verifica si hay movimientos antes de eliminar
        if ($this->hasMovimientos) {
            session()->flash('error', 'No se puede eliminar la cuenta porque tiene movimientos asociados.');
            return;
        }

        // Si no hay movimientos, elimina la cuenta
        $cuenta = Cuenta::find($this->cuentaId);
        if ($cuenta) {
            $cuenta->delete();
            session()->flash('message', 'Cuenta eliminada con éxito.');

            // Redirige a la ruta 'cuentas'
            return redirect()->route('cuentas');
        } else {
            session()->flash('error', 'Cuenta no encontrada.');
            return redirect()->route('cuentas');
        }
    }

    public function render()
    {
        return view('livewire.delete-cuenta-modal');
    }
}
