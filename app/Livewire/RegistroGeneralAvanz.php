<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;
use App\Models\TasaIgv;
use App\Models\TipoDeComprobanteDePagoODocumento;
use App\Models\Documento;
use DateTime;
use App\Services\ApiService;
use App\Models\TipoDeMoneda;
use App\Models\Entidad;
use App\Models\TipoDeCaja;
use App\Models\Apertura;
use App\Models\TipoDocumentoIdentidad;
class RegistroGeneralAvanz extends Component
{
    protected $tasaIgvMapping = [
        '18%' => 0.18,
        '10%' => 0.10,
        'No Gravado' => 0.00,
    ];
    public $tasasIgv = []; 
    public $basImp = 0;
    public $igv = 0;
    public $otrosTributos = 0;
    public $noGravado = 0;
    public $precio = 0;
    public $tasaIgvId;

    public $aperturaId;
    public $apertura;
    public $origen;
    public $productos;

    public $lenIdenId;
public $tipoDocDescripcion;
public  $serieNumero1;
public  $serieNumero2;
public $tipoDocIdentidades;
public  $destinatarios;
public  $tipoDocId;
public  $entidad;
public  $fechaEmi;
public  $fechaVen;
public  $monedaId;
public  $tipoDocumento;
public  $docIdent;
public $observaciones;
public $monedas;
protected $apiService;

