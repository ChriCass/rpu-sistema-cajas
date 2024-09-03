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

    public function mount($detalles, $fecha, $aplicacionesId)
    {
        // Asignar los detalles a la propiedad de la clase al montar el componente
        $this->detalles = $detalles;
        $this->fecha = $fecha;
        $this->aplicacionesId = $aplicacionesId;
        // Log para verificar que los detalles han sido asignados correctamente al montar
        Log::info("Detalles recibidos en mount: ", $this->detalles);
        Log::info("Fecha recibidos en mount: ". $this->fecha);
        $this->monedas = TipoDeMoneda::all();

        // Verificar si hay valores en montododebe o montodohaber
        $this->moneda = 'PEN'; // Valor por defecto
        $this->contenedor = $this->detalles;
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
        // Convertir stdClass a array si es necesario y mapear las claves
        $detallesSeleccionados = array_map(function($item) {
            return [
                'id' => $item['id_documentos'] ?? null, // Usar 'id_documentos' para 'id'
                'tdoc' => $item['tdoc'] ?? null,        // Usar 'tdoc' tal como está
                'id_entidades' => $item['id_entidades'] ?? null, // Usar 'id_entidades' tal como está
                'entidades' => $item['RZ'] ?? null,     // Usar 'RZ' para 'entidades'
                'num' => $item['Num'] ?? null,          // Usar 'Num' para 'num'
                'id_t04tipmon' => $item['Mon'] ?? null, // Usar 'Mon' para 'id_t04tipmon'
                'cuenta' => $item['Descripcion'] ?? null, // Usar 'Descripcion' para 'cuenta'
                'monto' => $item['monto'] ?? null,      // Usar 'monto' tal como está
                'montodebe' => $item['monto'] ?? null,  // Usar 'monto' también para 'montodebe'
                'montohaber' => null,                   // Asignar 'montohaber' como null
                'montododebe$' => null,                 // Asignar 'montododebe$' como null
                'montodohaber$' => null                 // Asignar 'montodohaber$' como null
            ];
        }, $detallesSeleccionados);
    
        // Registrar datos recibidos para depuración
        Log::info("Datos recibidos en receivingContenedorAplicaciones: ", $detallesSeleccionados);
    
        // Combinar el nuevo array con el contenedor existente
        $this->contenedor = array_merge($this->contenedor, $detallesSeleccionados);
    
        // Registrar el contenedor actualizado
        Log::info("Contenedor actualizado: ", $this->contenedor);
    }

    public function render()
    {
        return view('livewire.form-edit-aplicacion-detail');
    }
}
