<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Familia;
use App\Models\TasaIgv;
use App\Models\TipoDeMoneda;
use App\Models\Detalle;
use App\Models\CentroDeCostos;
use App\Models\TipoDeCaja;
use App\Models\Entidad;
use App\Models\SubFamilia;
use App\Models\TipoDocumentoIdentidad;
use App\Services\ApiService;
use App\Models\TipoDeComprobanteDePagoODocumento;
use Illuminate\Support\Facades\Auth;
use DateTime;
use Illuminate\Support\Facades\Log;
use App\Models\Documento; 

use App\Models\TipoDeCambioSunat;
use App\Models\MovimientoDeCaja;
use App\Models\Producto;
use App\Models\DDetalleDocumento;
use App\Models\Cuenta;
use App\Models\Apertura;


class RegistroDocumentoCxc extends Component
{
    public $familiaId; // ID de la familia seleccionada
    public $subfamiliaId; // ID de la subfamilia seleccionada
    public $detalleId; // ID del detalle seleccionado
    public $tasaIgvId; // ID de la tasa de IGV seleccionada
    public $monedaId; // ID de la moneda seleccionada
    public $tipoDocumento; // ID del tipo de documento seleccionado
    public $tipoDocumentoRef;
    public $serieNumero1; // Parte 1 del número de serie
    public $serieNumero2; // Parte 2 del número de serie
    public $tipoDocId; // Tipo de documento de identificación
    public $docIdent; // Documento de identidad
    public $fechaEmi; // Fecha de emisión
    public $fechaVen; // Fecha de vencimiento
    public $destinatarioVisible = false; // Mostrar u ocultar destinatario
    public $tipoDocDescripcion;
    public $observaciones;
    public $entidad;
    public $nuevoDestinatario;
    public $centroDeCostos; // Abelardo = Recoje el centro de costos
    public $lenIdenId; // Abelardo = recoje el largo del imput
    public $id_t10tdocMod;
    public $serieMod;
    public $numeroMod;

    public $familias = []; // Lista de familias
    public $subfamilias = []; // Lista de subfamilias filtradas
    public $detalles = []; // Lista de detalles filtrados
    public $tasasIgv = []; // Lista de tasas de IGV
    public $monedas = []; // Lista de monedas
    public $CC = []; // Abelardo = Lista de Centro de Costos
    public $visible;

    public $tipoDocIdentidades;
    public $disableFields = false; // Para manejar el estado de desactivación de campos
    public $disableFieldsEspecial = false; // Para manejar el estado de desactivación de campos
    public $destinatarios;

    public $user;

    /////
    public $PruebaArray = ""; //Abelardo = pruebas con las consultas
    public $apertura;


    public $basImp = 0;
    public $igv = 0;
    public $otrosTributos = 0;
    public $noGravado = 0;
    public $precio = 0;
    public $tipoCaja;

    protected $apiService; // Abelardo = Cree un service para el Api de busqueda de Ruc 
    // Add a method to calculate the price
   // Function to calculate IGV based on base imponible and tasa

    ///tiene detraccion

    public $toggle = false;
    public $montoDetraccion;
    public $montoNeto;
    public $porcentaje;
    public $validacionDet;


    public $cod_operacion;
    public function updatedmontoDetraccion ($value){
        if($value <> ''){
            $this -> montoNeto = $this -> precio - $value;
        }else{
            $this -> montoNeto = '';
        }   
    }

    public function updatedporcentaje($value){
        if($value <> ''){
            $this -> montoDetraccion = round(($value/100) * $this -> precio,0);
            $this -> montoNeto = $this -> precio - round(($value/100) * $this -> precio,0);
        }else{
            $this -> montoDetraccion = '';
            $this -> montoNeto = '';
        }
    }

    // Función para actualizar el estado de los inputs
    public function updatedToggle($value)
    {
        if($value == '1'){
            $this -> validacionDet = '1';
        }else{
            $this -> validacionDet = '0';
        };
        Log::info('Validacion: '.$this -> validacionDet);

        if (!$value) {
            $this->montoDetraccion = null; // Reiniciar valores si se desactiva el toggle
            $this->montoNeto = null;
        }
    }


    protected $tasaIgvMapping = [
        '18%' => 0.18,
        '10%' => 0.10,
        'No Gravado' => 0.00,
    ];
    
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
    

    #[On('mostrarDocumentosCxc')]
    public function mostrar()
    {
        $this->visible = true;
    }

    public function mount( ApiService $apiService)
    {
       
       
        $this->tipoDocIdentidades = TipoDocumentoIdentidad::whereIn('id', ['1', '6'])->get();
        $this->user = Auth::user()->id;
        $this->loadInitialData();
         
        $this->apiService = $apiService; // Abelardo = Asigne el servicio inyectado para la api.
        Log::info('Valor de tipoCaja: ' . $this->tipoCaja);

    }

