<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use App\Models\MovimientoDeCaja;

class EditTraspasoDetail extends Component
{

    public $traspasoId;
    public $traspaso;
    public $fecha;
    public $detalles = [];
    
    public function mount($traspasoId)
    {
        $this->traspasoId = $traspasoId;
    
        // Primera consulta para obtener el traspaso
        Log::info("Iniciando consulta para obtener el traspaso con mov: {$this->traspasoId}");
    
        $traspasos = MovimientoDeCaja::selectRaw(
            "'TRASPASOS' as trp, DATE_FORMAT(fec, '%d/%m/%Y') as fec, mov, 
            SUM(CASE WHEN id_dh = '1' THEN monto ELSE 0 END) as debe, 
            SUM(CASE WHEN id_dh = '2' THEN monto ELSE 0 END) as haber, 
            SUM(CASE WHEN montodo IS NULL THEN ' ' ELSE montodo END) as do"
        )
        ->leftJoin('cuentas', 'movimientosdecaja.id_cuentas', '=', 'cuentas.id')
        ->where('id_libro', '4')
        ->groupBy('trp', 'fec', 'mov')
        ->get()
        ->map(function($item) {
            return [
                'trp' => $item->trp,
                'fec' => $item->fec,
                'mov' => $item->mov,
                'monto' => ($item->debe + $item->haber) / 2,
                'monto_do' => $item->do,
            ];
        });
    
        Log::info("Traspasos obtenidos: ", $traspasos->toArray());
    
        $this->traspaso = $traspasos->firstWhere('mov', $this->traspasoId);
    
        if ($this->traspaso) {
            // Convertir la fecha a un formato adecuado sin usar Carbon
            $date = \DateTime::createFromFormat('d/m/Y', $this->traspaso['fec']);
            $this->fecha = $date ? $date->format('Y-m-d') : null;
            $this->dispatch('sendingFecha', $this->fecha);
            Log::info("Traspaso encontrado: ", $this->traspaso);
        } else {
            Log::warning("No se encontró el traspaso con mov igual a {$this->traspasoId}");
        }
    
        // Segunda consulta para obtener los detalles
        Log::info("Iniciando consulta para obtener los detalles del traspaso.");
    
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
        ->where('movimientosdecaja.mov', $this->traspasoId)
        ->where('id_libro', '4')
        ->get();
        
        $this->dispatch('sendDetallesToParent', $this->detalles->toArray());
        if ($this->detalles->isNotEmpty()) {
            Log::info("Detalles encontrados: ", $this->detalles->toArray());
        } else {
            Log::warning("No se encontraron detalles para el traspaso con mov igual a {$this->traspasoId}");
        }
    }
    
    public function render()
    {
        return view('livewire.edit-traspaso-detail', [
            'traspaso' => $this->traspaso,
            'fecha' => $this->fecha,
            'detalles' => $this->detalles,
        ]);
    }
    
}
