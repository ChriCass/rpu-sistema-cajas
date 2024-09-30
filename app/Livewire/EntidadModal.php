<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TipoDocumentoIdentidad;
use Livewire\Attributes\On;
use App\Services\ApiService;
use Illuminate\Support\Facades\DB;
use App\Models\Entidad;

class EntidadModal extends Component
{
    public $entidad;
    public $entidad_id;
    public $openModal;
    public $docs;
    public $tipoDocId;
    public $docIdent;
    public $desconozcoTipoDocumento = false;

    protected $apiService;

    public function mount(ApiService $apiService)
    {
        $this->docs = TipoDocumentoIdentidad::whereIn('id', ['1', '6'])->get();
        $this->apiService = $apiService;
    }

    protected function rules()
    {
        return [
            'entidad' => 'required|string|max:255',
            'tipoDocId' => 'required|in:1,6', // Solo puede ser DNI (1) o RUC (6)
            'docIdent' => 'required|numeric|unique:entidades,id|digits_between:8,11',
        ];
    }

    // Mensajes personalizados para las validaciones
    protected function messages()
    {
        return [
            'entidad.required' => 'El campo descripción de la entidad es obligatorio.',
            'tipoDocId.required' => 'Debe seleccionar un tipo de documento.',
            'tipoDocId.in' => 'El tipo de documento debe ser DNI o RUC.',
            'docIdent.required' => 'El número de documento es obligatorio.',
            'docIdent.numeric' => 'El número de documento debe ser numérico.',
            'docIdent.unique' => 'Este número de documento ya está registrado.',
            'docIdent.digits_between' => 'El número de documento debe tener entre 8 y 11 dígitos.',
        ];
    }

    public function clearFields()
    {
        $this->reset(['entidad', 'entidad_id', 'tipoDocId']);
    }

    public function submitEntidad()
    {
        $this->validate();
    
        // Iniciar una transacción para manejar concurrencia
        DB::transaction(function () {
            // Verifica si ya existe una entidad con el mismo RUC o DNI y crea si no existe
            $entidad = Entidad::firstOrCreate(
                ['id' => $this->docIdent],  // RUC o DNI como el ID de la entidad
                [
                    'descripcion' => $this->entidad, // Descripción ingresada
                    'idt02doc' => $this->tipoDocId,  // Tipo de documento (DNI o RUC)
                ]
            );
    
            if ($entidad->wasRecentlyCreated) {
                // Emitir evento para actualizar la tabla
                $this->dispatch('entidad-created');
    
                // Limpiar campos después de la inserción
                $this->reset(['entidad', 'tipoDocId', 'docIdent']);
    
                // Emitir un mensaje de éxito
                session()->flash('message', 'Entidad creada exitosamente.');
            } else {
                session()->flash('error', 'La entidad con este RUC o DNI ya existe.');
            }
        }, 5); // Tiempo de espera de la transacción (5 segundos)
    }
    

    public function hydrate(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    // Procesar el documento ingresado al presionar Enter
    public function processDocIdent()
    {
        // Validar que el tipo de documento está seleccionado
        if (!$this->tipoDocId) {
            $this->addError('tipoDocId', 'Debe seleccionar un tipo de documento antes de ingresar el número.');
            return;
        }

        // Determinar si el tipo de documento es DNI (1) o RUC (6)
        if ($this->tipoDocId === '1' && strlen($this->docIdent) !== 8) {
            $this->addError('docIdent', 'El DNI debe tener 8 dígitos.');
            return;
        } elseif ($this->tipoDocId === '6' && strlen($this->docIdent) !== 11) {
            $this->addError('docIdent', 'El RUC debe tener 11 dígitos.');
            return;
        }

        // Enviar al servicio API para validar el documento
        $response = $this->apiService->REntidad($this->tipoDocId, $this->docIdent);

        if ($response['success'] === '1') {
            $this->entidad = $response['desc'];
        } else {
            $this->addError('docIdent', $response['desc']);
            $this->docIdent = '';
            $this->entidad = '';
        }
    }
    public function render()
    {
        return view('livewire.entidad-modal');
    }
}
