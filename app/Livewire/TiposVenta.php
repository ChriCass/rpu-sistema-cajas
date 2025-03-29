<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TipoVenta;
use Livewire\WithPagination;
use App\Traits\WithNotifications;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class TiposVenta extends Component
{
    use WithPagination;
    use WithNotifications;

    // Propiedades para la tabla y búsqueda
    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Propiedades para el formulario
    public $tipoVentaId = null;
    public $descripcion = '';
    public $estado = true;

    // Control de modales
    public $modalFormulario = false;
    public $modalConfirmacion = false;

    // Reglas de validación
    protected function rules()
    {
        return [
            'descripcion' => [
                'required',
                'string',
                'min:3',
                'max:100',
                Rule::unique('tipos_venta', 'descripcion')->ignore($this->tipoVentaId),
            ],
            'estado' => 'boolean',
        ];
    }

    // Mensajes de validación personalizados
    protected $messages = [
        'descripcion.required' => 'La descripción es obligatoria.',
        'descripcion.min' => 'La descripción debe tener al menos 3 caracteres.',
        'descripcion.max' => 'La descripción no debe exceder los 100 caracteres.',
        'descripcion.unique' => 'Este tipo de venta ya está registrado.',
    ];

    // Resetear la paginación cuando se actualiza la búsqueda
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Definir el método de renderizado
    public function render()
    {
        $query = TipoVenta::query();

        if ($this->search) {
            $query->where('descripcion', 'like', '%' . $this->search . '%');
        }

        $tiposVenta = $query->orderBy($this->sortField, $this->sortDirection)
                        ->paginate($this->perPage);

        return view('livewire.tipos-venta', [
            'tiposVenta' => $tiposVenta
        ]);
    }

    // Método para abrir el modal con formulario vacío (nuevo tipo de venta)
    public function crear()
    {
        $this->reset(['tipoVentaId', 'descripcion', 'estado']);
        $this->estado = true;
        $this->modalFormulario = true;
    }

    // Método para abrir el modal con formulario para editar
    public function editar($id)
    {
        $tipoVenta = TipoVenta::findOrFail($id);
        $this->tipoVentaId = $tipoVenta->id;
        $this->descripcion = $tipoVenta->descripcion;
        $this->estado = $tipoVenta->estado;
        $this->modalFormulario = true;
    }

    // Método para guardar (crear o actualizar)
    public function guardar()
    {
        $this->validate();

        try {
            if ($this->tipoVentaId) {
                // Actualizar
                $tipoVenta = TipoVenta::findOrFail($this->tipoVentaId);
                $tipoVenta->update([
                    'descripcion' => strtoupper($this->descripcion),
                    'estado' => $this->estado,
                ]);
                $mensaje = 'Tipo de venta actualizado correctamente.';
            } else {
                // Crear nuevo
                TipoVenta::create([
                    'descripcion' => strtoupper($this->descripcion),
                    'estado' => $this->estado,
                ]);
                $mensaje = 'Tipo de venta registrado correctamente.';
            }

            $this->modalFormulario = false;
            $this->reset(['tipoVentaId', 'descripcion', 'estado']);
            $this->notify('success', $mensaje, 8000);

        } catch (\Exception $e) {
            Log::error('Error al guardar tipo de venta', [
                'error' => $e->getMessage(),
                'tipoVentaId' => $this->tipoVentaId,
                'descripcion' => $this->descripcion
            ]);

            $this->notify('error', 'Error al guardar el tipo de venta. Por favor, inténtelo de nuevo.', 10000);
        }
    }

    // Método para confirmar eliminación
    public function confirmarEliminar($id)
    {
        $this->tipoVentaId = $id;
        $this->modalConfirmacion = true;
    }

    // Método para cancelar eliminación
    public function cancelarEliminar()
    {
        $this->tipoVentaId = null;
        $this->modalConfirmacion = false;
    }

    // Método para eliminar
    public function eliminar()
    {
        try {
            $tipoVenta = TipoVenta::findOrFail($this->tipoVentaId);
            $tipoVenta->delete();

            $this->modalConfirmacion = false;
            $this->tipoVentaId = null;

            $this->notify('success', 'Tipo de venta eliminado correctamente.', 8000);

        } catch (\Exception $e) {
            Log::error('Error al eliminar tipo de venta', [
                'error' => $e->getMessage(),
                'tipoVentaId' => $this->tipoVentaId
            ]);

            $this->notify('error', 'Error al eliminar el tipo de venta. Por favor, inténtelo de nuevo.', 10000);
        }
    }

    // Método para cambiar el estado activo/inactivo
    public function cambiarEstado($id)
    {
        try {
            $tipoVenta = TipoVenta::findOrFail($id);
            $tipoVenta->update([
                'estado' => !$tipoVenta->estado
            ]);

            $estado = $tipoVenta->estado ? 'activado' : 'desactivado';
            $this->notify('success', "Tipo de venta {$estado} correctamente.", 8000);

        } catch (\Exception $e) {
            Log::error('Error al cambiar estado del tipo de venta', [
                'error' => $e->getMessage(),
                'tipoVentaId' => $id
            ]);

            $this->notify('error', 'Error al cambiar el estado del tipo de venta.', 10000);
        }
    }

    // Método para ordenar
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
} 