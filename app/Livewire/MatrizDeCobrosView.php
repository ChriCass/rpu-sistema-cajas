<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Mes;
use App\Models\TipoDeCaja;
use App\Models\Apertura;
use Illuminate\Support\Facades\DB;
use App\Services\MatrizDeCobrosServices;
class MatrizDeCobrosView extends Component
{

    public $movimientos = [];
    public $status = 'pendiente'; // Radio button default value
    protected $matrizDeCobrosService;

    public function hydrate(MatrizDeCobrosServices $matrizDeCobrosService)
    {
        $this->matrizDeCobrosService = $matrizDeCobrosService;
         
    }

    public function procesar()
    {
        try {
            switch ($this->status) {
                case 'pendiente':
                    $this->movimientos = $this->matrizDeCobrosService->obtenerPagosPendientes();
                    $mensaje = 'Cobros pendientes procesados correctamente.';
                    break;
                case 'pagado':
                    $this->movimientos = $this->matrizDeCobrosService->obtenerPagosPagados();
                    $mensaje = 'Cobros pagados procesados correctamente.';
                    break;
                default:
                    $this->movimientos = $this->matrizDeCobrosService->obtenerTodosLosPagos();
                    $mensaje = 'Todos los cobros procesados correctamente.';
                    break;
            }

            if (empty($this->movimientos)) {
                session()->flash('warning', 'No hay cobros en esta ocasión.');
            } else {
                session()->flash('success', $mensaje);
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Ocurrió un error al procesar los cobros: ' . $e->getMessage());
        }
    }
       public function render()
    {
        return view('livewire.matriz-de-cobros-view')->layout('layouts.app');
    }
}
