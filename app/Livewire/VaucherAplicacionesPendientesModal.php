<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class VaucherAplicacionesPendientesModal extends Component
{
    public $openModal = false;
    public $fecha;
    public $moneda;
    public $aplicaciones = [];
    public $contenedor = [];  // Para acumular los detalles seleccionados
 
    public $filterColumn  = 'id_documentos';  // Columna por defecto
    public $searchValue;               // Valor de búsqueda
    
    public function updatedSearchValue()
    {
        $this->applyFilters();
    }
    
    public function updatedFilterColumn()
    {
        $this->applyFilters();
    }
    
    public function applyFilters()
    {
        // Obtener todas las aplicaciones
        $aplicaciones = collect($this->loadAplicaciones());
    
        // Limpiar el valor de búsqueda para evitar espacios en blanco innecesarios
        $searchValue = trim($this->searchValue);

        // Si el valor de búsqueda está vacío después de limpiar, mostramos todas las aplicaciones
        if (empty($searchValue)) {
            $this->aplicaciones = $aplicaciones->all();
        } else {
            // Filtrar según la columna seleccionada y el valor ingresado
            $this->aplicaciones = $aplicaciones->filter(function ($aplicacion) use ($searchValue) {
                $filterColumn = $this->filterColumn;

                // Verificar si la propiedad existe y no es null
                if (!isset($aplicacion->$filterColumn) || is_null($aplicacion->$filterColumn)) {
                    return false;
                }

                // Convertimos el valor de la columna a string y hacemos la búsqueda insensible a mayúsculas/minúsculas
                $columnValue = (string) $aplicacion->$filterColumn;

                return stripos($columnValue, $searchValue) !== false;
            })->values()->all();
        }
    }
    // Monta el componente con los parámetros necesarios
    public function mount($fecha, $moneda)
    {
        $this->fecha = $fecha;
        $this->moneda = $moneda;
       $this->aplicaciones =  $this->loadAplicaciones();
    }

    // Actualiza los resultados de búsqueda cuando cambian los términos
 

    // Cargar las aplicaciones según los filtros aplicados
    public function loadAplicaciones()
    {
        $query = "
            SELECT id_documentos, tdoc, id_entidades, RZ, Num, Mon, Descripcion, 
                IF(Mon = 'PEN', monto, montodo) AS monto 
            FROM (
                SELECT id_documentos, fechaEmi, tabla10.descripcion AS tdoc, id_entidades, entidades.descripcion AS RZ, 
                    Num, IF(CON3.Descripcion = 'DETRACCIONES POR COBRAR', 'PEN', id_t04tipmon) AS Mon, 
                    CON3.Descripcion, monto, montodo 
                FROM (
                    SELECT id_documentos, 
                        documentos.fechaEmi,  
                        documentos.id_t10tdoc, 
                        documentos.id_entidades, 
                        CONCAT(documentos.serie, '-', documentos.numero) AS Num, 
                        documentos.id_t04tipmon, 
                        cuentas.Descripcion, monto, montodo 
                    FROM (
                        SELECT id_documentos, id_cuentas, SUM(monto) AS monto, SUM(montodo) AS montodo 
                        FROM (
                            SELECT id_documentos, id_cuentas, 
                                IF(id_dh = '1', monto, monto * -1) AS monto, 
                                IF(id_dh = '1', IF(montodo IS NULL, 0, montodo), IF(montodo IS NULL, 0, montodo) * -1) AS montodo 
                            FROM movimientosdecaja 
                            LEFT JOIN (
                                SELECT cuentas.id, tipodecuenta.id AS idTcuenta 
                                FROM cuentas 
                                LEFT JOIN tipodecuenta ON cuentas.id_tCuenta = tipodecuenta.id
                            ) INN1 ON movimientosdecaja.id_cuentas = INN1.id 
                            WHERE INN1.idTcuenta <> '1'
                        ) CON1 
                        GROUP BY id_documentos, id_cuentas 
                        HAVING SUM(monto) <> 0
                    ) CON2 
                    LEFT JOIN documentos ON CON2.id_documentos = documentos.id 
                    LEFT JOIN cuentas ON CON2.id_cuentas = cuentas.id
                ) CON3 
                LEFT JOIN entidades ON CON3.id_entidades = entidades.id 
                LEFT JOIN tabla10_tipodecomprobantedepagoodocumento AS tabla10 
                    ON CON3.id_t10tdoc = tabla10.id 
                WHERE monto > 0

                UNION ALL

                SELECT id_documentos, fechaEmi,tabla10.descripcion AS tdoc, id_entidades, entidades.descripcion AS RZ, 
                    Num, IF(CON3.Descripcion = 'DETRACCIONES POR PAGAR', 'PEN', id_t04tipmon) AS Mon, 
                    CON3.Descripcion, monto, montodo 
                FROM (
                    SELECT id_documentos, 
                        documentos.fechaEmi,
                        documentos.id_t10tdoc, 
                        documentos.id_entidades, 
                        CONCAT(documentos.serie, '-', documentos.numero) AS Num, 
                        documentos.id_t04tipmon, 
                        cuentas.Descripcion, monto, montodo 
                    FROM (
                        SELECT id_documentos, id_cuentas, SUM(monto) AS monto, SUM(montodo) AS montodo 
                        FROM (
                            SELECT id_documentos, id_cuentas, 
                                IF(id_dh = '2', monto, monto * -1) AS monto, 
                                IF(id_dh = '2', IF(montodo IS NULL, 0, montodo), IF(montodo IS NULL, 0, montodo) * -1) AS montodo 
                            FROM movimientosdecaja 
                            LEFT JOIN (
                                SELECT cuentas.id, tipodecuenta.id AS idTcuenta 
                                FROM cuentas 
                                LEFT JOIN tipodecuenta ON cuentas.id_tCuenta = tipodecuenta.id
                            ) INN1 ON movimientosdecaja.id_cuentas = INN1.id 
                            WHERE INN1.idTcuenta <> '1'
                        ) CON1 
                        GROUP BY id_documentos, id_cuentas 
                        HAVING SUM(monto) <> 0
                    ) CON2 
                    LEFT JOIN documentos ON CON2.id_documentos = documentos.id 
                    LEFT JOIN cuentas ON CON2.id_cuentas = cuentas.id
                ) CON3 
                LEFT JOIN entidades ON CON3.id_entidades = entidades.id 
                LEFT JOIN tabla10_tipodecomprobantedepagoodocumento AS tabla10 
                    ON CON3.id_t10tdoc = tabla10.id 
                WHERE monto > 0
            ) CON4 
            WHERE fechaEmi <= ? 
            AND Mon = ?
            AND ROUND(monto, 2) <> 0 
            ORDER BY CAST(id_documentos AS UNSIGNED) ASC;
        ";
    
        return DB::select($query, [
            $this->fecha,
            $this->moneda
        ]);
    }
    

    // Lógica para alternar la selección de aplicaciones (toggle)
    public function toggleSelection($idDocumento, $num, $descripcion)
    {
        Log::info('Toggle selection iniciado', [
            'idDocumento' => $idDocumento,
            'Num' => $num,
            'Descripcion' => $descripcion
        ]);
    
        // Log para mostrar el contenido de aplicaciones antes de la selección
        Log::info('Estado de aplicaciones antes de la selección', ['aplicaciones' => $this->aplicaciones]);
    
        // Asignar valor por defecto si $descripcion está vacío
        $descripcion = $descripcion ?: 'CUENTAS POR COBRAR';
    
        // Buscar el documento en las aplicaciones
        $pendiente = collect($this->aplicaciones)->first(function ($item) use ($idDocumento, $num, $descripcion) {
            return $item->id_documentos == $idDocumento && 
                   $item->Num == $num && 
                   $item->Descripcion == $descripcion;
        });
    
        // Log para mostrar el dato seleccionado antes de la operación
        Log::info('Dato seleccionado antes de la operación', ['pendiente' => $pendiente]);
    
        if ($pendiente) {
            // Verificar si el documento ya está en el contenedor
            if (collect($this->contenedor)->contains(function ($item) use ($pendiente) {
                return $item->id_documentos == $pendiente->id_documentos;
            })) {
                // Si está en el contenedor, lo eliminamos
                $this->contenedor = array_filter($this->contenedor, function ($item) use ($pendiente) {
                    return $item->id_documentos != $pendiente->id_documentos;
                });
                Log::info('Documento eliminado del contenedor', [
                    'idDocumento' => $idDocumento,
                    'Num' => $num,
                    'Descripcion' => $descripcion
                ]);
            } else {
                // Si no está en el contenedor, lo añadimos
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
    
        // Log para mostrar el estado del contenedor después de la operación
        Log::info('Estado del contenedor después de la operación', ['contenedor' => $this->contenedor]);
    
        // Log para verificar si el contenedor está vacío o tiene elementos
        if (empty($this->contenedor)) {
            Log::info('El contenedor está vacío.');
        } else {
            Log::info('El contenedor tiene elementos.', ['contenedor' => $this->contenedor]);
        }
    }
    
    

    // Función para enviar los datos seleccionados
    public function sendingData()
    {
        Log::info('Enviando datos', [
            'contenedor' => $this->contenedor,
        ]);

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
        return view('livewire.vaucher-aplicaciones-pendientes-modal');
    }
}
