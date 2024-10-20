<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Services\MatrizDePagosServices;
class MatrizDePagosView extends Component
{
    public $movimientos = [];
    public $status = 'pendiente';
    protected $matrizDePagosService;

    // Método mount inicializa el servicio al cargar el componente
    

    // Método hydrate asegura que el servicio se mantenga disponible en todas las solicitudes AJAX
    public function hydrate(MatrizDePagosServices $matrizDePagosService)
    {
        $this->matrizDePagosService = $matrizDePagosService;
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
                    break;
    
                case 'pagado':
                    $this->movimientos = $this->matrizDePagosService->obtenerPagosPagados();
                    $mensaje = 'Movimientos pagados procesados correctamente.';
                    break;
    
                default:
                    $this->movimientos = $this->matrizDePagosService->obtenerTodosLosPagos();
                    $mensaje = 'Todos los movimientos procesados correctamente.';
                    break;
            }
    
            // Verificación de movimientos vacíos
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
