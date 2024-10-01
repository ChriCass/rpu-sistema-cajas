<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Familia;
use App\Models\TasaIgv;
use App\Models\TipoDeMoneda;
use App\Models\Detalle;
use App\Models\SubFamilia;
use App\Models\TipoDocumentoIdentidad;
use DateTime;

use App\Models\Documento;
use Illuminate\Support\Facades\Log;
use App\Models\Entidad;
 
use App\Services\ApiService;
use App\Models\TipoDeCaja;
use App\Models\TipoDeCambioSunat;
use App\Models\MovimientoDeCaja;
use App\Models\Producto;
use App\Models\CentroDeCostos;
use App\Models\DDetalleDocumento;
use App\Models\Cuenta;


use App\Models\TipoDeComprobanteDePagoODocumento;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

class RegistroDocumentosCxp extends Component
{

    public $familiaId; // ID de la familia seleccionada
    public $subfamiliaId; // ID de la subfamilia seleccionada
    public $detalleId; // ID del detalle seleccionado
    public $tasaIgvId; // ID de la tasa de IGV seleccionada
    public $monedaId; // ID de la moneda seleccionada
    public $tipoDocumento; // ID del tipo de documento seleccionado
    public $serieNumero1; // Parte 1 del número de serie
    public $serieNumero2; // Parte 2 del número de serie
    public $tipoDocId; // Tipo de documento de identificación
    public $docIdent; // Documento de identidad
    public $fechaEmi; // Fecha de emisión
    public $fechaVen; // Fecha de vencimiento
    public $tipoDocDescripcion;
    public $observaciones;
    public $entidad;
    public $nuevoDestinatario;
    public $centroDeCostos; // Abelardo = Recoje el centro de costos

    public $familias = []; // Lista de familias
    public $subfamilias = []; // Lista de subfamilias filtradas
    public $detalles = []; // Lista de detalles filtrados
    public $tasasIgv = []; // Lista de tasas de IGV
    public $monedas = []; // Lista de monedas
    public $disableFields = false;
    public $tipoDocIdentidades;
    public $visible;
    public $destinatarioVisible = false; // Mostrar u ocultar destinatario
    public $disableFieldsEspecial = false; 
    public $user;

    public $detraccion;
    public $porcetajeDetraccion;
    public $CC = [];
    // Abelardo = Recoje el centro de costos
    public $lenIdenId;
    public $basImp = 0;
    public $igv = 0;
    public $otrosTributos = 0;
    public $noGravado = 0;
    public $precio = 0;
    protected $apiService; 
    public $PruebaArray = ""; 

     ///tiene detraccion

     public $toggle = false;
     public $montoDetraccion;
     public $montoNeto;
     public $porcentaje;
     public $validacionDet;
 
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

    #[On('mostrarDocumentosCxp')]
    public function mostrar()
    {
        $this->visible = true;
    }

    public function calculateIgv()
    {
        // Ensure the baseImponible is not null or zero
        if (!$this->basImp || !$this->tasaIgvId) {
            return;
        }

        // Calculate IGV based on the selected tasa
        switch ($this->tasaIgvId) {
            case '18%':
                $this->igv = round($this->basImp * 0.18, 2);
                break;
            case '10%':
                $this->igv = round($this->basImp * 0.10, 2);
                break;
            case 'No Gravado':
            default:
                $this->igv = 0; // No IGV applied
                break;
        }
    }

    // Function to calculate the total price dynamically
    public function calculatePrecio()
    {
        if (is_numeric($this->basImp) && is_numeric($this->igv) && is_numeric($this->otrosTributos) && is_numeric($this->noGravado)) {
            $this->precio = round($this->basImp + $this->igv + $this->otrosTributos + $this->noGravado, 2);
        }
    }

    // Livewire hooks for triggering the functions when fields are updated
    public function updatedBasImp()
    {
        // Calculate IGV based on the updated base imponible
        $this->calculateIgv();

        // Calculate the total price
        $this->calculatePrecio();
    }

    public function updatedTasaIgvId()
    {
        // Recalculate IGV based on the updated tasa
        $this->calculateIgv();

        // Recalculate the total price
        $this->calculatePrecio();
    }

    public function updatedOtrosTributos()
    {
        // Recalculate the total price whenever otros tributos is updated
        $this->calculatePrecio();
    }

