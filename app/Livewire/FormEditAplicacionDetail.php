<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;
use App\Models\TipoDeMoneda;
use App\Models\MovimientoDeCaja;
use App\Models\Cuenta;
use Illuminate\Support\Facades\DB;


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
    public $balance = 0;     // Inicializar el balance

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

        // Calcular el balance inicial
        $this->recalcularBalance();
    }

    public function submit()
    {
        Log::info('Iniciando la función submit.');
    
        // Validar si falta la fecha
        if (empty($this->fecha)) {
            Log::error('Falta la fecha.');
            session()->flash('error', 'Falta llenar campos.');
            return;
        }
    
        // Validar si el contenedor está vacío
        if (count($this->contenedor) == 0) {
            Log::error('El contenedor está vacío.');
            session()->flash('error', 'Debe seleccionar al menos un ítem.');
            return;
        }
    
        // Validar si el balance no está cuadrado
        if ($this->balance != 0) {
            Log::error("El balance no cuadra. Balance actual: $this->balance");
            session()->flash('error', 'El asiento no cuadra.');
            return;
        }
    
        // Verificar si se han añadido nuevos detalles
        $nuevosDatos = false;
        foreach ($this->contenedor as $detalle) {
            if ($detalle['id'] === null) { // Si el detalle no tiene ID, es nuevo
                $nuevosDatos = true;
                break;
            }
        }
    
        // Si no se añadieron nuevos datos, mostrar alerta de advertencia
        if (!$nuevosDatos) {
            Log::info('No se han añadido nuevos datos.');
            session()->flash('warning', 'No se ha modificado nada.');
            return;
        }
    
        Log::info('Validaciones completadas. Iniciando transacción.');
    
        DB::transaction(function () {
            // Borrar transacciones previas usando Eloquent
            Log::info('Eliminando transacciones previas para el libro 4, movimiento: ' . $this->aplicacionesId);
            MovimientoDeCaja::where('id_libro', 4)
                ->where('mov', $this->aplicacionesId)
                ->delete();
            Log::info('Transacciones anteriores eliminadas.');
    
            // Procesar las entradas de contenedor
            foreach ($this->contenedor as $detalle) {
                Log::info('Procesando detalle: ', $detalle);
    
                // Buscar la cuenta relacionada
                $cuenta = Cuenta::where('Descripcion', $detalle['cuenta'])->first();
                
                if (!$cuenta) {
                    Log::error('Cuenta no encontrada para: ' . $detalle['cuenta']);
                    continue;
                }
    
                $iddoc = $detalle['id'] ?? null;
                $dh = $detalle['montodebe'] ? '1' : '2';
                $monto = $detalle['montodebe'] ?? $detalle['montohaber'];
    
                Log::info("Insertando movimiento en la base de datos: 
                    Libro: 4, 
                    Movimiento: $this->aplicacionesId, 
                    Fecha: $this->fecha, 
                    Documento: $iddoc, 
                    Cuenta: {$cuenta->id}, 
                    Debe/Haber: $dh, 
                    Monto: $monto");
    
                // Crear la nueva transacción
                MovimientoDeCaja::create([
                    'id_libro' => 4,
                    'mov' => $this->aplicacionesId,
                    'fec' => $this->fecha,
                    'id_documentos' => $iddoc,
                    'id_cuentas' => $cuenta->id,
                    'id_dh' => $dh,
                    'monto' => $monto,
                    'montodo' => null,
                ]);
    
                Log::info('Movimiento creado exitosamente.');
            }
        });
    
        session()->flash('message', 'Transacción exitosa.');
        Log::info('Transacción finalizada exitosamente.', $this->contenedor);

        $this->dispatch('refresh table');
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
    
        // Recalcular dinámicamente los totales y el balance cada vez que cambie el contenedor
        $this->recalcularTotales();
        $this->recalcularBalance();
    
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

        Log::info("TotalDebe actualizado: $this->TotalDebe, TotalHaber actualizado: $this->TotalHaber");
    }

    /**
     * Función para recalcular el balance entre TotalDebe y TotalHaber.
     */
    private function recalcularBalance()
    {
        $this->balance = $this->TotalDebe - $this->TotalHaber; // Calcular el balance
        Log::info("Balance actualizado: $this->balance");
    }
    
    public function render()
    {
        return view('livewire.form-edit-aplicacion-detail');
    }
}
