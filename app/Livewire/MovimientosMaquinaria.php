<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ParteDiario;
use Livewire\WithPagination;
use App\Traits\WithNotifications;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class MovimientosMaquinaria extends Component
{
    use WithPagination;
    use WithNotifications;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'fecha_inicio';
    public $sortDirection = 'desc';
    public $filters = [
        'fechaDesde' => '',
        'fechaHasta' => '',
        'operador' => '',
        'unidad' => '',
        'estadoPago' => '',
        'cliente' => ''
    ];

    public $idParteEliminar;
    public $confirmarEliminacion = false;

    // Nuevas propiedades para el modal de documento
    public $documentoModal = false;
    public $documentoActual = null;
    public $parteActual = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'fecha_inicio'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount()
    {
        // Inicializar filtros de fecha con el mes actual
        $now = Carbon::now();
        $this->filters['fechaDesde'] = $now->startOfMonth()->format('Y-m-d');
        $this->filters['fechaHasta'] = $now->endOfMonth()->format('Y-m-d');
        
        // Log básico de inicialización
        Log::info('MovimientosMaquinaria inicializado', [
            'usuario' => auth()->id() ?? 'sin autenticar',
            'fecha_desde' => $this->filters['fechaDesde'],
            'fecha_hasta' => $this->filters['fechaHasta']
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function resetFilters()
    {
        $now = Carbon::now();
        $this->filters = [
            'fechaDesde' => $now->startOfMonth()->format('Y-m-d'),
            'fechaHasta' => $now->endOfMonth()->format('Y-m-d'),
            'operador' => '',
            'unidad' => '',
            'estadoPago' => '',
            'cliente' => ''
        ];
        $this->search = '';
    }

    public function render()
    {
        $query = ParteDiario::query()
            ->with(['operador', 'unidad', 'entidad', 'tipoVenta'])
            ->when($this->search !== '', function ($query) {
                return $query->where(function ($q) {
                    $q->where('numero_parte', 'like', '%' . $this->search . '%')
                      ->orWhereHas('entidad', function ($q) {
                          $q->where('descripcion', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('operador', function ($q) {
                          $q->where('nombre', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('unidad', function ($q) {
                          $q->where('descripcion', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->filters['fechaDesde'] !== '', function ($query) {
                return $query->where('fecha_inicio', '>=', $this->filters['fechaDesde']);
            })
            ->when($this->filters['fechaHasta'] !== '', function ($query) {
                return $query->where('fecha_inicio', '<=', $this->filters['fechaHasta']);
            })
            ->when($this->filters['operador'] !== '', function ($query) {
                return $query->where('operador_id', $this->filters['operador']);
            })
            ->when($this->filters['unidad'] !== '', function ($query) {
                return $query->where('unidad_id', $this->filters['unidad']);
            })
            ->when($this->filters['estadoPago'] !== '', function ($query) {
                return $query->where('estado_pago', $this->filters['estadoPago']);
            })
            ->when($this->filters['cliente'] !== '', function ($query) {
                return $query->where('entidad_id', $this->filters['cliente']);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $partesDiarios = $query->paginate($this->perPage);

        return view('livewire.movimientos-maquinaria', [
            'partesDiarios' => $partesDiarios
        ]);
    }

    public function verDetalle($id)
    {
        return redirect()->route('parte-diario', ['origen' => 'edicion', 'id' => $id]);
    }

    public function confirmarEliminar($id)
    {
        $this->idParteEliminar = $id;
        $parte = ParteDiario::find($id);
        
        if (!$parte) {
            $this->documentoModal = false; // Cerrar el modal de documento primero
            $this->dispatch('showNotification', [
                'message' => 'No se encontró el parte diario',
                'type' => 'error'
            ])->self();
            return;
        }
        
        // Primero, buscar el documento asociado
        $documento = $this->encontrarDocumentoAsociado($parte);
        
        if (!$documento) {
            Log::warning('No se encontró documento asociado al parte diario', [
                'parte_id' => $parte->id,
                'numero_parte' => $parte->numero_parte,
                'entidad_id' => $parte->entidad_id
            ]);
        } else {
            // Verificar si tiene movimientos en libro de caja 3
            $tieneMovimientosEnCaja3 = $this->verificarMovimientosEnCaja3($documento->id);
            
            if ($tieneMovimientosEnCaja3) {
                $this->documentoModal = false; // Cerrar el modal de documento primero
                session()->flash('mensaje', 'No se puede eliminar el parte diario porque tiene movimientos asociados en el Libro de Caja 3. Por favor, contacte al administrador.');
                session()->flash('tipo', 'error');
                return;
            }
        }
        
        $this->confirmarEliminacion = true;
    }
    
    /**
     * Encuentra el documento asociado a un parte diario
     * 
     * @param ParteDiario $parte El parte diario
     * @return \App\Models\Documento|null El documento asociado o null si no se encuentra
     */
    private function encontrarDocumentoAsociado($parte)
    {
        return \App\Models\Documento::where('id_t10tdoc', '82') // Tipo de documento Parte Diario
            ->where('serie', '0000')
            ->where('numero', $parte->numero_parte)
            ->where('id_entidades', $parte->entidad_id)
            ->first();
    }
    
    /**
     * Verifica si un documento tiene movimientos en el libro de caja 3
     * 
     * @param int $documentoId ID del documento a verificar
     * @return bool True si tiene movimientos, False si no
     */
    private function verificarMovimientosEnCaja3($documentoId)
    {
        // Buscar movimientos en la tabla movimientos_de_caja que estén en el libro 3
        $movimientosEnCaja3 = \App\Models\MovimientoDeCaja::where('id_documentos', $documentoId)
            ->where('id_libro', 3) // Filtrar específicamente por libro de caja 3
            ->count();
        
        // Registrar información para debugging
        Log::info('Verificando movimientos en libro de caja 3', [
            'documento_id' => $documentoId,
            'tiene_movimientos' => $movimientosEnCaja3 > 0,
            'cantidad_movimientos' => $movimientosEnCaja3
        ]);
        
        return $movimientosEnCaja3 > 0;
    }

    public function cancelarEliminar()
    {
        $this->idParteEliminar = null;
        $this->confirmarEliminacion = false;
    }

    public function eliminar()
    {
        try {
            $parte = ParteDiario::find($this->idParteEliminar);
            
            if (!$parte) {
                session()->flash('mensaje', 'No se encontró el parte diario.');
                session()->flash('tipo', 'error');
                $this->confirmarEliminacion = false;
                return;
            }
            
            // Guardar algunos datos para el mensaje de confirmación
            $numeroParte = $parte->numero_parte;
            
            // Buscar el documento asociado
            $documento = $this->encontrarDocumentoAsociado($parte);
            
            // Proceso de eliminación en transacción
            DB::beginTransaction();
            
            try {
                if ($documento) {
                    // Eliminar detalles del documento
                    \App\Models\DDetalleDocumento::where('id_referencia', $documento->id)->delete();
                    
                    // Eliminar movimientos de caja (excepto libro 3)
                    \App\Models\MovimientoDeCaja::where('id_documentos', $documento->id)
                        ->where('id_libro', '!=', 3)
                        ->delete();
                    
                    // Eliminar el documento
                    $documento->delete();
                }
                
                // Eliminar el parte diario
                $parte->delete();
                
                DB::commit();
                
                // Mensaje de éxito
                session()->flash('mensaje', "Parte diario #{$numeroParte} y su documento asociado eliminados correctamente.");
                session()->flash('tipo', 'success');
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
            
            // Cerrar el modal de confirmación
            $this->confirmarEliminacion = false;
            
            // Refrescar la página para actualizar la tabla
            return redirect()->route('movimientos-maquinaria');
            
        } catch (\Exception $e) {
            Log::error('Error al eliminar parte diario y/o documento asociado', [
                'id' => $this->idParteEliminar,
                'error' => $e->getMessage()
            ]);
            
            session()->flash('mensaje', 'Error al eliminar el parte diario. Por favor, inténtelo de nuevo.');
            session()->flash('tipo', 'error');
            $this->confirmarEliminacion = false;
        }
    }

    public function verDocumento($parteId)
    {
        try {
            // Obtener el parte diario
            $parte = ParteDiario::find($parteId);
            
            if (!$parte) {
                $this->dispatch('showNotification', [
                    'message' => 'No se encontró el parte diario',
                    'type' => 'error'
                ])->self();
                return;
            }
            
            // Buscar documento asociado
            $documento = \App\Models\Documento::where('id_t10tdoc', '82') // Tipo de documento Parte Diario
                ->where('serie', '0000')
                ->where('numero', $parte->numero_parte)
                ->where('id_entidades', $parte->entidad_id)
                ->first();
                
            if (!$documento) {
                $this->dispatch('showNotification', [
                    'message' => 'No se encontró un documento asociado a este parte diario',
                    'type' => 'warning'
                ])->self();
                return;
            }
            
            // Obtener detalles del documento
            $detalles = \App\Models\DDetalleDocumento::where('id_referencia', $documento->id)->get();
            
            // Guardar información para el modal
            $this->documentoActual = $documento;
            $this->parteActual = $parte;
            $this->documentoActual->detalles = $detalles;
            
            // Abrir el modal
            $this->documentoModal = true;
            
        } catch (\Exception $e) {
            Log::error('Error al obtener documento', [
                'parte_id' => $parteId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->dispatch('showNotification', [
                'message' => 'Error al obtener el documento: ' . $e->getMessage(),
                'type' => 'error'
            ])->self();
        }
    }

    public function cerrarModalDocumento()
    {
        $this->documentoModal = false;
        $this->documentoActual = null;
        $this->parteActual = null;
    }

    public function imprimirDocumento()
    {
        // Esta función utiliza JavaScript para imprimir el contenido del modal
        $this->dispatch('imprimir-documento');
    }
}
