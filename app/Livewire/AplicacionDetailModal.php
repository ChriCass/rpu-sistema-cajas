<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use App\Models\Apertura;
use Illuminate\Support\Facades\DB;
use DateTime;

class AplicacionDetailModal extends Component
{
    public $openModal = false;
    public $aplicacionesId;
    public $fecha;
    public $moneda;
    public $aplicaciones = [];
    public $contenedor = [];
    public $detalles;


    public $filterColumn = 'id_entidades';  // Columna por defecto para el filtro
    public $searchTerm = '';                // Término de búsqueda

    public function mount($fecha, $aplicacionesId, $moneda, $detalles)
    {
        $this->moneda = $moneda;
        $this->aplicacionesId = $aplicacionesId;
        $this->fecha = $fecha;
        $this->detalles = $detalles;
        $this->loadAplicaciones();
    }

    public function updatedSearchTerm()
    {
        $this->loadAplicaciones();
    }

    public function updatedFilterColumn()
    {
        $this->loadAplicaciones();
    }

    private function loadAplicaciones()
    {
        $query = "
            SELECT 
                documentos.id AS id_documentos, 
                d_tipomovimientos.descripcionl AS tdoc, 
                documentos.id_entidades, 
                entidades.descripcion AS RZ, 
                CONCAT(documentos.serie, '-', documentos.numero) AS Num, 
                documentos.id_t04tipmon AS Mon, 
                cuentas.Descripcion, 
                IF(documentos.id_t04tipmon = 'PEN', d_detalledocumentos.total, d_detalledocumentos.total) AS monto
            FROM 
                documentos
            LEFT JOIN 
                d_tipomovimientos ON documentos.id_tipmov = d_tipomovimientos.id
            LEFT JOIN 
                entidades ON documentos.id_entidades = entidades.id
            LEFT JOIN 
                d_detalledocumentos ON documentos.id = d_detalledocumentos.id_referencia
            LEFT JOIN 
                cuentas ON d_detalledocumentos.id_producto = cuentas.id
            WHERE 
                documentos.fechaEmi <= ?
                AND documentos.id_t04tipmon = ?
                AND d_detalledocumentos.total <> 0
                AND {$this->filterColumn} LIKE ?
            ORDER BY 
                CAST(documentos.id AS UNSIGNED) ASC
        ";
    
        // Actualización de los parámetros de consulta
        $this->aplicaciones = DB::select($query, [
            $this->fecha,
            $this->moneda,
            '%' . $this->searchTerm . '%',
        ]);
    }
    

    public function toggleSelection($idDocumento, $num, $descripcion)
{
    Log::info('Toggle selection iniciado', [
        'idDocumento' => $idDocumento,
        'Num' => $num,
        'Descripcion' => $descripcion
    ]);

    $this->contenedor = $this->contenedor ?? [];
    
    // Buscar el documento específico en la lista de aplicaciones
    $pendiente = collect($this->aplicaciones)->first(function ($item) use ($idDocumento, $num, $descripcion) {
        return $item->id_documentos === $idDocumento && $item->Num === $num && $item->Descripcion === $descripcion;
    });

    Log::info('Documento encontrado en aplicaciones', ['pendiente' => $pendiente]);

    if ($pendiente) {
        // Si el ítem ya está en el contenedor, lo quitamos
        if (collect($this->contenedor)->contains(function ($item) use ($idDocumento, $num, $descripcion) {
            return $item->id_documentos === $idDocumento && $item->Num === $num && $item->Descripcion === $descripcion;
        })) {
            $this->contenedor = array_filter($this->contenedor, function ($item) use ($idDocumento, $num, $descripcion) {
                return !($item->id_documentos === $idDocumento && $item->Num === $num && $item->Descripcion === $descripcion);
            });
            Log::info('Documento eliminado del contenedor', [
                'idDocumento' => $idDocumento,
                'Num' => $num,
                'Descripcion' => $descripcion
            ]);
        } else {
            // De lo contrario, lo añadimos al contenedor
            $this->contenedor[] = $pendiente;
            Log::info('Documento añadido al contenedor', [
                'idDocumento' => $pendiente->id_documentos,
                'Num' => $pendiente->Num,
                'Descripcion' => $pendiente->Descripcion
            ]);
        }
    } else {
        Log::warning('Documento no encontrado en aplicaciones', [
            'idDocumento' => $idDocumento,
            'Num' => $num,
            'Descripcion' => $descripcion
        ]);
    }
}

public function sendingData()
{
    Log::info('Enviando datos', [
        'contenedor' => $this->contenedor,
        'detalles' => $this->detalles
    ]);

    // Verificación de duplicados entre aplicaciones y detalles
    foreach ($this->contenedor as $aplicacion) {
        foreach ($this->detalles as $detalle) {
            // Acceder a las propiedades de $aplicacion, que es un stdClass, con "->"
            $aplicacionIdDocumentos = $aplicacion->id_documentos ?? null;
            $aplicacionNum = $aplicacion->Num ?? null;
            $aplicacionDescripcion = $aplicacion->Descripcion ?? null;

            // Verificar si $detalle es un array o un objeto
            $detalleIdDocumentos = is_array($detalle) ? $detalle['id'] : ($detalle->id ?? null);
            $detalleNum = is_array($detalle) ? $detalle['num'] : ($detalle->num ?? null);
            $detalleDescripcion = is_array($detalle) ? $detalle['cuenta'] : ($detalle->cuenta ?? null);

            Log::info('Verificando duplicado', [
                'aplicacion' => [
                    'id_documentos' => $aplicacionIdDocumentos,
                    'Num' => $aplicacionNum,
                    'Descripcion' => $aplicacionDescripcion,
                ],
                'detalle' => [
                    'id' => $detalleIdDocumentos,
                    'num' => $detalleNum,
                    'cuenta' => $detalleDescripcion
                ]
            ]);

            if (
                $aplicacionIdDocumentos === $detalleIdDocumentos &&
                $aplicacionNum === $detalleNum &&
                $aplicacionDescripcion === $detalleDescripcion
            ) {
                // Si hay un dato repetido, manda un flash error y detiene la función
                Log::error('Dato repetido encontrado', [
                    'aplicacion' => $aplicacion,
                    'detalle' => $detalle
                ]);
                session()->flash('error', 'Hay un dato repetido en la tabla. No se enviaron los datos.');
                return;
            }
        }
    }

    Log::info('No se encontraron duplicados, enviando contenedor');

    // Si no hay duplicados, se despachan los datos y se manda un flash de éxito
    $this->dispatch('sendingContenedorAplicaciones', $this->contenedor);

    if (!empty($this->contenedor)) {
        session()->flash('message', 'Datos enviados correctamente.');
        Log::info('Datos enviados correctamente');
    } else {
        session()->flash('warning', 'No se envió nada a la tabla.');
        Log::warning('No se envió nada a la tabla');
    }
}



    

    public function render()
    {
        return view('livewire.aplicacion-detail-modal');
    }
}