    public function updatedNoGravado()
    {
        // Recalculate the total price whenever no gravado is updated
        $this->calculatePrecio();
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
            session()->flash('error', 'Descripción no encontrada');
        }
    }


    public function mount(ApiService $apiService)
    {
        $this->tipoDocIdentidades = TipoDocumentoIdentidad::whereIn('id', ['1', '6'])->get();
        $this->user = Auth::user()->id;
        $this->loadInitialData();
        $this->apiService = $apiService;
    }

    public function hydrate(ApiService $apiService) // Abelardo = Hidrate la inyecion del servicio puesto que no esta funcionando el servicio, con esta opcion logre pasar el service por las diferentes funciones
    {
        $this->apiService = $apiService;
    }


    public function updatedFamiliaId($value)
    {
        // Actualizar las subfamilias según la familia seleccionada
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

    public function loadInitialData()
    {
        $this->familias = Familia::where('id', 'not like', '0%')->get();
        $this->tasasIgv = TasaIgv::all();
        $this->monedas = TipoDeMoneda::all();
        $this->detalles = Detalle::all();
        $this->CC = CentroDeCostos::all();
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

    public function submit()
    {
        // Validar los campos obligatorios
        $this->validate([
            'tipoDocumento' => 'required',
            'tipoDocDescripcion' => 'required',
            'serieNumero1' => 'required',
            'serieNumero2' => 'required',
            'tipoDocId' => 'required',
            'docIdent' => 'required',
            'entidad' => 'required',
            'monedaId' => 'required',
            'tasaIgvId' => 'required',
            'fechaEmi' => 'required|date',
            'fechaVen' => 'required|date',
            'basImp' => 'required|numeric|min:0',
            'igv' => 'required|numeric|min:0',
            'noGravado' => 'required|numeric|min:0',
            'precio' => 'required|numeric|min:0.01',
            'observaciones' => 'nullable|string|max:500',
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
    
        // Validación de detracción
        if ($this->validacionDet == '1') {
            if ($this->porcentaje == '' || $this->porcentaje == 0) {
                session()->flash('error', 'No puede ser el monto cero');
                return;
            } elseif ($this->montoDetraccion == '' || $this->montoDetraccion == 0) {
                session()->flash('error', 'No puede ser el monto cero');
                return;
            }
        }
    
        // Validación del destinatario si la familiaId es '001'
        if ($this->familiaId == '001') {
            if ($this->nuevoDestinatario == '') {
                session()->flash('error', 'Tiene que tener un destinatario');
                return;
            }
        }
    
        // Iniciar una transacción para asegurar la atomicidad de las operaciones
        DB::beginTransaction();
    
        try {
            // Validar si el documento ya está registrado con bloqueo pesimista
            $documentoExistente = Documento::where('id_entidades', $this->docIdent)
                ->where('id_t10tdoc', $this->tipoDocumento)
                ->where('serie', $this->serieNumero1)
                ->where('numero', $this->serieNumero2)
                ->where('id_tipmov', '2')
                ->lockForUpdate() // Bloqueo pesimista
                ->first();
    
            if ($documentoExistente) {
                session()->flash('error', 'Documento ya registrado');
                DB::rollBack();
                return;
            }
    
            Log::info('Prueba: ' . $this->montoDetraccion);
    
            // Insertar el nuevo documento
            $nuevoDocumento = Documento::create([
                'id_tipmov' => 2, // cxp
                'fechaEmi' => $this->fechaEmi,
                'fechaVen' => $this->fechaVen,
                'id_t10tdoc' => $this->tipoDocumento,
                'id_t02tcom' => $this->tipoDocId,
                'id_entidades' => $this->docIdent,
                'id_t04tipmon' => $this->monedaId,
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
    
            // Obtener el producto con bloqueo pesimista
            $producto = Producto::select('id')
                ->where('id_detalle', $this->detalleId)
                ->where('descripcion', 'GENERAL')
                ->lockForUpdate() // Bloqueo pesimista
                ->first();
    
            if ($this->centroDeCostos != '') {
                Log::info('Paso');
                $centroDeCosts = $this->centroDeCostos;
            } else {
                Log::info('Es nulo');
                $centroDeCosts = null;
            }
    
            Log::info('Centro de Costos: ' . $centroDeCosts);
    
            // Insertar el detalle del documento
            DDetalleDocumento::create([
                'id_referencia' => $nuevoDocumento->id,
                'orden' => '1',
                'id_producto' => $producto->id,
                'id_tasas' => '1',
                'cantidad' => '1',
                'cu' => $this->precio,
                'total' => $this->precio,
                'id_centroDeCostos' => $centroDeCosts,
            ]);
    
            // Registrar log
            Log::info('Documento registrado exitosamente', ['documento_id' => $nuevoDocumento->id]);
    
            // Registrar movimientos de caja
            $this->registrarMovimientoCaja($nuevoDocumento->id, $this->docIdent, $this->fechaEmi);
    
            // Confirmar la transacción
            DB::commit();
    
            // Limpiar el formulario
            $this->resetForm();
    
            session()->flash('message', 'Documento registrado con éxito.');
    
            // Emitir el evento para actualizar la tabla
            //$this->dispatch('actualizar-tabla-apertura', $this->aperturaId);
            //$this->dispatch('scroll-up');
    
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            Log::error('Error al registrar el documento', ['exception' => $e]);
            session()->flash('error', 'Ocurrió un error al registrar el documento.');
        }
    }
    

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
            'entidad'
        ]);
    }

    public function registrarMovimientoCaja($documentoId, $entidadId, $fechaEmi)
    {
        // Iniciar una transacción para asegurar la atomicidad
        DB::beginTransaction();
    
        try {
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
            $lib = ($this->familiaId == '001') ? '5' : '2';
            Log::info('Determinado tipo de libro', ['lib' => $lib]);
    
            // Obtener la cuenta de caja o el ID de cuenta desde Logistica.detalle con bloqueo pesimista
            if ($this->familiaId == '001') { 
                $cuentaId = 8; // Transferencias
                Log::info('Cuenta para transferencias asignada', ['cuentaId' => $cuentaId]);
            } else {
                $cuentaDetalle = Detalle::lockForUpdate()->find($this->detalleId);
                $cuentaId = $cuentaDetalle->id_cuenta ?? null; // Cuenta de Logistica.detalle
                Log::info('Cuenta asignada desde Logistica.detalle', ['cuentaId' => $cuentaId]);
            }
    
            // Calcular tipo de cambio si la moneda es USD con bloqueo pesimista
            if ($this->monedaId == 'USD') {
                $tipoCambio = TipoDeCambioSunat::lockForUpdate() // Bloqueo pesimista
                    ->where('fecha', $this->fechaEmi)
                    ->first()->venta ?? 1;
    
                $precioConvertido = round($this->precio * $tipoCambio, 2);
                if ($this->validacionDet == '1') {
                    $detraConvertido = round($this->montoDetraccion * $tipoCambio, 2);
                    $netoConvertido = round($this->montoNeto * $tipoCambio, 2);
                }
                Log::info('Tipo de cambio calculado', [
                    'tipoCambio' => $tipoCambio,
                    'precioConvertido' => $precioConvertido
                ]);
            } else {
                $precioConvertido = $this->precio;
                if ($this->validacionDet == '1') {
                    $detraConvertido = $this->montoDetraccion;
                    $netoConvertido = $this->montoNeto;
                }
                Log::info('Precio sin conversión aplicado', ['precioConvertido' => $precioConvertido]);
            }
    
            // Obtener el último número de movimiento con bloqueo pesimista
            $ultimoMovimiento = MovimientoDeCaja::where('id_libro', $lib)
                ->lockForUpdate() // Bloqueo pesimista
                ->orderByRaw('CAST(mov AS UNSIGNED) DESC')
                ->first();
            $nuevoMov = $ultimoMovimiento ? intval($ultimoMovimiento->mov) + 1 : 1;
            Log::info('Nuevo movimiento asignado', ['nuevoMov' => $nuevoMov]);
    
            // Obtener tipo de familia con bloqueo pesimista
            $tipoFamilia = Familia::select('id_tipofamilias')
                ->where('id', $this->familiaId)
                ->lockForUpdate() // Bloqueo pesimista
                ->first();
    
            // Registro en movimientos de caja para ingresos
            if ($tipoFamilia && $tipoFamilia->id_tipofamilias == '2') {
                // Crear el primer registro en todos los casos
                MovimientoDeCaja::create([
                    'id_libro' => $lib,
                    'mov' => $nuevoMov,
                    'fec' => $fechaEmi,
                    'id_documentos' => $documentoId,
                    'id_cuentas' => $cuentaId,
                    'id_dh' => 2,
                    'monto' => $this->validacionDet == '1' ? $netoConvertido : $precioConvertido,
                    'montodo' => null,
                    'glosa' => $this->observaciones,
                ]);
    
                // Si validacionDet es verdadero, crear el segundo registro
                if ($this->validacionDet == '1') {
                    MovimientoDeCaja::create([
                        'id_libro' => $lib,
                        'mov' => $nuevoMov,
                        'fec' => $fechaEmi,
                        'id_documentos' => $documentoId,
                        'id_cuentas' => 4,
                        'id_dh' => 2,
                        'monto' => $detraConvertido,
                        'montodo' => null,
                        'glosa' => $this->observaciones,
                    ]);
                }
    
                // Registrar en el log
                Log::info('Registro de ingresos en movimientos de caja realizado', [
                    'id_documentos' => $documentoId,
                    'monto' => $this->validacionDet == '1' ? $netoConvertido : $precioConvertido
                ]);
            }
    
            // Confirmar la transacción
            DB::commit();
    
            // Confirmación de registro exitoso
            Log::info('Documento y movimiento de caja registrados exitosamente');
            session()->flash('message', 'Documento y movimiento de caja registrados exitosamente.');
    
            $this->dispatch('cxp-created');
    
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            Log::error('Error al registrar movimiento de caja', ['exception' => $e]);
            session()->flash('error', 'Ocurrió un error al registrar movimiento de caja.');
        }
    }
    


    public function render()
    {
        return view('livewire.registro-documentos-cxp');
    }
}
