<?php

namespace App\Livewire;

use App\Models\Apertura;
use App\Models\Cuenta;
use App\Models\TipoDeCaja;
use App\Models\TipoDeCuenta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class DeleteCajaModal extends ModalComponent
{   
    public $openModal = false;
    public $cajaId; // Cambiado de cuentaId a cajaId
    public $hasMovimientos = false;

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function mount($cajaId) // Cambiado de cuentaId a cajaId
    {
        $this->cajaId = $cajaId;
        $this->hasMovimientos();
    }

    public function hasMovimientos()
    {
        // Verifica si la caja tiene aperturas asociadas
        $aperturas = Apertura::query()
            ->join('tipodecaja', 'tipodecaja.id', '=', 'aperturas.id_tipo')
            ->join('meses', 'meses.id', '=', 'aperturas.id_mes')
            ->where('aperturas.id_tipo', $this->cajaId) // Filtra por el ID de la caja
            ->select([
                'aperturas.id',
                'tipodecaja.descripcion as tipo_de_caja_descripcion',
                'aperturas.numero',
                'aperturas.año',
                'meses.descripcion as mes_descripcion',
                'aperturas.id_mes',
                'aperturas.id_tipo',
                DB::raw("DATE_FORMAT(aperturas.fecha, '%d/%m/%Y') as fecha_formatted")
            ])
            ->orderBy('aperturas.id_mes', 'DESC')  // Orden descendente por mes
            ->orderBy('aperturas.numero', 'DESC') // Orden descendente por número
            ->exists(); // Verifica si hay registros

        // Si hay aperturas, cambia hasMovimientos a true
        $this->hasMovimientos = $aperturas;
    }

    public function deleteCaja()
    {
        // Verifica si hay movimientos antes de eliminar
        if ($this->hasMovimientos) {
            session()->flash('error', 'No se puede eliminar la caja porque tiene aperturas asociadas.');
            return;
        }

        // Si no hay movimientos, elimina la caja
        $caja = TipoDeCaja::find($this->cajaId);
        if ($caja) {
            $caja->delete();
            session()->flash('message', 'Caja eliminada con éxito.');

            // Redirige a la ruta 'cajas'
            return redirect()->route('cajas');
        } else {
            session()->flash('error', 'Caja no encontrada.');
            return redirect()->route('cajas');
        }
    }


    public function render()
    {
        return view('livewire.delete-caja-modal');
    }
}
