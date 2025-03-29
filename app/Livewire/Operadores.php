<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Operador;
use Livewire\WithPagination;
use App\Traits\WithNotifications;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class Operadores extends Component
{
    use WithPagination;
    use WithNotifications;

    // Propiedades para la tabla y búsqueda
    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Propiedades para el formulario
    public $operadorId = null;
    public $nombre = '';
    public $estado = true;

    // Control de modales
    public $modalFormulario = false;
    public $modalConfirmacion = false;

    // Reglas de validación
    protected function rules()
    {
        return [
            'nombre' => [
                'required',
                'string',
                'min:3',
                'max:100',
                Rule::unique('operadores', 'nombre')->ignore($this->operadorId),
            ],
            'estado' => 'boolean',
        ];
    }

    // Mensajes de validación personalizados
    protected $messages = [
        'nombre.required' => 'El nombre del operador es obligatorio.',
        'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
        'nombre.max' => 'El nombre no debe exceder los 100 caracteres.',
        'nombre.unique' => 'Este operador ya está registrado.',
    ];

    // Resetear la paginación cuando se actualiza la búsqueda
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Definir el método de renderizado
    public function render()
    {
        $query = Operador::query();

        if ($this->search) {
            $query->where('nombre', 'like', '%' . $this->search . '%');
        }

        $operadores = $query->orderBy($this->sortField, $this->sortDirection)
                        ->paginate($this->perPage);

        return view('livewire.operadores', [
            'operadores' => $operadores
        ]);
    }

    // Método para abrir el modal con formulario vacío (nuevo operador)
    public function crear()
    {
        $this->reset(['operadorId', 'nombre', 'estado']);
        $this->estado = true;
        $this->modalFormulario = true;
    }

    // Método para abrir el modal con formulario para editar
    public function editar($id)
    {
        $operador = Operador::findOrFail($id);
        $this->operadorId = $operador->id;
        $this->nombre = $operador->nombre;
        $this->estado = $operador->estado;
        $this->modalFormulario = true;
    }

    // Método para guardar (crear o actualizar)
    public function guardar()
    {
        $this->validate();

        try {
            if ($this->operadorId) {
                // Actualizar
                $operador = Operador::findOrFail($this->operadorId);
                $operador->update([
                    'nombre' => strtoupper($this->nombre),
                    'estado' => $this->estado,
                ]);
                $mensaje = 'Operador actualizado correctamente.';
            } else {
                // Crear nuevo
                Operador::create([
                    'nombre' => strtoupper($this->nombre),
                    'estado' => $this->estado,
                ]);
                $mensaje = 'Operador registrado correctamente.';
            }

            $this->modalFormulario = false;
            $this->reset(['operadorId', 'nombre', 'estado']);
            $this->notify('success', $mensaje, 8000);

        } catch (\Exception $e) {
            Log::error('Error al guardar operador', [
                'error' => $e->getMessage(),
                'operadorId' => $this->operadorId,
                'nombre' => $this->nombre
            ]);

            $this->notify('error', 'Error al guardar el operador. Por favor, inténtelo de nuevo.', 10000);
        }
    }

    // Método para confirmar eliminación
    public function confirmarEliminar($id)
    {
        $this->operadorId = $id;
        $this->modalConfirmacion = true;
    }

    // Método para cancelar eliminación
    public function cancelarEliminar()
    {
        $this->operadorId = null;
        $this->modalConfirmacion = false;
    }

    // Método para eliminar
    public function eliminar()
    {
        try {
            $operador = Operador::findOrFail($this->operadorId);
            
            // Verificar si el operador tiene partes diarios asociados
            if ($operador->partesDiarios()->count() > 0) {
                throw new \Exception('No se puede eliminar el operador porque tiene partes diarios asociados.');
            }
            
            $operador->delete();

            $this->modalConfirmacion = false;
            $this->operadorId = null;

            $this->notify('success', 'Operador eliminado correctamente.', 8000);

        } catch (\Exception $e) {
            Log::error('Error al eliminar operador', [
                'error' => $e->getMessage(),
                'operadorId' => $this->operadorId
            ]);

            $this->notify('error', 'Error al eliminar el operador: ' . $e->getMessage(), 10000);
            $this->modalConfirmacion = false;
        }
    }

    // Método para cambiar el estado activo/inactivo
    public function cambiarEstado($id)
    {
        try {
            $operador = Operador::findOrFail($id);
            $operador->update([
                'estado' => !$operador->estado
            ]);

            $estado = $operador->estado ? 'activado' : 'desactivado';
            $this->notify('success', "Operador {$estado} correctamente.", 8000);

        } catch (\Exception $e) {
            Log::error('Error al cambiar estado del operador', [
                'error' => $e->getMessage(),
                'operadorId' => $id
            ]);

            $this->notify('error', 'Error al cambiar el estado del operador.', 10000);
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