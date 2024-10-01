<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TipoDocumentoIdentidad;
use Livewire\Attributes\On;
use App\Services\ApiService;
use Illuminate\Support\Facades\DB;
use App\Models\Entidad;
use Illuminate\Support\Facades\Log;

class EntidadModal extends Component
{
    public $entidad;
    public $entidad_id;
    public $openModal;
    public $docs;
    public $tipoDocId;
    public $docIdent;
    public $desconozcoTipoDocumento = false;
    public $desconozcoTipoDocumento1 = true;

    protected $apiService;

    public function mount(ApiService $apiService)
    {
        $this->docs = TipoDocumentoIdentidad::whereIn('id', ['1', '6'])->get();
        $this->apiService = $apiService;
    }

    public function updatedDesconozcoTipoDocumento($value)
{
    // Aquí puedes hacer algo cuando cambie el valor de desconozcoTipoDocumento
    Log::info($value);
    if ($value == 1) {
        $value = true;
        $this -> desconozcoTipoDocumento1 = false;
        $this -> clearFields();
    } else {
        $value = false;
        $this -> desconozcoTipoDocumento1 = true;
        $this -> clearFields();
    }
}



    protected function rules()
    {
        return [
            'tipoDocId' => 'required|in:1,6', // Solo puede ser DNI (1) o RUC (6)
            'docIdent' => 'required|numeric|unique:entidades,id|digits_between:8,11',
        ];
    }

    // Mensajes personalizados para las validaciones
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
        //$this->validate();
    
        if ($this -> entidad == ''){
            session()->flash('error', 'El nombre esta vacio');
            return;
        }

        
        $entidad = Entidad::where('id', 'like', '100%')
            ->where('idt02doc', '1')
            ->orderByRaw('CAST(id AS UNSIGNED) DESC')
            ->first(); // Obtener el primer registro que coincide con los filtros

        // Sumar 1 al valor de id en PHP
        $id = $entidad ? $entidad->id + 1 : null;

        Log::info('Entidad creada', [
            'id' => $id,
            'descripcion' => $this->entidad,
            'estado_contribuyente' => '-',
            'estado_domicilio' => '-',
            'provincia' => '-',
            'distrito' => '-',
            'idt02doc' => '1'
        ]);
            
        Entidad::create(['id' => $id,
                          'descripcion' => $this -> entidad,
                          'estado_contribuyente' => '-',
                          'estado_domiclio' => '-',
                          'provincia' => '-',
                          'distrito' => '-',
                          'idt02doc' => '1' ]);
    
        
            // Emitir evento para actualizar la tabla
        $this->dispatch('entidad-created');

        // Limpiar campos después de la inserción
        $this->reset(['entidad', 'tipoDocId', 'docIdent']);

        // Emitir un mensaje de éxito
        session()->flash('message', 'Entidad creada exitosamente.');
        // Tiempo de espera de la transacción (5 segundos)
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
        return view('livewire.entidad-modal');
    }
}