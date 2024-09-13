<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Apertura;
use DateTime;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use WireUi\Actions;
class CuadroDePendientesModal extends Component
{
    public $openModal = false;
    public $aperturaId;
    public $fechaApertura;
    public $moneda = "PEN"; // Moneda por defecto, puede ser dinámica
    public $pendientes = [];
    public $contenedor;
   
     
    public function mount($aperturaId)
    {
        $this->aperturaId = $aperturaId;

        // Recuperar la fecha directamente usando el aperturaId
        $apertura = Apertura::findOrFail($aperturaId);
        $this->fechaApertura = (new DateTime($apertura->fecha))->format('Y-m-d');



        // Ejecutar la consulta dinámica
        $this->pendientes = DB::table(DB::raw('(
            SELECT 
                CON3.id AS id_documentos,  
                CON3.fechaEmi,
                CON3.id_entidades,
                tdoc.descripcion AS tdoc,
                entidades.descripcion AS RZ,
                CONCAT(CON3.serie, "-", CON3.numero) AS Num,
                IF(CON3.Descripcion = "DETRACCIONES POR PAGAR", "PEN", CON3.id_t04tipmon) AS Mon,
                CON3.Descripcion,
                CON3.totalBi AS monto,
                CON3.montoNeto AS montodo
            FROM (
                SELECT 
                    documentos.id,
                    documentos.fechaEmi,
                    documentos.id_t10tdoc,
                    documentos.id_entidades,
                    documentos.serie,
                    documentos.numero,
                    documentos.id_t04tipmon,
                    cuentas.Descripcion,
                    documentos.totalBi,
                    documentos.montoNeto
                FROM documentos
                LEFT JOIN cuentas ON documentos.id_t10tdoc = cuentas.id
                WHERE documentos.id_tipmov = 2 -- Cuentas por pagar (compras)
                AND documentos.totalBi <> 0
            ) CON3
            LEFT JOIN entidades ON CON3.id_entidades = entidades.id 
            LEFT JOIN tabla10_tipodecomprobantedepagoodocumento AS tdoc ON CON3.id_t10tdoc = tdoc.id
            WHERE CON3.totalBi > 0
        ) as subquery'))
        ->where('fechaEmi', '<=', $this->fechaApertura)
        ->where('Mon', '=', $this->moneda)
        ->whereRaw('IF(Mon = "PEN", monto, montodo) <> 0')
        ->where('tdoc', '<>', 'Vaucher de Transferencia')
        ->orderBy(DB::raw('CAST(id_documentos AS UNSIGNED)'), 'asc')
        ->get();
        

        Log::info('Consulta de pendientes ejecutada', [
            'aperturaId' => $this->aperturaId,
            'fechaApertura' => $this->fechaApertura,
            'moneda' => $this->moneda,
            'pendientes' => $this->pendientes,
        ]);
    }

    
    public function toggleSelection($idDocumento)
    {
        // Inicializa el contenedor si es null
        $this->contenedor = $this->contenedor ?? [];

        // Buscar el documento en la lista de pendientes
        $pendiente = collect($this->pendientes)->firstWhere('id_documentos', $idDocumento);

        if ($pendiente) {
            if (collect($this->contenedor)->contains('id_documentos', $pendiente->id_documentos)) {
                $this->contenedor = array_filter($this->contenedor, function ($item) use ($pendiente) {
                    return $item->id_documentos !== $pendiente->id_documentos;
                });
                Log::info('Documento eliminado del contenedor', ['documento' => $pendiente]);
            } else {
                $this->contenedor[] = $pendiente;
                Log::info('Documento añadido al contenedor', ['documento' => $pendiente]);
            }
        }

        Log::info('Estado actual del contenedor', ['contenedor' => $this->contenedor]);
    }

    /* 
    public function resetSelection()
    {
        $this->contenedor = [];   // Limpia el contenedor
        $this->openModal = false; // Cierra el modal
        Log::info('El contenedor ha sido reiniciado y el modal ha sido cerrado');
    }
*/
public function sendingData()
{
    $this->dispatch('sendingContenedor', $this->contenedor);
    
    if (!empty($this->contenedor)) {
        // Almacenar mensaje de éxito en la sesión
        session()->flash('message', 'Datos enviados correctamente.');
        Log::info("El array se envió", ["contenedor" => $this->contenedor]);
    } else {
        // Almacenar mensaje de advertencia en la sesión
        session()->flash('warning', 'No se envió nada a la tabla.');
        Log::info("No se envió nada a la tabla", ["contenedor" => $this->contenedor]);
    }
}

    

    public function render()
    {
        return view('livewire.cuadro-de-pendientes-modal', [
            'pendientes' => $this->pendientes,
        ]);
    }
}
