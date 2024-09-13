<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Familia;
use App\Models\SubFamilia;
use App\Models\Detalle;
use App\Models\TasaIgv;
use App\Models\TipoDeMoneda;
use App\Models\TipoDeComprobanteDePagoODocumento;
use App\Services\ApiService;
use App\Services\CalculoDocumentosService;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\Else_;

class RegistroDocumentosIngreso extends Component
{
    public $aperturaId;
    public $familiaId; // Almacena el ID seleccionado de la familia
    public $subfamiliaId; // Almacena el ID seleccionado de la subfamilia
    public $detalleId; // Almacena el ID seleccionado del detalle
    public $tasaIgvId; // Almacena el ID de la tasa de IGV seleccionada
    public $monedaId; // Almacena el ID de la moneda seleccionada
    public $TDocId; // Abelardo = Almacena el ID del documento seleccionado
    public $TDocDesc; // Abelardo = Almacena la descripcion del documento seleccionado
    public $valTdoc = 0; // Abelardo = Almacena codificacion para la validacion de los tdocs
    public $valDocIden = 0; // Abelardo = Almacena codificacion para la validacion de los docident
    public $valGrav = 0; // Abelardo = Almacena codificacion para la validacion de los gravados
    public $ErrorDocIden; // Abelardo = Almacena los errores
    public $rucId; // Abelardo = Almacena el Ruc
    public $docIdenId; // Abelardo = Almacena el Id doc Ident
    public $dosIdenDesc; // Abelardo = Almacena la descripcion doc Ident
    public $idTipGrav; // Abelardo = Almacena el tipo de gravado

    public $familias = []; // Lista de familias para el select
    public $subfamilias = []; // Lista de subfamilias filtradas
    public $detalles = []; // Lista de detalles filtrados
    public $tasasIgv = []; // Lista de tasas de IGV para el select
    public $monedas = []; // Lista de tipos de moneda para el select
    public $TDoc; // Abelardo = Lista los tdocs
    public $descIdenId = "T. doc Ident"; // Abelardo = Almacena la descripcion del tdocIdent
    public $lenIdenId = 2; //Abelardo = Almacena la longitud del input de tdocIdent

    public $BI = 0; //Abelardo = Almacena la BI
    public $IGV = 0; //Abelardo = Almacena la BI
    public $OtroTrib = 0; //Abelardo = Almacena la BI
    public $NoGravado = 0; //Abelardo = Almacena la BI
    public $Precio = 0; //Abelardo = Almacena la BI

    protected $apiService; // Abelardo = Cree un service para el Api de busqueda de Ruc
    protected $CalculoDocumentosService; // Abelardo = Cree un service para los calculos de los documentos

    public function mount($aperturaId, ApiService $apiService,CalculoDocumentosService $CalculoDocumentosService)
    {
        $this->aperturaId = $aperturaId;
        $this->apiService = $apiService; // Abelardo = Asigne el servicio inyectado para la api.
        $this->CalculoDocumentosService = $CalculoDocumentosService; // Abelardo = Asigne el servicio inyectado para el calculo de documentos.
        $this->loadInitialData();
    }

    public function hydrate(ApiService $apiService,CalculoDocumentosService $CalculoDocumentosService) // Abelardo = Hidrate la inyecion del servicio puesto que no esta funcionando el servicio, con esta opcion logre pasar el service por las diferentes funciones
    {
        $this->apiService = $apiService;
        $this->CalculoDocumentosService = $CalculoDocumentosService;
    }

    // Cargar datos iniciales para los ComboBoxes
    public function loadInitialData()
    {
        // Aquí filtramos las familias para que el ID comience con '0'
        $this->familias = Familia::where('id', 'like', '0%')->get();
        $this->tasasIgv = TasaIgv::all();
        $this->monedas = TipoDeMoneda::all();
    }

