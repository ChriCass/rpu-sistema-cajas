<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;
use App\Models\TipoDeMoneda;

class FormEditAplicacionDetail extends Component
{
    public $aplicacionesId;
    public $detalles = [];
    public $fecha;
    public $monedas;
    public $haveDolares;
    public $moneda;
    public $contenedor = []; // Contenedor para acumular los detalles seleccionados
    public $TotalDebe = 0;   // Inicializar TotalDebe
    public $TotalHaber = 0;  // Inicializar TotalHaber

    public function mount($detalles, $fecha, $aplicacionesId)
    {
        // Asignar los detalles a la propiedad de la clase al montar el componente
        $this->detalles = $detalles;
        $this->fecha = $fecha;
        $this->aplicacionesId = $aplicacionesId;

        Log::info("Detalles recibidos en mount: ", $this->detalles);
        Log::info("Fecha recibidos en mount: " . $this->fecha);

        $this->monedas = TipoDeMoneda::all();
        $this->contenedor = $this->detalles;

        // Calcular los valores iniciales de TotalDebe y TotalHaber
        foreach ($this->detalles as $detalle) {
            if ($detalle['montodebe'] !== null) {
                $this->TotalDebe += $detalle['montodebe'];
            }

            if ($detalle['montohaber'] !== null) {
                $this->TotalHaber += $detalle['montohaber'];
            }
        }

        Log::info("TotalDebe inicial: $this->TotalDebe, TotalHaber inicial: $this->TotalHaber");

        // Verificar si hay valores en montododebe o montodohaber
        $this->moneda = 'PEN'; // Valor por defecto
        foreach ($this->detalles as $detalle) {
            if ($detalle['montododebe$'] !== null || $detalle['montodohaber$'] !== null) {
                $this->moneda = 'USD';
                break; // Si encontramos un valor en dólares, no necesitamos seguir buscando
            }
        }
    }

    #[On('sendingContenedorAplicaciones')]
    public function receivingContenedorAplicaciones($detallesSeleccionados)
    {
        // Convertir stdClass a array si es necesario
        $detallesSeleccionados = array_map(function($item) {
            return (array) $item;
        }, $detallesSeleccionados);
    
        Log::info("Detalles recibidos en receivingContenedorAplicaciones: ", $detallesSeleccionados);
    
        // Limpiar el contenedor que ya estaba previamente recibido a través del evento,
        // pero no tocar los detalles que ya estaban desde antes
        $this->contenedor = $this->detalles; // Mantener los detalles originales
    
        // Agregar los nuevos detalles recibidos al contenedor
        foreach ($detallesSeleccionados as $detalle) {
            // Asignar valores a las columnas dependiendo del tipo de cuenta
            if ($detalle['Descripcion'] === 'CUENTAS POR COBRAR' || $detalle['Descripcion'] === 'DETRACCIONES POR COBRAR') {
                $detalle['montodebe'] = null;
                $detalle['montohaber'] = $detalle['monto'];
            } else {
                $detalle['montodebe'] = $detalle['monto'];
                $detalle['montohaber'] = null;
            }
    
            // Agregar el detalle al contenedor con las keys adaptadas
            $this->contenedor[] = [
                'id' => null,  // Puedes asignar un valor si es necesario
                'tdoc' => $detalle['tdoc'],
                'id_entidades' => $detalle['id_entidades'],
                'entidades' => $detalle['RZ'],  // Usar 'RZ' como 'entidades'
                'num' => $detalle['Num'],
                'id_t04tipmon' => $detalle['Mon'],
                'cuenta' => $detalle['Descripcion'],
                'monto' => $detalle['monto'],
                'montodebe' => $detalle['montodebe'],
                'montohaber' => $detalle['montohaber'],
            ];
        }
    
        // Recalcular dinámicamente los totales cada vez que cambie el contenedor
        $this->recalcularTotales();
    
        Log::info("Contenedor actualizado: ", $this->contenedor);
        Log::info("TotalDebe: $this->TotalDebe, TotalHaber: $this->TotalHaber");
    }
    
    /**
     * Función para recalcular los totales de debe y haber dinámicamente.
     */
    private function recalcularTotales()
    {
        $this->TotalDebe = 0;   // Reiniciar el total de debe
        $this->TotalHaber = 0;  // Reiniciar el total de haber
    
        foreach ($this->contenedor as $detalle) {
            if ($detalle['montodebe'] !== null) {
                $this->TotalDebe += $detalle['montodebe'];
            }
            if ($detalle['montohaber'] !== null) {
                $this->TotalHaber += $detalle['montohaber'];
            }
        }
    
        // Log para verificar los cálculos actualizados
        Log::info("TotalDebe actualizado: $this->TotalDebe, TotalHaber actualizado: $this->TotalHaber");
    }
    
    public function render()
    {
        return view('livewire.form-edit-aplicacion-detail');
    }
}
