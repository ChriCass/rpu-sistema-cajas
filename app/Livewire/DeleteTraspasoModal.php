<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MovimientoDeCaja;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class DeleteTraspasoModal extends Component
{
    public $openModal;
    public $detalles;
    public function mount($detalles)
    {
        $this->detalles = $detalles;
    }

    public function deleteAplication()
{
    DB::beginTransaction(); // Iniciar transacción

    try {
        Log::info("Intentando eliminar movimientos con mov: {$this->detalles}");

        // Eliminar los movimientos
        MovimientoDeCaja::where('id_libro', 4)
            ->where('mov', $this->detalles)
            ->delete();

        DB::commit(); // Confirmar la transacción

        session()->flash('message', 'Movimiento eliminado exitosamente.');
        Log::info("Movimientos eliminados exitosamente: {$this->detalles}");

        // Redireccionar a la ruta 'aplicaciones' con navegación dinámica
        return $this->redirect(route('traspasos'), navigate: true);

    } catch (\Exception $e) {
        DB::rollBack(); // Revertir la transacción en caso de error
        Log::error("Error al eliminar el movimiento: {$e->getMessage()}");
        session()->flash('error', 'Ocurrió un error al eliminar el movimiento.');
    }
}
    public function render()
    {
        return view('livewire.delete-traspaso-modal');
    }
}