    public function calculateIgv()
{
    // Convertir base imponible a número flotante para evitar errores
    $baseImponible = floatval($this->basImp);

    // Si la base imponible o la tasa de IGV no son válidas, asignar IGV a 0
    if ($baseImponible <= 0 || !$this->tasaIgvId) {
        $this->igv = 0;
        return;
    }

    // Obtener la tasa correspondiente, con un valor predeterminado de 0 si no existe
    $tasa = $this->tasaIgvMapping[$this->tasaIgvId] ?? 0;
    $this->igv = round($baseImponible * $tasa, 2);
}

public function updatedtipoDocId($value){
    if($value === '1'){;
        $this -> lenIdenId = 8;
        $this -> docIdent = "";
    } else { 
        $this -> lenIdenId = 11;
        $this -> docIdent = "";
    };
}


public function hydrate(ApiService $apiService) // Abelardo = Hidrate la inyecion del servicio puesto que no esta funcionando el servicio, con esta opcion logre pasar el service por las diferentes funciones
{
    $this->apiService = $apiService;
}
public function EnterRuc(){ //Abelardo = Evento enter para RUC
    if($this -> tipoDocId <> ''){
        $data = $this->apiService->REntidad($this -> tipoDocId , $this -> docIdent);
        if ($data['success'] == '1') {
            $this -> entidad = $data['desc'];
        }else{
            session()->flash('error', $data['desc']);
            $this->docIdent = '';
            $this->entidad = '';    
        }
    }else{
        session()->flash('error', 'Elige un Tip de Indentidad');
        $this -> docIdent = '';
        $this -> entidad = '';
    }
}

public function buscarDescripcionTipoDocumento()
{
    // Buscar el tipo de documento en la base de datos
    $tipoComprobante = TipoDeComprobanteDePagoODocumento::where('id', $this->tipoDocumento)->first();
    $this->apertura = Apertura::findOrFail( $this->aperturaId);
    // Si se encuentra el tipo de documento, actualizamos la descripción
    if ($tipoComprobante) {
        $this->tipoDocDescripcion = $tipoComprobante->descripcion;
        if($this->tipoDocumento=='75'){
            $this->serieNumero1 = '0000';
            // Obtener el siguiente número de serie utilizando el modelo Documento
            $ultimoDocumento = Documento::where('id_t10tdoc',  $this->tipoDocumento) // Tipo de documento 74
                ->where('serie', $this->serieNumero1) // Serie 0000
                ->orderByRaw('CAST(numero AS UNSIGNED) DESC') // Ordenar por número de documento de manera descendente
                ->first(); // Obtener el primer registro (el número más alto)

            // Asignar el siguiente número de serie
            if ($ultimoDocumento) {
                $this->serieNumero2 = intval($ultimoDocumento->numero) + 1; // Incrementar el número en 1
            } else {
                $this->serieNumero2 = '1'; // Si no hay registros, empezar con 1
            }

            $this->destinatarios = TipoDeCaja::all();

            $this->tipoDocId = '1'; // RUC
            $this->docIdent = '10000001'; // Valor por defecto

            $entidad = Entidad::where('id', $this->docIdent)->first();
            $this->entidad = $entidad->descripcion;
            $fecha = (new DateTime($this->apertura->fecha))->format('Y-m-d');
            Log::info('Fecha formateada: ', ['fecha' => $fecha]);
            $this->fechaEmi = $fecha;
            $this->fechaVen = $fecha;

            // Encontrar la tasa de IGV por la descripcion seleccionada
            $tasaIgv = TasaIgv::where('tasa', 'No Gravado')->first();

            if ($tasaIgv) {
                $this->tasaIgvId = $tasaIgv->tasa; // Usamos el id internamente si es necesario
                Log::info('Tasa IGV encontrada: ', ['id' => $tasaIgv->id, 'tasa' => $tasaIgv->tasa]);
            } else {
                Log::warning('No se encontró la Tasa IGV con el valor: ' . $this->tasaIgvDescripcion);
            }

            $this->monedaId = TipoDeMoneda::where('id', 'PEN')->first()->id;

        }else{
            $this->reset([
            'serieNumero1',
            'serieNumero2',
            'tipoDocId',
            'docIdent',
            'fechaEmi',
            'fechaVen',
            'monedaId',
            'tasaIgvId',
            'observaciones',
            'entidad'
        ]);

        }

    } else {
        // Si no se encuentra, puedes asignar un mensaje de error o dejar vacío = Abelardo = Modifique estos datos adaptarlo a la idea
        $this->tipoDocumento = '';
        $this->tipoDocDescripcion = '';
        session()->flash('error', 'Descripción no encontrada');
    }
}


public function loadInitialData()
{
    
    $this->tasasIgv = TasaIgv::all();
    $this->monedas = TipoDeMoneda::all();
    $this->tasasIgv = TasaIgv::all();
    $this->tipoDocIdentidades = TipoDocumentoIdentidad::whereIn('id', ['1', '6'])->get();
   
}
// Function to calculate the total price dynamically
public function calculatePrecio()
{
    // Convertir los valores a números flotantes para evitar errores de tipo
    $baseImponible = floatval($this->basImp);
    $igv = floatval($this->igv);
    $otrosTributos = floatval($this->otrosTributos);
    $noGravado = floatval($this->noGravado);

    // Calcular el precio total y redondearlo a 2 decimales
    $this->precio = round($baseImponible + $igv + $otrosTributos + $noGravado, 2);
}

// Livewire hooks for triggering the functions when fields are updated
public function updatedBasImp($value)
{
    // Verificar si el valor ingresado es numérico, de lo contrario asignar 0
    $this->basImp = is_numeric($value) ? $value : 0;

    // Recalcular IGV y precio con los nuevos valores
    $this->calculateIgv();
    $this->calculatePrecio();
}

public function updatedTasaIgvId($value)
{   
    // Recalculate IGV based on the updated tasa
    $this->calculateIgv();

    // Recalculate the total price
    $this->calculatePrecio();
}



public function calculateTotals()
{
    // Reiniciar valores
    $this->basImp = 0;
    $this->noGravado = 0;
    $this->igv = 0;
    $this->precio = 0;

    // Verificar si hay productos para recalcular
    if (!empty($this->productos)) {
        foreach ($this->productos as $producto) {
            if ($producto['tasaImpositiva'] == 'Sí' || $producto['tasaImpositiva'] == '1') {
                // Sumar al Base Imponible y aplicar IGV
                $this->basImp += floatval($producto['total']);
            } else {
                // Sumar al No Gravado
                $this->noGravado += floatval($producto['total']);
            }
        }

        // Calcular el IGV sobre la base imponible
        $this->calculateIgv();
    }

    // Calcular el precio total
    $this->calculatePrecio();
}


public function updatedOtrosTributos($value)
{
    // Recalculate the total price whenever otros tributos is updated
    $this->calculatePrecio();
}

public function updatedNoGravado($value)
{
    // Recalculate the total price whenever no gravado is updated
    $this->calculatePrecio();
}
public function updatedIgv($value)
{
    // Asegurarse de que el valor ingresado es numérico; si no, asignar 0
    $this->igv = is_numeric($value) ? floatval($value) : 0;

    // Recalcular el precio total
    $this->calculatePrecio();
}


public function mount($aperturaId)
{
    $this->aperturaId = $aperturaId;
    $this->origen = request()->get('origen', 'ingreso'); // Default a 'ingreso'

    $this->loadInitialData();

    Log::info('Parámetros recibidos', [
        'aperturaId' => $this->aperturaId,
        'origen' => $this->origen,
        'request_data' => request()->all()
    ]);

    // Cargar productos según el origen desde la sesión
    $this->productos = Session::get("productos_{$this->origen}", []);
}

     // Escuchar el evento 'productoEnviado' utilizando #[On] 
     #[On('productoEnviado')]
     public function procesarProducto($data)
     {
         $this->productos[] = $data;
     
         // Guardar productos en la sesión según el origen
         Session::put("productos_{$this->origen}", $this->productos);
     
         session()->flash('message', 'Producto añadido exitosamente.');
     
         // Recalcular los totales después de agregar un producto
         $this->calculateTotals();
     }
     

        // Función para eliminar un producto específico por su índice
        public function eliminarProducto($index)
        {
            if (isset($this->productos[$index])) {
                unset($this->productos[$index]);
        
                // Reindexar el array para evitar huecos
                $this->productos = array_values($this->productos);
        
                // Guardar la lista actualizada en la sesión según el origen
                Session::put("productos_{$this->origen}", $this->productos);
        
                session()->flash('message', 'Producto eliminado.');
            }
        
            // Recalcular los totales después de eliminar un producto
            $this->calculateTotals();
        }
        


    public function render()
    {
        return view('livewire.registro-general-avanz')->layout('layouts.app');

    }
}
