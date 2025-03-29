<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Unidad;
use Livewire\WithPagination;
use App\Traits\WithNotifications;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class Unidades extends Component
{
    use WithPagination;
    use WithNotifications;

    // Propiedades para la tabla y búsqueda
    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Propiedades para el formulario
    public $unidadId = null;
    public $numero = '';
    public $descripcion = '';
    public $placa = '';
    public $marca = '';
    public $modelo = '';
    public $anio = '';
    public $serie = '';
    public $estado = true;

    // Control de modales
    public $modalFormulario = false;
    public $modalConfirmacion = false;

    // Reglas de validación
    protected function rules()
    {
        return [
            'numero' => [
                'required',
                'string',
                'max:50',
                Rule::unique('unidades', 'numero')->ignore($this->unidadId),
            ],
            'descripcion' => 'required|string|max:255',
            'placa' => 'nullable|string|max:50',
            'marca' => 'nullable|string|max:100',
            'modelo' => 'nullable|string|max:100',
            'anio' => 'nullable|string|max:10',
            'serie' => 'nullable|string|max:100',
            'estado' => 'boolean',
        ];
    }

    // Mensajes de validación personalizados
    protected $messages = [
        'numero.required' => 'El número de unidad es obligatorio.',
        'numero.max' => 'El número de unidad no debe exceder los 50 caracteres.',
        'numero.unique' => 'Este número de unidad ya está registrado.',
        'descripcion.required' => 'La descripción es obligatoria.',
        'descripcion.max' => 'La descripción no debe exceder los 255 caracteres.',
    ];

    // Resetear la paginación cuando se actualiza la búsqueda
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Definir el método de renderizado
    public function render()
    {
        $query = Unidad::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('numero', 'like', '%' . $this->search . '%')
                  ->orWhere('descripcion', 'like', '%' . $this->search . '%')
                  ->orWhere('placa', 'like', '%' . $this->search . '%')
                  ->orWhere('marca', 'like', '%' . $this->search . '%')
                  ->orWhere('modelo', 'like', '%' . $this->search . '%');
            });
        }

        $unidades = $query->orderBy($this->sortField, $this->sortDirection)
                        ->paginate($this->perPage);

        return view('livewire.unidades', [
            'unidades' => $unidades
        ]);
    }

    // Método para abrir el modal con formulario vacío (nueva unidad)
    public function crear()
    {
        $this->reset(['unidadId', 'numero', 'descripcion', 'placa', 'marca', 'modelo', 'anio', 'serie', 'estado']);
        $this->estado = true;
        $this->modalFormulario = true;
    }

    // Método para abrir el modal con formulario para editar
    public function editar($id)
    {
        $unidad = Unidad::findOrFail($id);
        $this->unidadId = $unidad->id;
        $this->numero = $unidad->numero;
        $this->descripcion = $unidad->descripcion;
        $this->placa = $unidad->placa;
        $this->marca = $unidad->marca;
        $this->modelo = $unidad->modelo;
        $this->anio = $unidad->anio;
        $this->serie = $unidad->serie;
        $this->estado = $unidad->estado;
        $this->modalFormulario = true;
    }

    // Método para guardar (crear o actualizar)
    public function guardar()
    {
        $this->validate();

        try {
            if ($this->unidadId) {
                // Actualizar
                $unidad = Unidad::findOrFail($this->unidadId);
                $unidad->update([
                    'numero' => strtoupper($this->numero),
                    'descripcion' => strtoupper($this->descripcion),
                    'placa' => strtoupper($this->placa),
                    'marca' => strtoupper($this->marca),
                    'modelo' => strtoupper($this->modelo),
                    'anio' => $this->anio,
                    'serie' => strtoupper($this->serie),
                    'estado' => $this->estado,
                ]);
                $mensaje = 'Unidad actualizada correctamente.';
            } else {
                // Crear nuevo
                Unidad::create([
                    'numero' => strtoupper($this->numero),
                    'descripcion' => strtoupper($this->descripcion),
                    'placa' => strtoupper($this->placa),
                    'marca' => strtoupper($this->marca),
                    'modelo' => strtoupper($this->modelo),
                    'anio' => $this->anio,
                    'serie' => strtoupper($this->serie),
                    'estado' => $this->estado,
                ]);
                $mensaje = 'Unidad registrada correctamente.';
            }

            $this->modalFormulario = false;
            $this->reset(['unidadId', 'numero', 'descripcion', 'placa', 'marca', 'modelo', 'anio', 'serie', 'estado']);
            $this->notify('success', $mensaje, 8000);

        } catch (\Exception $e) {
            Log::error('Error al guardar unidad', [
                'error' => $e->getMessage(),
                'unidadId' => $this->unidadId,
                'numero' => $this->numero,
                'descripcion' => $this->descripcion
            ]);

            $this->notify('error', 'Error al guardar la unidad. Por favor, inténtelo de nuevo.', 10000);
        }
    }

    // Método para confirmar eliminación
    public function confirmarEliminar($id)
    {
        $this->unidadId = $id;
        $this->modalConfirmacion = true;
    }

    // Método para cancelar eliminación
    public function cancelarEliminar()
    {
        $this->unidadId = null;
        $this->modalConfirmacion = false;
    }

    // Método para eliminar
    public function eliminar()
    {
        try {
            $unidad = Unidad::findOrFail($this->unidadId);
            
            // Verificar si la unidad tiene partes diarios asociados
            if ($unidad->partesDiarios()->count() > 0) {
                throw new \Exception('No se puede eliminar la unidad porque tiene partes diarios asociados.');
            }
            
            $unidad->delete();

            $this->modalConfirmacion = false;
            $this->unidadId = null;

            $this->notify('success', 'Unidad eliminada correctamente.', 8000);

        } catch (\Exception $e) {
            Log::error('Error al eliminar unidad', [
                'error' => $e->getMessage(),
                'unidadId' => $this->unidadId
            ]);

            $this->notify('error', 'Error al eliminar la unidad: ' . $e->getMessage(), 10000);
            $this->modalConfirmacion = false;
        }
    }

    // Método para cambiar el estado activo/inactivo
    public function cambiarEstado($id)
    {
        try {
            $unidad = Unidad::findOrFail($id);
            $unidad->update([
                'estado' => !$unidad->estado
            ]);

            $estado = $unidad->estado ? 'activada' : 'desactivada';
            $this->notify('success', "Unidad {$estado} correctamente.", 8000);

        } catch (\Exception $e) {
            Log::error('Error al cambiar estado de la unidad', [
                'error' => $e->getMessage(),
                'unidadId' => $id
            ]);

            $this->notify('error', 'Error al cambiar el estado de la unidad.', 10000);
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