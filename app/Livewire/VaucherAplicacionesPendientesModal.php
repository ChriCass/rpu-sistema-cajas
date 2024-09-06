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
    public $filterColumn = 'id_entidades';  // Columna por defecto para el filtro
    public $searchTerm = '';  // Término de búsqueda

    // Monta el componente con los parámetros necesarios
    public function mount($fecha, $moneda)
    {
        $this->fecha = $fecha;
        $this->moneda = $moneda;
        $this->loadAplicaciones();
    }

    // Actualiza los resultados de búsqueda cuando cambian los términos
    public function updatedSearchTerm()
    {
        $this->loadAplicaciones();
    }

    // Actualiza los resultados de búsqueda cuando cambia la columna de filtrado
    public function updatedFilterColumn()
    {
        $this->loadAplicaciones();
    }

    // Cargar las aplicaciones según los filtros aplicados
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
                IFNULL(cuentas.Descripcion, 'CUENTAS POR COBRAR') AS Descripcion, 
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
    
        $this->aplicaciones = DB::select($query, [
            $this->fecha,
            $this->moneda,
            '%' . $this->searchTerm . '%',
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
