<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TipoDocumentoIdentidad;
use Livewire\Attributes\On;
use App\Services\ApiService;
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

    public function clearFields()
    {
        $this->reset(['entidad', 'entidad_id', 'tipoDocId']);
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