    // Método que se ejecuta cuando se selecciona una familia
    public function updatedFamiliaId($value)
    {
        // Filtra las subfamilias basándose en la familia seleccionada
        $this->subfamilias = SubFamilia::where('id_familias', $value)->get();
        $this->reset('subfamiliaId', 'detalleId'); // Reiniciar subfamilia y detalle seleccionados

    }

    // Método que se ejecuta cuando se selecciona una subfamilia
    public function updatedSubfamiliaId($value)
    {
        // Filtra los detalles basándose en la subfamilia seleccionada
        $this->detalles = Detalle::where('id_subfamilia', $value)->get();
        $this->reset('detalleId'); // Reiniciar detalle seleccionado
    }
 

    // Abelardo = Se activa el evento Enter para sacar la descripcion del documento
    public function EnterTDocId(){
        // Abelardo = la validacion se pone por defecto en cero
        $this -> valTdoc = 0;
        // Abelardo = Se consulta en vase al numero escrito en el input T.doc
        $this -> TDoc = TipoDeComprobanteDePagoODocumento::where('id',$this -> TDocId)
              -> get()
              -> toarray();
         // Abelardo = Se hace un conteo de los datos del array
        if (count($this -> TDoc) <> 0){
            // Abelardo = Si devuelve se inpregna en la vista
            $this -> TDocDesc = $this -> TDoc[0]['descripcion'];
        } else {
            // Abelardo = Si no devuelve regresa los valores a "" y la validacion a 1 para que muestre el mensaje de error
            $this -> TDocDesc = "";
            $this -> TDocId = "";
            $this -> valTdoc = 1;
        }
 
    }

    public function updateddocIdenId($value){
        if($value === '1'){
            $this -> descIdenId = 'DNI: ';
            $this -> lenIdenId = 8;
            $this -> rucId = "";
        } else {
            $this -> descIdenId = 'RUC: ';
            $this -> lenIdenId = 11;
            $this -> rucId = "";
        };
    }

    public function EnterRuc(){ //Abelardo = Evento enter para RUC
        $this -> valDocIden = 0;
        if($this -> docIdenId <> ''){
            $data = $this -> apiService -> REntidad($this -> docIdenId,$this -> rucId);
            if ($data['success'] == '1') {
                $this -> dosIdenDesc = $data['desc'];
            }else{
                $this -> valDocIden = 1;
                $this -> ErrorDocIden = $data['desc'];    
            }
        }else{
            $this -> valDocIden = 1;
            $this -> ErrorDocIden = 'Elige un Tip de Indentidad';
        }
    }

    public function updatedBI($value) { //Abelardo = Actualiza la BI y calcula el resto
        Log::info($value);
        $this -> valGrav = 0;
        if ($this -> idTipGrav <> ''){
            $data = $this -> CalculoDocumentosService -> calculoBI($value,$this -> IGV,$this -> OtroTrib,$this -> NoGravado,$this -> Precio,$this -> idTipGrav);
            if ($data <> 'N'){
                $this -> BI = $data['BI'];
                $this -> IGV = $data['IGV'];
                $this -> OtroTrib = $data['OtroTributo'];
                $this -> NoGravado = $data['NoGravado'];
                $this -> Precio = $data['Precio'];
            }
            
        }else{
            $this -> valGrav = 1;
        }
    }

    public function updatedIGV($value) { //Abelardo = Actualiza la BI y calcula el resto
        $this -> valGrav = 0;
        $puntopartida = 1;
        if ($this -> idTipGrav <> ''){
            $data = $this -> CalculoDocumentosService -> CalculoGN($this -> IGV,$value,$this -> OtroTrib,$this -> NoGravado,$this -> Precio,$this -> idTipGrav,$puntopartida);
            if ($data <> 'N'){
                $this -> BI = $data['BI'];
                $this -> IGV = $data['IGV'];
                $this -> OtroTrib = $data['OtroTributo'];
                $this -> NoGravado = $data['NoGravado'];
                $this -> Precio = $data['Precio'];
            }
            
        }else{
            $this -> valGrav = 1;
        }
    }


    public function render()
    {
        return view('livewire.registro-documentos-ingreso');
    }


}
