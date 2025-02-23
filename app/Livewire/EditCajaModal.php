<?php

namespace App\Livewire;

use App\Models\Apertura;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use App\Models\TipoDeCaja;
use App\Models\TipoDeMoneda;
use Illuminate\Support\Facades\DB;

class EditCajaModal extends ModalComponent
{
    public $cajaId;
    public $descripcion;
    public $t04_tipodemoneda; // Nuevo campo para el tipo de moneda
    public $hasMovimientos = false; // Para verificar si hay movimientos
    public $tiposDeMoneda; // Lista de tipos de moneda para el select

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function mount(int $cajaId)
    {
        Log::info("Mounting EditCajaModal with cajaId: {$cajaId}");

        $this->cajaId = $cajaId;
        $caja = TipoDeCaja::findOrFail($cajaId);
        $this->descripcion = $caja->descripcion;
        $this->t04_tipodemoneda = $caja->t04_tipodemoneda;

        // Obtener todos los tipos de moneda para el campo de selección
        $this->tiposDeMoneda = TipoDeMoneda::all()->map(function ($tipoMoneda) {
            return ['id' => $tipoMoneda->id, 'descripcion' => $tipoMoneda->descripcion];
        })->toArray();

        // Verificar si la caja tiene movimientos
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

    public function save()
    {
        Log::info("Attempting to save caja with id: {$this->cajaId}");

        $this->validate([
            'descripcion' => 'required|string|max:255',
            't04_tipodemoneda' => $this->hasMovimientos ? [] : ['required', 'exists:tabla04_tipodemoneda,id'], // Validar solo si no hay movimientos
        ]);

        DB::transaction(function () {
            // Bloquear la fila para evitar concurrencia
            $caja = TipoDeCaja::lockForUpdate()->findOrFail($this->cajaId);

            $caja->descripcion = $this->descripcion;

            // Solo actualizar el tipo de moneda si no hay movimientos
            if (!$this->hasMovimientos) {
                $caja->t04_tipodemoneda = $this->t04_tipodemoneda;
            }

            $caja->save();

            Log::info("Successfully saved caja: ", $caja->toArray());

            session()->flash('message', 'Caja actualizada exitosamente.');
            $this->dispatch('cajaUpdated');
        }, 5); // Tiempo de espera de la transacción (5 segundos)
    }
    public function render()
    {
        return view('livewire.edit-caja-modal');
    }
}
