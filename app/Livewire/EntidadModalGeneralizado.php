<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TipoDocumentoIdentidad;
use Livewire\Attributes\On;
use App\Services\ApiService;
use Illuminate\Support\Facades\DB;
use App\Models\Entidad;
use Illuminate\Support\Facades\Log;

class EntidadModalGeneralizado extends Component
{
    public $entidad;
    public $entidad_id;
    public $openModal = false;
    public $docs;
    public $tipoDocId;
    public $docIdent;
    public $desconozcoTipoDocumento = true;
    public $desconozcoTipoDocumento1 = false;

    protected $apiService;

    public function mount(ApiService $apiService)
    {
        $this->docs = TipoDocumentoIdentidad::whereIn('id', ['1', '6'])->get();
        $this->apiService = $apiService;
    }

    #[On('openModalEntidad')]
    public function abrirModal($value)
    {
        $this->openModal = $value;
    }

    public function updatedDesconozcoTipoDocumento($value)
    {
        Log::info($value);
        if ($value == 1) {
            $value = true;
            $this->desconozcoTipoDocumento1 = false;
            $this->clearFields();
        } else {
            $value = false;
            $this->desconozcoTipoDocumento1 = true;
            $this->clearFields();
        }
    }

    protected function rules()
    {
        return [
            'tipoDocId' => 'required|in:1,6',
            'docIdent' => 'required|numeric|unique:entidades,id|digits_between:8,11',
        ];
    }

    protected function messages()
    {
        return [
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
        try {
            DB::transaction(function () {
                $this->tipoDocId = $this->tipoDocId ?? 1;
                if (empty($this->entidad)) {
                    session()->flash('error', 'El nombre está vacío');
                    throw new \Exception('El nombre de la entidad está vacío.');
                }

                $existingEntidadByDoc = Entidad::where('id', $this->docIdent)
                    ->where('idt02doc', $this->tipoDocId)
                    ->first();

                if ($existingEntidadByDoc) {
                    session()->flash('error', 'Esta entidad ya está registrada con el mismo número de documento.');
                    throw new \Exception('Entidad ya registrada.');
                }

                $existingEntidadByDescripcion = Entidad::where('descripcion', $this->entidad)->first();

                if ($existingEntidadByDescripcion) {
                    session()->flash('error', 'Ya existe una entidad con la misma descripción.');
                    throw new \Exception('Entidad con descripción duplicada.');
                }

                $entidad = Entidad::where('id', 'like', '100%')
                    ->where('idt02doc', '1')
                    ->lockForUpdate()
                    ->orderByRaw('CAST(id AS UNSIGNED) DESC')
                    ->first();

                $id = $entidad ? $entidad->id + 1 : '10000001';

                Log::info('Entidad creada', [
                    'id' => $id,
                    'descripcion' => $this->entidad,
                    'estado_contribuyente' => '-',
                    'estado_domicilio' => '-',
                    'provincia' => '-',
                    'distrito' => '-',
                    'idt02doc' => '1'
                ]);

                Entidad::create([
                    'id' => $id,
                    'descripcion' => $this->entidad,
                    'doc_ident' => $this->docIdent,
                    'estado_contribuyente' => '-',
                    'estado_domicilio' => '-',
                    'provincia' => '-',
                    'distrito' => '-',
                    'idt02doc' => $this->tipoDocId
                ]);

                $this->dispatch('entidad-created');
                $this->reset(['entidad', 'tipoDocId', 'docIdent']);
                session()->flash('message', 'Entidad creada exitosamente.');
                $this->openModal = false;
            });
        } catch (\Exception $e) {
            Log::error('Error al crear entidad: ' . $e->getMessage());
            session()->flash('error', 'Ocurrió un error: ' . $e->getMessage());
            $this->openModal = true;
        }
    }

    public function hydrate(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function processDocIdent()
    {
        if (!$this->tipoDocId) {
            $this->addError('tipoDocId', 'Debe seleccionar un tipo de documento antes de ingresar el número.');
            return;
        }

        if ($this->tipoDocId === '1' && strlen($this->docIdent) !== 8) {
            $this->addError('docIdent', 'El DNI debe tener 8 dígitos.');
            return;
        } elseif ($this->tipoDocId === '6' && strlen($this->docIdent) !== 11) {
            $this->addError('docIdent', 'El RUC debe tener 11 dígitos.');
            return;
        }

        $response = $this->apiService->REntidad($this->tipoDocId, $this->docIdent);
        Log::info($response);
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
        return view('livewire.entidad-modal-generalizado');
    }
} 