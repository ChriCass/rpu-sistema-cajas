<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\MovimientoDeCaja;

class EditAplicacionDetail extends Component
{   
    public $aplicacionesId;
    public $aplicacion;
    public $fecha;
    public $detalles = [];
    
    public function mount($aplicacionesId)
    {
        $this->aplicacionesId = $aplicacionesId;

        // Primera consulta para obtener la aplicación
        Log::info("Iniciando consulta para obtener la aplicación con mov: {$this->aplicacionesId}");

        $aplicaciones = MovimientoDeCaja::selectRaw(
            "'APLICACIONES' as apl, DATE_FORMAT(fec, '%d/%m/%Y') as fec, mov, 
            SUM(CASE WHEN id_dh = '1' THEN monto ELSE 0 END) as debe, 
            SUM(CASE WHEN id_dh = '2' THEN monto ELSE 0 END) as haber, 
            SUM(CASE WHEN montodo IS NULL THEN ' ' ELSE montodo END) as do"
        )
        ->leftJoin('cuentas', 'movimientosdecaja.id_cuentas', '=', 'cuentas.id')
        ->where('id_libro', '4')
        ->groupBy('apl', 'fec', 'mov')
        ->get()
        ->map(function($item) {
            return [
                'apl' => $item->apl,
                'fec' => $item->fec,
                'mov' => $item->mov,
                'monto' => ($item->debe + $item->haber) / 2,
                'monto_do' => $item->do,
            ];
        });

        Log::info("Aplicaciones obtenidas: ", $aplicaciones->toArray());

        $this->aplicacion = $aplicaciones->firstWhere('mov', $this->aplicacionesId);

        if ($this->aplicacion) {
            // Convertir la fecha a un formato adecuado sin usar Carbon
            $date = \DateTime::createFromFormat('d/m/Y', $this->aplicacion['fec']);
            $this->fecha = $date ? $date->format('Y-m-d') : null;
            $this->dispatch('sendingFecha', $this->fecha);
            Log::info("Aplicación encontrada: ", $this->aplicacion);
        } else {
            Log::warning("No se encontró la aplicación con mov igual a {$this->aplicacionesId}");
        }
        // Segunda consulta para obtener los detalles
        Log::info("Iniciando consulta para obtener los detalles de la aplicación.");

        $this->detalles = MovimientoDeCaja::selectRaw("
        documentos.id AS id,
        tabla10_tipodecomprobantedepagoodocumento.descripcion as tdoc,
        documentos.id_entidades AS id_entidades,
        entidades.descripcion as entidades,
        CONCAT(documentos.serie, '-', documentos.numero) AS num,
        documentos.id_t04tipmon AS id_t04tipmon,
        cuentas.Descripcion as cuenta,
        CASE 
            WHEN cuentas.Descripcion = 'DETRACCIONES POR COBRAR' THEN documentos.detraccion 
            ELSE documentos.precio
        END AS monto,
        CASE WHEN id_dh = '1' THEN monto END AS montodebe,
        CASE WHEN id_dh = '2' THEN monto END AS montohaber,
        CASE WHEN id_dh = '1' THEN montodo END AS montododebe$,
        CASE WHEN id_dh = '2' THEN montodo END AS montohaber$
    ")
    ->leftJoin('cuentas', 'movimientosdecaja.id_cuentas', '=', 'cuentas.id')
    ->leftJoin('documentos', 'movimientosdecaja.id_documentos', '=', 'documentos.id')
    ->leftJoin('tabla10_tipodecomprobantedepagoodocumento', 'documentos.id_t10tdoc', '=', 'tabla10_tipodecomprobantedepagoodocumento.id')
    ->leftJoin('entidades', 'documentos.id_entidades', '=', 'entidades.id')
    ->where('movimientosdecaja.mov', $this->aplicacionesId)
    ->where('id_libro', '4')
    ->get();
    
        $this->dispatch('sendDetallesToParent', $this->detalles->toArray());
        if ($this->detalles->isNotEmpty()) {
            Log::info("Detalles encontrados: ", $this->detalles->toArray());
        } else {
            Log::warning("No se encontraron detalles para la aplicación con mov igual a {$this->aplicacionesId}");
        }
    }
    


    public function render()
    {
        Log::info("Renderizando la vista para EditAplicacionDetail con aplicacionesId: {$this->aplicacionesId}");

        return view('livewire.edit-aplicacion-detail', [
            'aplicacion' => $this->aplicacion,
            'fecha' => $this->fecha,
            'detalles' => $this->detalles,
        ]);
    }
}
