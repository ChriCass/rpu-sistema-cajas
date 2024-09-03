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
                id_documentos, 
                tdoc, 
                id_entidades, 
                RZ, 
                Num, 
                Mon, 
                Descripcion, 
                monto 
            FROM (
                SELECT 
                    id_documentos, 
                    fechaEmi, 
                    tabla10_tipodecomprobantedepagoodocumento.descripcion AS tdoc, 
                    id_entidades, 
                    RZ, 
                    Num, 
                    Mon, 
                    CON4.Descripcion, 
                    IF(Mon = 'PEN', monto, montodo) AS monto 
                FROM (
                    SELECT 
                        id_documentos, 
                        fechaEmi, 
                        id_t10tdoc, 
                        id_entidades, 
                        entidades.descripcion AS RZ, 
                        Num, 
                        IF(CON3.Descripcion = 'DETRACCIONES POR COBRAR', 'PEN', id_t04tipmon) AS Mon, 
                        CON3.Descripcion, 
                        monto, 
                        montodo 
                    FROM (
                        SELECT 
                            id_documentos, 
                            fechaEmi, 
                            id_t10tdoc, 
                            id_entidades, 
                            CONCAT(serie, '-', numero) AS Num, 
                            id_t04tipmon, 
                            cuentas.Descripcion, 
                            monto, 
                            montodo 
                        FROM (
                            SELECT 
                                id_documentos, 
                                id_cuentas, 
                                SUM(monto) AS monto, 
                                SUM(montodo) AS montodo 
                            FROM (
                                SELECT 
                                    id_documentos, 
                                    id_cuentas, 
                                    IF(id_dh = '1', monto, monto * -1) AS monto, 
                                    IF(id_dh = '1', IF(montodo IS NULL, 0, montodo), IF(montodo IS NULL, 0, montodo) * -1) AS montodo 
                                FROM 
                                    movimientosdecaja 
                                WHERE 
                                    id_cuentas IN ('1', '2') 
                                    AND CONCAT(id_libro, mov) <> ?
                            ) CON1 
                            GROUP BY 
                                id_documentos, 
                                id_cuentas 
                            HAVING 
                                SUM(monto) <> 0
                        ) CON2 
                        LEFT JOIN ventas_documentos ON CON2.id_documentos = ventas_documentos.id 
                        LEFT JOIN cuentas ON CON2.id_cuentas = cuentas.id
                    ) CON3 
                    LEFT JOIN entidades ON CON3.id_entidades = entidades.id
                ) CON4 
                LEFT JOIN tabla10_tipodecomprobantedepagoodocumento ON CON4.id_t10tdoc = tabla10_tipodecomprobantedepagoodocumento.id

                UNION ALL

                SELECT 
                    id_documentos, 
                    fechaEmi, 
                    tabla10_tipodecomprobantedepagoodocumento.descripcion, 
                    id_entidades, 
                    RZ, 
                    Num, 
                    Mon, 
                    CON4.Descripcion, 
                    IF(Mon = 'PEN', monto, montodo) AS monto 
                FROM (
                    SELECT 
                        id_documentos, 
                        fechaEmi, 
                        id_t10tdoc, 
                        id_entidades, 
                        entidades.descripcion AS RZ, 
                        Num, 
                        IF(CON3.Descripcion = 'DETRACCIONES POR PAGAR', 'PEN', id_t04tipmon) AS Mon, 
                        CON3.Descripcion, 
                        monto, 
                        montodo 
                    FROM (
                        SELECT 
                            id_documentos, 
                            fechaEmi, 
                            id_t10tdoc, 
                            id_entidades, 
                            CONCAT(serie, '-', numero) AS Num, 
                            id_t04tipmon, 
                            cuentas.Descripcion, 
                            monto, 
                            montodo 
                        FROM (
                            SELECT 
                                id_documentos, 
                                id_cuentas, 
                                SUM(monto) AS monto, 
                                SUM(montodo) AS montodo 
                            FROM (
                                SELECT 
                                    id_documentos, 
                                    id_cuentas, 
                                    IF(id_dh = '2', monto, monto * -1) AS monto, 
                                    IF(id_dh = '2', IF(montodo IS NULL, 0, montodo), IF(montodo IS NULL, 0, montodo) * -1) AS montodo 
                                FROM 
                                    movimientosdecaja 
                                WHERE 
                                    id_cuentas IN ('3', '4') 
                                    AND CONCAT(id_libro, mov) <> ?
                            ) CON1 
                            GROUP BY 
                                id_documentos, 
                                id_cuentas 
                            HAVING 
                                ROUND(SUM(monto), 2) <> 0
                        ) CON2 
                        LEFT JOIN compras_documentos ON CON2.id_documentos = compras_documentos.id 
                        LEFT JOIN cuentas ON CON2.id_cuentas = cuentas.id
                    ) CON3 
                    LEFT JOIN entidades ON CON3.id_entidades = entidades.id
                ) CON4 
                LEFT JOIN tabla10_tipodecomprobantedepagoodocumento ON CON4.id_t10tdoc = tabla10_tipodecomprobantedepagoodocumento.id
            ) CON5
            WHERE 
                fechaEmi <= ? 
                AND Mon = ? 
                AND monto <> 0
                AND {$this->filterColumn} LIKE ?
            ORDER BY 
                CAST(id_documentos AS UNSIGNED) ASC
        ";

        $this->aplicaciones = DB::select($query, [
            $this->aplicacionesId,
            $this->aplicacionesId,
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
