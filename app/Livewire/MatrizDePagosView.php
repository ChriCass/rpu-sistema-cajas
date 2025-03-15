<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Services\MatrizDePagosServices;

class MatrizDePagosView extends Component
{
    public $movimientos = [];
    public $status = 'pendiente';
    public $filters = [];  // Filtros para los pagos
    public $hasFiltered = false;  // Si se ha aplicado algún filtro
    protected $matrizDePagosService;

 

    // Método hydrate asegura que el servicio se mantenga disponible en todas las solicitudes AJAX
    public function hydrate(MatrizDePagosServices $matrizDePagosService)
    {
        $this->matrizDePagosService = $matrizDePagosService;
    }

    public function updated($property)
    {
        if (str_starts_with($property, 'filters.') || $property === 'status') {
            $this->hasFiltered = true;
            $this->procesar();
        }
    }

    public function procesar()
    {
        try {
            if (!$this->matrizDePagosService) {
                throw new \Exception('El servicio MatrizDePagosService no está disponible.');
            }

            switch ($this->status) {
                case 'pendiente':
                    $this->movimientos = $this->matrizDePagosService->obtenerPagosPendientes();
                    $mensaje = 'Movimientos pendientes procesados correctamente.';
                    $this->dispatch('procesamientoTerminado');
                    break;

                case 'pagado':
                    $this->movimientos = $this->matrizDePagosService->obtenerPagosPagados();
                    $mensaje = 'Movimientos pagados procesados correctamente.';
                    $this->dispatch('procesamientoTerminado');
                    break;

                default:
                    $this->movimientos = $this->matrizDePagosService->obtenerTodosLosPagos();
                    $mensaje = 'Todos los movimientos procesados correctamente.';
                    $this->dispatch('procesamientoTerminado');
                    break;
            }

            // Verificación de movimientos vacíos
            foreach ($this->filters as $key => $value) {
                if (!empty($value)) {
                    // Filtrar si hay filtros
                    $this->movimientos = collect($this->movimientos)->filter(fn($item) => str_contains(strtolower($item->$key ?? ''), strtolower(trim($value))));
                }
            }

            if (empty($this->movimientos)) {
                session()->flash('warning', 'No hay movimientos en esta ocasión.');
            } else {
                session()->flash('success', $mensaje);
            }

        } catch (\Exception $e) {
            session()->flash('error', 'Ocurrió un error al procesar: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.matriz-de-pagos-view')->layout('layouts.app');
    }
}
