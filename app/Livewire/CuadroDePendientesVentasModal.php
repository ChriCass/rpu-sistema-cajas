<?php

namespace App\Livewire;

use Livewire\Component;
use DateTime;
use App\Models\Apertura;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CuadroDePendientesVentasModal extends Component
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
        WHERE documentos.id_tipmov = 1 -- Cuentas por pagar (compras)
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
    

    public function toggleSelection($idDocumento, $num, $descripcion)
    {
        // Inicializa el contenedor si es null
        $this->contenedor = $this->contenedor ?? [];
    
        // Buscar el documento en la lista de pendientes
        $pendiente = collect($this->pendientes)->first(function ($item) use ($idDocumento, $num, $descripcion) {
            return $item->id_documentos === $idDocumento && $item->Num === $num && $item->Descripcion === $descripcion;
        });
    
        if ($pendiente) {
            // Verificar si un documento idéntico ya está en el contenedor
            $exists = collect($this->contenedor)->contains(function ($item) use ($pendiente) {
                return $item->id_documentos === $pendiente->id_documentos &&
                       $item->Num === $pendiente->Num &&
                       $item->Descripcion === $pendiente->Descripcion;
            });
    
            if ($exists) {
                // Si existe, lo eliminamos del contenedor
                $this->contenedor = array_filter($this->contenedor, function ($item) use ($pendiente) {
                    return !(
                        $item->id_documentos === $pendiente->id_documentos &&
                        $item->Num === $pendiente->Num &&
                        $item->Descripcion === $pendiente->Descripcion
                    );
                });
    
                Log::info('Documento eliminado del contenedor', ['documento' => $pendiente]);
            } else {
                // Si no existe, lo añadimos al contenedor
                $this->contenedor[] = $pendiente; // $pendiente es un objeto, no un array
    
                Log::info('Documento añadido al contenedor', ['documento' => $pendiente]);
            }
        }
    
        Log::info('Estado actual del contenedor', ['contenedor' => $this->contenedor]);
    }
    


    public function sendingData()
    {
        if (!empty($this->contenedor)) {
            $this->dispatch('sendingContenedorVentas', $this->contenedor);

            // Almacenar mensaje de éxito en la sesión
            session()->flash('message', 'El array se envió correctamente.');

            Log::info("El array se envió", ["contenedor" => $this->contenedor]);
        } else {
            // Almacenar mensaje de error en la sesión
            session()->flash('warning', 'No se envió nada a la tabla.');

            Log::info("No se envió nada a la tabla", ["contenedor" => $this->contenedor]);
            $this->dispatch('sendingContenedorVentas', $this->contenedor);
        }
    }


    public function render()
    {
        return view('livewire.cuadro-de-pendientes-ventas-modal');
    }
}
