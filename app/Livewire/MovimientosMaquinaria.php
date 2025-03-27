<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ParteDiario;
use Livewire\WithPagination;
use App\Traits\WithNotifications;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

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
            $this->dispatch('showNotification', [
                'message' => 'No se encontró el parte diario',
                'type' => 'error'
            ])->self();
            return;
        }
        
        // Si es un parte con pago parcial o pagado, no se puede eliminar
        if ($parte->estado_pago == '1' || $parte->estado_pago == '2') {
            session()->flash('mensaje', 'No se puede eliminar un parte diario que ya ha sido pagado. Por favor, contacte al administrador.');
            session()->flash('tipo', 'error');
            return;
        }
        
        $this->confirmarEliminacion = true;
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
            
            // Registrar información sobre el parte antes de eliminarlo
            Log::info('Eliminando parte diario', [
                'id' => $parte->id,
                'numero_parte' => $parte->numero_parte,
                'cliente' => $parte->entidad->descripcion ?? 'N/A',
                'importe' => $parte->importe_cobrar,
                'estado_pago' => $parte->estado_pago,
                'usuario' => auth()->user()->id
            ]);
            
            $numeroParte = $parte->numero_parte;
            $entidadId = $parte->entidad_id;
            
            // Primero, buscar el documento asociado
            $documento = \App\Models\Documento::where('id_t10tdoc', '82') // Tipo de documento Parte Diario
                ->where('serie', '0000')
                ->where('numero', $numeroParte)
                ->where('id_entidades', $entidadId)
                ->first();
                
            if ($documento) {
                Log::info('Documento asociado encontrado, procediendo a eliminar', [
                    'documento_id' => $documento->id,
                    'serie' => $documento->serie,
                    'numero' => $documento->numero,
                    'entidad_id' => $documento->id_entidades
                ]);
                
                // Primero eliminar los detalles del documento
                $detalles = \App\Models\DDetalleDocumento::where('id_referencia', $documento->id)->get();
                
                if ($detalles->count() > 0) {
                    Log::info('Eliminando ' . $detalles->count() . ' detalles de documento', [
                        'documento_id' => $documento->id,
                        'detalles_ids' => $detalles->pluck('id')->toArray()
                    ]);
                    
                    // Eliminar todos los detalles
                    \App\Models\DDetalleDocumento::where('id_referencia', $documento->id)->delete();
                    
                    Log::info('Detalles del documento eliminados correctamente');
                } else {
                    Log::info('No se encontraron detalles asociados al documento');
                }
                
                // También verificar y eliminar cualquier movimiento de caja asociado
                $movimientos = \App\Models\MovimientoDeCaja::where('id_documentos', $documento->id)->get();
                
                if ($movimientos->count() > 0) {
                    Log::info('Eliminando ' . $movimientos->count() . ' movimientos de caja asociados', [
                        'documento_id' => $documento->id,
                        'movimientos_ids' => $movimientos->pluck('id')->toArray()
                    ]);
                    
                    // Eliminar movimientos de caja asociados
                    \App\Models\MovimientoDeCaja::where('id_documentos', $documento->id)->delete();
                    
                    Log::info('Movimientos de caja eliminados correctamente');
                }
                
                // Finalmente eliminar el documento
                $documento->delete();
                
                Log::info('Documento eliminado correctamente');
            } else {
                Log::warning('No se encontró documento asociado al parte diario', [
                    'parte_id' => $parte->id,
                    'numero_parte' => $numeroParte,
                    'entidad_id' => $entidadId
                ]);
            }
            
            // Eliminar el parte
            $parte->delete();
            
            session()->flash('mensaje', "Parte diario #{$numeroParte} y su documento asociado eliminados correctamente.");
            session()->flash('tipo', 'success');
            
            $this->confirmarEliminacion = false;
            
        } catch (\Exception $e) {
            Log::error('Error al eliminar parte diario y/o documento asociado', [
                'id' => $this->idParteEliminar,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('mensaje', 'Error al eliminar el parte diario. Por favor, inténtelo de nuevo.');
            session()->flash('tipo', 'error');
        }
    }
}