    public function hydrate(ApiService $apiService) // Abelardo = Hidrate la inyecion del servicio puesto que no esta funcionando el servicio, con esta opcion logre pasar el service por las diferentes funciones
    {
        $this->apiService = $apiService;
    }

    // Cargar datos iniciales
    public function loadInitialData()
    {
        $familias1 = Familia::where('id', 'like', '0%')->get();

        $familias2 = Familia::where('id', 'like', '1%')
            ->where('id_tipofamilias', '=', '1')
            ->get();

        $this->familias = $familias1->merge($familias2);
        $this->tasasIgv = TasaIgv::all();
        $this->monedas = TipoDeMoneda::all();
        $this->detalles = Detalle::all();
        $this->CC = CentroDeCostos::all(); // Abelardo = Añadi para el select de centro de costos
        $this->tipoDocumentoRef = TipoDeComprobanteDePagoODocumento::all();
    }

    public function buscarDescripcionTipoDocumento()
    {
        // Buscar el tipo de documento en la base de datos
        $tipoComprobante = TipoDeComprobanteDePagoODocumento::where('id', $this->tipoDocumento)->first();

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
                $fecha = (new DateTime())->format('Y-m-d');

                
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

    public function EnterRuc(){ //Abelardo = Evento enter para RUC
        if($this -> tipoDocId <> ''){
            $data = $this -> apiService -> REntidad($this -> tipoDocId,$this -> docIdent);
            if ($data['success'] == '1') {
                $this -> entidad = $data['desc'];
            }else{
                session()->flash('error', $data['desc']);
                $this -> docIdent = '';
                $this -> entidad = '';    
            }
        }else{
            session()->flash('error', 'Elige un Tip de Indentidad');
            $this -> docIdent = '';
            $this -> entidad = '';
        }
    }

    // Método que se ejecuta cuando se selecciona una familia
    public function updatedFamiliaId($value)
    {
        // Actualizar las subfamilias según la familia seleccionada
        $this->subfamilias = SubFamilia::select( // Abelardo = Hice cambios para que funcione el select
            'id_familias',
            'id as ic',  // Renombramos el campo 'id' a 'ic'
            'desripcion'  
        )
        ->where('id_familias', $value)
        ->get();
        Log::info($this->subfamilias);
        $this->reset('subfamiliaId', 'detalleId'); // Reiniciar las selecciones
       
    }

    // Método que se ejecuta cuando se selecciona una subfamilia
    public function updatedSubfamiliaId($value)
    {
        // Filtrar los detalles según la subfamilia seleccionada
        $this->detalles = Detalle::where('id_subfamilia', $value)
                                    ->where('id_familias', $this -> familiaId)
                                    ->get();
        
        $this->reset('detalleId'); // Reiniciar detalle
    }

     
    // Resetear el formulario cuando se cambia la familia
    public function resetForm()
    {
        $this->reset([
            'subfamiliaId',
            'detalleId',
            'tipoDocumento',
            'serieNumero1',
            'serieNumero2',
            'tipoDocId',
            'docIdent',
            'fechaEmi',
            'fechaVen',
            'tipoDocDescripcion',
            'monedaId',
            'tasaIgvId',
            'observaciones',
            'entidad',
            'id_t10tdocMod',
            'serieMod',
            'numeroMod' 
        ]);
    }

    #[On('sending TipoCaja')]
    public function settingTipoCaja($caja)
    {
        $this->tipoCaja = $caja;

        Log::info('recibiendo el tipo de caja', ['tipo caja id' => $this->tipoCaja]);
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


    public function submit()
    {
        // Validar los campos obligatorios
        $this->validate([
            'tipoDocumento' => 'required', // TextBox52
            'tipoDocDescripcion' => 'required', // TextBox53
            'serieNumero1' => 'required', // TextBox2
            'serieNumero2' => 'required', // TextBox3
            'tipoDocId' => 'required', // ComboBox2
            'docIdent' => 'required', // TextBox4
            'entidad' => 'required', // TextBox5
            'monedaId' => 'required', // ComboBox4
            'tasaIgvId' => 'required', // ComboBox5
            'fechaEmi' => 'required|date', // TextBox33
            'fechaVen' => 'required|date', // TextBox34
            'basImp' => 'required|numeric|min:0', // TextBox11
            'igv' => 'required|numeric|min:0', // TextBox14
            'noGravado' => 'required|numeric|min:0', // TextBox13
            'precio' => 'required|numeric|min:0.01', // TextBox17
            'observaciones' => 'required|string|max:500', // TextBox29
        ], [
            'required' => 'El campo es obligatorio',
            'numeric' => 'Debe ser un valor numérico',
            'min' => 'El valor debe ser mayor a :min',
        ]);

        // Validar si el precio es 0
        if ($this->precio == 0) {
            session()->flash('error', 'No puede ser el monto cero');
            return;
        }

        if($this -> validacionDet == '1'){
            if($this -> porcentaje == '' || $this -> porcentaje == 0){
                session()->flash('error', 'No puede ser el monto cero');
                return;
            }elseif($this -> montoDetraccion == ''|| $this -> montoDetraccion == 0){
                session()->flash('error', 'No puede ser el monto cero');
                return;
            }
        }

        if ($this -> familiaId == '001') { //Abelardo = Se hace la validacion de destinario
            if($this->nuevoDestinatario == ''){
                session()->flash('error', 'Tiene que tener un destinatario');
            return;
            }
        }

        // Validar si el documento ya está registrado
        $documentoExistente = Documento::where('id_entidades', $this->docIdent)
            ->where('id_t10tdoc', $this->tipoDocumento)
            ->where('serie', $this->serieNumero1)
            ->where('numero', $this->serieNumero2)
            ->where('id_tipmov','1')
            ->first();

        if ($documentoExistente) {
            session()->flash('error', 'Documento ya registrado');
            return;
        }

        Log::info('Prueba:'.$this -> montoDetraccion);

        // Insertar el nuevo documento
        $nuevoDocumento =Documento::create([
            'id_tipmov' => 1,  ////cxc
            'fechaEmi' => $this->fechaEmi,
            'fechaVen' => $this->fechaVen,
            'id_t10tdoc' => $this->tipoDocumento,
            'id_t02tcom' => $this->tipoDocId,
            'id_entidades' => $this->docIdent,
            'id_t04tipmon' => $this->monedaId,
            // Condicional para 'id_tasasIgv' basado en la tasa
            'id_tasasIgv' => $this->tasaIgvId === 'No Gravado' ? 0 : ($this->tasaIgvId === '18%' ? 1 : ($this->tasaIgvId === '10%' ? 2 : null)),
            'serie' => $this->serieNumero1,
            'numero' => $this->serieNumero2,
            'totalBi' => $this->totalBi ?? 0,
            'descuentoBi' => $this->descuentoBi ?? 0,
            'recargoBi' => $this->recargoBi ?? 0,
            'basImp' => $this->basImp,
            'IGV' => $this->igv,
            'totalNg' => $this->totalNg ?? 0,
            'descuentoNg' => $this->descuentoNg ?? 0,
            'recargoNg' => $this->recargoNg ?? 0,
            'noGravadas' => $this->noGravado,
            'otroTributo' => $this->otroTributo ?? 0,
            'precio' => $this->precio,
            'detraccion' => $this->montoDetraccion ?? 0,
            'montoNeto' => $this->montoNeto ?? 0,
            'id_t10tdocMod' => $this->id_t10tdocMod ?? null,
            'observaciones' => $this->observaciones,
            'serieMod' => $this->serieMod ?? null,
            'numeroMod' => $this->numeroMod ?? null,
            'id_user' => $this->user ?? Auth::user()->id,
            'fecha_Registro' => now(),
            'id_dest_tipcaja' => $this->destinatarioVisible ? $this->nuevoDestinatario : null,
        ]);

        $producto = Producto::select('id')
                    -> where('id_detalle',$this->detalleId)
                    -> where('descripcion','GENERAL')
                    -> get()
                    -> toarray();

        if ($this->centroDeCostos <> '') {
            Log::info('Paso');
            $centroDeCosts = $this->centroDeCostos;
        } else {
            Log::info('Es nulo');
            $centroDeCosts = null;
        }

        Log::info('Centro de Costos:'.$centroDeCosts);
                    
        DDetalleDocumento::create(['id_referencia' => $nuevoDocumento->id,
                    'orden' => '1',
                    'id_producto' => $producto[0]['id'],
                    'id_tasas' => '1',
                    'cantidad' => '1',
                    'cu' => $this->precio,
                    'total' => $this->precio,
                    'id_centroDeCostos' => $centroDeCosts,]);

        // Registrar log

        Log::info('Documento registrado exitosamente', ['documento_id' => $nuevoDocumento->id]);
           // Llamar a la función para registrar movimientos de caja
        $this->registrarMovimientoCaja($nuevoDocumento->id, $this->docIdent, $this->fechaEmi);
        // Limpiar el formulario
        $this->resetForm();

    
        session()->flash('message', 'Documento registrado con éxito.');

        // Emitir el evento para actualizar la tabla en `TablaDetalleApertura`
        //$this->dispatch('actualizar-tabla-apertura', $this->aperturaId); 

        //$this->dispatch('scroll-up');
         
    }

    public function registrarMovimientoCaja($documentoId, $entidadId, $fechaEmi)
    {
        // Log de variables iniciales
        Log::info('Iniciando registro de movimiento de caja', [
            'documentoId' => $documentoId,
            'entidadId' => $entidadId,
            'tipoDocumento' => $this->tipoDocumento,
            'serieNumero1' => $this->serieNumero1,
            'serieNumero2' => $this->serieNumero2,
            'fechaEmi' => $fechaEmi,
            'familiaId' => $this->familiaId,
            
        ]);
    
        // Determinar si es una transferencia o no
        $lib = ($this->familiaId == '002') ? '1' : '7';
        Log::info('Determinado tipo de libro', ['lib' => $lib]);
    
        // Obtener la cuenta de caja o el ID de cuenta desde Logistica.detalle
        if ($this->familiaId == '001') { 
            $cuentaId = 8; // Transferencias
            Log::info('Cuenta para transferencias asignada', ['cuentaId' => $cuentaId]);
        } else {
            $cuentaDetalle = Detalle::find($this->detalleId);
            $cuentaId = $cuentaDetalle->id_cuenta ?? null; // Cuenta de Logistica.detalle
            Log::info('Cuenta asignada desde Logistica.detalle', ['cuentaId' => $cuentaId]);
        }
    
        $tipoCambio = TipoDeCambioSunat::where('fecha', $this->fechaEmi)->first()->venta ?? 1;
        // Calcular tipo de cambio si la moneda es USD
        if ($this->monedaId == 'USD') {
            $precioConvertido = round($this->precio * $tipoCambio, 2);
            if($this -> validacionDet == '1'){
                $detraConvertido = round($this->montoDetraccion * $tipoCambio, 2);
                $netoConvertido = round($this->montoNeto * $tipoCambio, 2);
            }
            Log::info('Tipo de cambio calculado', [
                'tipoCambio' => $tipoCambio,
                'precioConvertido' => $precioConvertido
            ]);
        } else {
            $precioConvertido = round($this->precio / $tipoCambio, 2);
            if($this -> validacionDet == '1'){
                $detraConvertido = round($this->montoDetraccion / $tipoCambio, 2);
                $netoConvertido = round($this->montoNeto / $tipoCambio, 2);
            }
            Log::info('Precio sin conversión aplicado', ['precioConvertido' => $precioConvertido]);
        }
    
        // Obtener el último número de movimiento
        $ultimoMovimiento = MovimientoDeCaja::where('id_libro', $lib)
            ->orderByRaw('CAST(mov AS UNSIGNED) DESC')
            ->first();
        $nuevoMov = $ultimoMovimiento ? intval($ultimoMovimiento->mov) + 1 : 1;
        Log::info('Nuevo movimiento asignado', ['nuevoMov' => $nuevoMov]);
    
        // Registro en movimientosdecaja para ingresos
            // Crear el primer registro en todos los casos
            MovimientoDeCaja::create([
                'id_libro' => $lib,
                'mov' => $nuevoMov,
                'fec' => $fechaEmi,
                'id_documentos' => $documentoId,
                'id_cuentas' => $cuentaId,
                'id_dh' => $this->tipoDocumento == '07' ? 2 : 1,
                'monto' => ($this->monedaId == "USD") 
                        ? ($this->validacionDet == '1' ? $netoConvertido : $precioConvertido) 
                        : ($this->validacionDet == '1' ? $this->montoNeto : $this->precio),
                'montodo' => ($this->monedaId == "USD") 
                            ? ($this->validacionDet == '1' ? $this->montoNeto : $this->precio) 
                            : ($this->validacionDet == '1' ? $netoConvertido : $precioConvertido),
                'glosa' => $this->observaciones,
            ]);

            // Si $this->toggle es verdadero, crear el segundo registro
            if ($this->validacionDet == '1') {
                MovimientoDeCaja::create([
                    'id_libro' => $lib,
                    'mov' => $nuevoMov,
                    'fec' => $fechaEmi,
                    'id_documentos' => $documentoId,
                    'id_cuentas' => 2,
                    'id_dh' => $this->tipoDocumento == '07' ? 2 : 1,
                    'monto' => $this->monedaId == "USD" ? $detraConvertido : $this->montoDetraccion,
                    'montodo' => $this->monedaId == "USD" ? $this->montoDetraccion : $detraConvertido,
                    'glosa' => $this->observaciones,
                ]);

            // Registrar en el log
            Log::info('Registro de ingresos en movimientosdecaja realizado', [
                'id_documentos' => $documentoId,
                'monto' => $this->validacionDet == '1' ? $netoConvertido : $precioConvertido
            ]);

        }
    
            
        // Confirmación de registro exitoso
        Log::info('Documento y movimiento de caja registrados exitosamente');
        session()->flash('message', 'Documento y movimiento de caja registrados exitosamente.');
        $this->dispatch('cxc-created');
    }


    public function render()
    {
        return view('livewire.registro-documento-cxc');
    }
}
