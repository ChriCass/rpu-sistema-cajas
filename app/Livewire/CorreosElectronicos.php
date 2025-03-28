<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CorreoElectronico;
use Livewire\WithPagination;
use App\Traits\WithNotifications;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class CorreosElectronicos extends Component
{
    use WithPagination;
    use WithNotifications;

    // Propiedades para la tabla y búsqueda
    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Propiedades para el formulario
    public $correoId = null;
    public $descripcion = '';
    public $activo = true;

    // Control de modales
    public $modalFormulario = false;
    public $modalConfirmacion = false;

    // Reglas de validación
    protected function rules()
    {
        return [
            'descripcion' => [
                'required',
                'email',
                'max:100',
                Rule::unique('correos_electronicos', 'descripcion')->ignore($this->correoId),
            ],
            'activo' => 'boolean',
        ];
    }

    // Mensajes de validación personalizados
    protected $messages = [
        'descripcion.required' => 'La dirección de correo es obligatoria.',
        'descripcion.email' => 'Debe ingresar una dirección de correo válida.',
        'descripcion.max' => 'La dirección de correo no debe exceder los 100 caracteres.',
        'descripcion.unique' => 'Esta dirección de correo ya está registrada.',
    ];

    // Resetear la paginación cuando se actualiza la búsqueda
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Definir el método de renderizado
    public function render()
    {
        $query = CorreoElectronico::query();

        if ($this->search) {
            $query->where('descripcion', 'like', '%' . $this->search . '%');
        }

        $correos = $query->orderBy($this->sortField, $this->sortDirection)
                        ->paginate($this->perPage);

        return view('livewire.correos-electronicos', [
            'correos' => $correos
        ]);
    }

    // Método para abrir el modal con formulario vacío (nuevo correo)
    public function crear()
    {
        $this->reset(['correoId', 'descripcion', 'activo']);
        $this->activo = true;
        $this->modalFormulario = true;
    }

    // Método para abrir el modal con formulario para editar
    public function editar($id)
    {
        $correo = CorreoElectronico::findOrFail($id);
        $this->correoId = $correo->id;
        $this->descripcion = $correo->descripcion;
        $this->activo = $correo->activo;
        $this->modalFormulario = true;
    }

    // Método para guardar (crear o actualizar)
    public function guardar()
    {
        $this->validate();

        try {
            if ($this->correoId) {
                // Actualizar
                $correo = CorreoElectronico::findOrFail($this->correoId);
                $correo->update([
                    'descripcion' => $this->descripcion,
                    'activo' => $this->activo,
                ]);
                $mensaje = 'Correo electrónico actualizado correctamente.';
            } else {
                // Crear nuevo
                CorreoElectronico::create([
                    'descripcion' => $this->descripcion,
                    'activo' => $this->activo,
                ]);
                $mensaje = 'Correo electrónico registrado correctamente.';
            }

            $this->modalFormulario = false;
            $this->reset(['correoId', 'descripcion', 'activo']);
            $this->notify('success', $mensaje, 8000);

        } catch (\Exception $e) {
            Log::error('Error al guardar correo electrónico', [
                'error' => $e->getMessage(),
                'correoId' => $this->correoId,
                'descripcion' => $this->descripcion
            ]);

            $this->notify('error', 'Error al guardar el correo electrónico. Por favor, inténtelo de nuevo.', 10000);
        }
    }

    // Método para confirmar eliminación
    public function confirmarEliminar($id)
    {
        $this->correoId = $id;
        $this->modalConfirmacion = true;
    }

    // Método para cancelar eliminación
    public function cancelarEliminar()
    {
        $this->correoId = null;
        $this->modalConfirmacion = false;
    }

    // Método para eliminar
    public function eliminar()
    {
        try {
            $correo = CorreoElectronico::findOrFail($this->correoId);
            $correo->delete();

            $this->modalConfirmacion = false;
            $this->correoId = null;

            $this->notify('success', 'Correo electrónico eliminado correctamente.', 8000);

        } catch (\Exception $e) {
            Log::error('Error al eliminar correo electrónico', [
                'error' => $e->getMessage(),
                'correoId' => $this->correoId
            ]);

            $this->notify('error', 'Error al eliminar el correo electrónico. Por favor, inténtelo de nuevo.', 10000);
        }
    }

    // Método para cambiar el estado activo/inactivo
    public function cambiarEstado($id)
    {
        try {
            $correo = CorreoElectronico::findOrFail($id);
            $correo->update([
                'activo' => !$correo->activo
            ]);

            $estado = $correo->activo ? 'activado' : 'desactivado';
            $this->notify('success', "Correo electrónico {$estado} correctamente.", 8000);

        } catch (\Exception $e) {
            Log::error('Error al cambiar estado del correo electrónico', [
                'error' => $e->getMessage(),
                'correoId' => $id
            ]);

            $this->notify('error', 'Error al cambiar el estado del correo electrónico.', 10000);
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

    /**
     * Envía un correo con la lista de documentos pendientes a todos los correos activos
     */
    public function enviarDocumentosPendientes()
    {
        try {
            Log::info('Iniciando envío de documentos pendientes');
            
            // 1. Obtener todos los correos electrónicos activos
            $correos = CorreoElectronico::where('activo', true)->get();
            
            Log::info('Correos encontrados:', ['cantidad' => $correos->count()]);
            
            if ($correos->isEmpty()) {
                $this->notify('warning', 'No hay correos electrónicos activos registrados. Por favor, registre al menos un correo activo.', 7000);
                return;
            }
            
            // 2. Obtener documentos pendientes usando consulta raw
            Log::info('Ejecutando consulta de documentos pendientes');
            
            $documentosPendientes = DB::select("
                SELECT 
                    documentos.id, 
                    documentos.fechaEmi, 
                    documentos.fechaVen, 
                    documentos.id_entidades, 
                    documentos.precio,
                    documentos.id_t10tdoc,
                    documentos.serie,
                    documentos.numero, 
                    CON1.monto 
                FROM (
                    SELECT 
                        id_documentos, monto
                    FROM (
                        SELECT 
                            id_documentos,
                            id_cuentas,
                            ROUND(SUM(IF(id_dh = '1', monto, -monto)), 2) AS monto,
                            ROUND(SUM(
                                IF(id_dh = '1', IFNULL(montodo, 0), -IFNULL(montodo, 0))
                            ), 2) AS montodo
                        FROM movimientosdecaja
                        WHERE id_cuentas IN ('44')
                        GROUP BY id_documentos, id_cuentas
                    ) AS t
                    GROUP BY id_documentos 
                    HAVING monto <> 0
                ) CON1 
                LEFT JOIN documentos ON documentos.id = CON1.id_documentos
            ");
            
            Log::info('Documentos pendientes encontrados:', ['cantidad' => count($documentosPendientes)]);
            
            if (empty($documentosPendientes)) {
                $this->notify('info', 'No se encontraron documentos pendientes para enviar. El sistema está al día.', 7000);
                return;
            }
            
            // 3. Cargar entidades relacionadas para mostrar nombres
            $entidadesIds = collect($documentosPendientes)->pluck('id_entidades')->filter()->unique()->toArray();
            $entidades = \App\Models\Entidad::whereIn('id', $entidadesIds)->get()->keyBy('id');
            
            // 4. Cargar tipos de documento
            $tiposDocIds = collect($documentosPendientes)->pluck('id_t10tdoc')->filter()->unique()->toArray();
            $tiposDocumento = \App\Models\TipoDeComprobanteDePagoODocumento::whereIn('id', $tiposDocIds)->get()->keyBy('id');
            
            Log::info('Entidades encontradas:', ['cantidad' => $entidades->count()]);
            Log::info('Tipos de documento encontrados:', ['cantidad' => $tiposDocumento->count()]);
            
            // 5. Enviar correos (idealmente en un job para no bloquear)
            $enviados = 0;
            $destinatariosTexto = [];
            
            foreach ($correos as $correo) {
                // Formato del correo
                $data = [
                    'documentos' => $documentosPendientes,
                    'entidades' => $entidades,
                    'tiposDocumento' => $tiposDocumento
                ];
                
                // Enviar correo usando la clase Mail de Laravel (sin queue)
                Log::info('Enviando correo a:', ['email' => $correo->descripcion]);
                
                Mail::to($correo->descripcion)
                    ->send(new \App\Mail\DocumentosPendientesMail($data));
                
                $enviados++;
                $destinatariosTexto[] = $correo->descripcion;
            }
            
            Log::info('Correos enviados correctamente', ['total' => $enviados]);
            
            // 6. Preparar el mensaje detallado
            $cantidadCorreos = count($correos);
            $cantidadDocumentos = count($documentosPendientes);
            $destinatarios = implode(', ', $destinatariosTexto);
            
            // 7. Notificar éxito - MEJORA EN LA NOTIFICACIÓN
            $mensaje = "✅ CORREOS ENVIADOS: {$cantidadDocumentos} documentos pendientes enviados a {$cantidadCorreos} destinatarios ({$destinatarios}).";
            
            // Usar solo un método de notificación para evitar duplicación
            $this->dispatch('showNotification', [
                'id' => \Illuminate\Support\Str::random(10),
                'type' => 'success',
                'message' => $mensaje,
                'duration' => 10000
            ]);
            
            Log::info('Notificación enviada: ' . $mensaje);
            
        } catch (\Exception $e) {
            Log::error('Error al enviar documentos pendientes por correo', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $mensaje = '❌ ERROR: ' . $e->getMessage();
            
            // Usar solo un método de notificación para evitar duplicación
            $this->dispatch('showNotification', [
                'id' => \Illuminate\Support\Str::random(10),
                'type' => 'error',
                'message' => $mensaje,
                'duration' => 10000
            ]);
            
            Log::error('Notificación de error enviada: ' . $mensaje);
        }
    }
}
