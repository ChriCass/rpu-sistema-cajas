<?php

namespace App\Livewire;


use Carbon\Carbon;
use Livewire\Component;
use DateTime;
use Illuminate\Support\Facades\Log;
use App\Models\Documento;
use App\Models\Apertura;
use App\Models\Familia;
use App\Models\SubFamilia;
use App\Models\Detalle;
use App\Models\TasaIgv;
use App\Models\TipoDeMoneda;
use App\Models\TipoDeComprobanteDePagoODocumento;
use App\Models\Entidad;
use App\Models\TipoDocumentoIdentidad;
use App\Models\TipoDeCaja;
use App\Models\TipoDeCambioSunat;
use App\Models\MovimientoDeCaja;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\ApiService;
use App\Models\CentroDeCostos;
use Livewire\Attributes\On;
use App\Models\Producto;
use App\Models\DDetalleDocumento;
use App\Models\Cuenta;

class EdRegistroDocumentosIngreso extends Component
{   
    public $aperturaId;
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
    public $destinatarioVisible = false; // Mostrar u ocultar destinatario
    public $tipoDocDescripcion;
    public $observaciones;
    public $entidad;
    public $nuevoDestinatario;
    public $centroDeCostos; // Abelardo = Recoje el centro de costos
    public $lenIdenId; // Abelardo = recoje el largo del imput
    public $numMov;

    public $familias = []; // Lista de familias
    public $subfamilias = []; // Lista de subfamilias filtradas
    public $detalles = []; // Lista de detalles filtrados
    public $tasasIgv = []; // Lista de tasas de IGV
    public $monedas = []; // Lista de monedas
    public $CC = []; // Abelardo = Lista de Centro de Costos

    public $tipoDocIdentidades;
    public $disableFields = false; // Para manejar el estado de desactivación de campos
    public $disableFieldsEspecial = false; // Para manejar el estado de desactivación de campos
    public $destinatarios;

    public $user;
    public $movimientoCompras;
    public $movimientoCaja;
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
    protected $tasaIgvMapping = [
        '18%' => 0.18,
        '10%' => 0.10,
        'No Gravado' => 0.00,
    ];
    public $cod_operacion;
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
    

    public function mount($aperturaId, ApiService $apiService,$numeroMovimiento)
    {
        $this->aperturaId = $aperturaId;
        $this->apertura = Apertura::findOrFail($aperturaId);
        $this->tipoDocIdentidades = TipoDocumentoIdentidad::whereIn('id', ['1', '6'])->get();
        $this->user = Auth::user()->id;
        $this->loadInitialData();
        $this->tipoCaja = $this->apertura->id_tipo; ////VALOR TIPO CAJA AHORA EN VARIABLE PUBLICA
        $this->apiService = $apiService; // Abelardo = Asigne el servicio inyectado para la api.
        $this->numMov = $numeroMovimiento;
        $this->loadDocumentData($this->numMov);
        Log::info('Valor de tipoCaja: ' . $this->tipoCaja);

    }

    public function loadDocumentData($idMovimiento)
    {
        // Ejecutar la consulta SQL usando el Query Builder de Laravel o SQL raw
        $result = DB::selectOne("
            SELECT 
                CO1.id, 
                familias.id AS familia_id, -- ID de la familia
                subfamilias.id AS subfamilia_id, -- ID de la subfamilia
                CO1.id_detalle AS detalle_id, -- ID del detalle
                CO1.id_t10tdoc AS tipo_documento_id, 
                tabla10_tipodecomprobantedepagoodocumento.descripcion AS tipo_documento_descripcion, 
                CO1.serie, 
                CO1.numero, 
                CO1.id_t02tcom AS tipo_comprobante_id, 
                CO1.id_entidades AS entidad_id, 
                entidades.descripcion AS entidad_descripcion, 
                CO1.id_t04tipmon AS tipo_moneda_id, 
                tasas_igv.tasa AS tasa_igv, 
                DATE_FORMAT(CO1.fechaEmi, '%d/%m/%Y') AS fechaEmision, 
                DATE_FORMAT(CO1.fechaVen, '%d/%m/%Y') AS fechaVencimiento, 
                CO1.observaciones, 
                CO1.id_dest_tipcaja AS tipo_caja_descripcion, 
                CO1.basImp AS base_imponible, 
                CO1.IGV, 
                CO1.noGravadas, 
                CO1.otroTributo, 
                CO1.precio, 
                CO1.detalle_producto, -- Detalle del producto
                CO1.id_centroDeCostos,
                INN1.numero_de_operacion
            FROM 
                (SELECT 
                    documentos.id, 
                    detalle.id_familias, 
                    detalle.id_subfamilia, 
                    detalle.id AS id_detalle, -- Incluimos el ID del detalle
                    documentos.id_t10tdoc, 
                    documentos.id_tipmov,
                    documentos.serie, 
                    documentos.numero, 
                    documentos.id_t02tcom, 
                    documentos.id_entidades, 
                    documentos.id_t04tipmon, 
                    documentos.id_tasasIgv, 
                    documentos.fechaEmi, 
                    documentos.fechaVen, 
                    documentos.observaciones, 
                    documentos.id_dest_tipcaja, 
                    documentos.basImp, 
                    documentos.IGV, 
                    documentos.noGravadas, 
                    documentos.otroTributo, 
                    documentos.precio,
                    detalle.descripcion AS detalle_producto, -- Descripción del producto
                    d_detalledocumentos.id_centroDeCostos
                FROM 
                    documentos 
                LEFT JOIN 
                    d_detalledocumentos ON documentos.id = d_detalledocumentos.id_referencia -- Relación con el detalle de documentos
                LEFT JOIN 
                    l_productos ON d_detalledocumentos.id_producto = l_productos.id -- Relación con los productos
                LEFT JOIN 
                    detalle ON detalle.id = l_productos.id_detalle -- Relación con el detalle
                ) CO1
            LEFT JOIN 
                familias ON CO1.id_familias = familias.id 
            LEFT JOIN 
                subfamilias ON CONCAT(CO1.id_familias, CO1.id_subfamilia) = CONCAT(subfamilias.id_familias, subfamilias.id) 
            LEFT JOIN 
                tabla10_tipodecomprobantedepagoodocumento ON CO1.id_t10tdoc = tabla10_tipodecomprobantedepagoodocumento.id 
            LEFT JOIN 
                entidades ON CO1.id_entidades = entidades.id 
            LEFT JOIN 
                tasas_igv ON CO1.id_tasasIgv = tasas_igv.id 
            LEFT JOIN 
                tipodecaja ON CO1.id_dest_tipcaja = tipodecaja.id 
            LEFT JOIN
                (select distinct id_documentos,numero_de_operacion from movimientosdecaja where id_libro = '3') INN1 on INN1.id_documentos = CO1.id
            WHERE 
                CO1.id_tipmov = 1 -- cxc
                AND CO1.id = ?
        ", [$idMovimiento]);

        // Log del resultado completo de la consulta
        Log::info('Resultado de la consulta SQL:', (array) $result);
        
        // Asignar los resultados a las variables del componente
        $this->familiaId = $result->familia_id; // ID de la familia
        $this->updatedFamiliaId($this->familiaId);
        $this->subfamiliaId = $result->subfamilia_id; // ID de la subfamilia
        $this->updatedSubfamiliaId($this->subfamiliaId);
        $this->detalleId = $result->detalle_id; // ID del detalle
        $this->tasaIgvId = $result->tasa_igv; // Tasa de IGV seleccionada
        $this->monedaId = $result->tipo_moneda_id; // ID de la moneda seleccionada
        $this->tipoDocumento = $result->tipo_documento_id; // ID del tipo de documento seleccionado
        $this->serieNumero1 = $result->serie; // Parte 1 del número de serie
        $this->serieNumero2 = $result->numero; // Parte 2 del número de serie
        $this->tipoDocId = $result->tipo_comprobante_id; // Tipo de documento de identificación
        $this->docIdent = $result->entidad_id; // Documento de identidad
        $this->fechaEmi = Carbon::createFromFormat('d/m/Y', $result->fechaEmision)->format('Y-m-d'); // Fecha de emisión
        $this->fechaVen = Carbon::createFromFormat('d/m/Y', $result->fechaVencimiento)->format('Y-m-d'); // Fecha de emisión; // Fecha de vencimiento
        $this->tipoDocDescripcion = $result->tipo_documento_descripcion; // Descripción del tipo de documento
        $this->observaciones = $result->observaciones; // Observaciones
        $this->entidad = $result->entidad_descripcion; // Descripción de la entidad
        $this->nuevoDestinatario = $result->tipo_caja_descripcion; // Destinatario o tipo de caja
        $this->centroDeCostos = $result->id_centroDeCostos;
        $this->cod_operacion = $result->numero_de_operacion;

        // Variables financieras
        $this->basImp = $result->base_imponible; // Base imponible
        $this->igv = $result->IGV; // IGV
        $this->noGravado = $result->noGravadas; // No gravado
        $this->otrosTributos = $result->otroTributo; // Otros tributos
        $this->precio = $result->precio; // Precio total
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
        $this->subfamilias = SubFamilia::select('id as ic','desripcion')->get()->toArray();
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
        $this->checkFieldState(); // Verificar el estado de los campos
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

    // Método que verifica el estado de los campos según la familia seleccionada
    public function checkFieldState()
    {
        switch ($this->familiaId) {
            case '001': // TRANSFERENCIAS
                $this->disableFields = true;
                $this->disableFieldsEspecial = true;
                $this->destinatarioVisible = true;
                $this->setDefaultTransferenciasValues();
                break;

            case '003': // ANTICIPOS
                $this->disableFields = true;
                $this->disableFieldsEspecial = false;
                $this->destinatarioVisible = false;
                $this->setDefaultAnticiposValues();
                break;

            case '004': // RENDICIONES
                $this->disableFields = true;
                $this->disableFieldsEspecial = false;
                $this->destinatarioVisible = false;
                $this->setDefaultRendicionesValues(); // Función especial para rendiciones
                break;

            default:
                // Habilitar todos los campos y ocultar destinatario
                $this->disableFields = false;
                $this->disableFieldsEspecial = false;
                $this->destinatarioVisible = false;
                $this->resetForm();
                break;
        }
    }
    // Establecer valores por defecto para rendiciones
    public function setDefaultRendicionesValues()
    {
        $subfamilia = SubFamilia::select('id as ic','desripcion')
                            -> where('desripcion', 'GENERAL')-> get() ->toarray();
        $this->subfamiliaId = $subfamilia[0]['ic'];
        
        $detalle = Detalle::where('descripcion', 'RENDICIONES POR PAGAR')->first(); // Encontrar el detalle correcto

        if ($detalle) {
            $this->detalleId = $detalle->id;
            Log::info('Detalle encontrado: ', ['id' => $detalle->id, 'descripcion' => $detalle->descripcion]);
        } else {
            Log::warning('No se encontró el detalle con la descripción: RENDICIONES POR PAGAR');
        }

        $this->tasaIgvId = TasaIgv::where('tasa', 'No Gravado')->first()->tasa;
        $this->monedaId = TipoDeMoneda::where('id', 'PEN')->first()->id;
        $this->tipoDocumento = '77'; // Código de documento para rendiciones
        $tipoComprobante = TipoDeComprobanteDePagoODocumento::where('id', $this->tipoDocumento)->first();
        $this->tipoDocDescripcion = $tipoComprobante ? $tipoComprobante->descripcion : null;

        $this->serieNumero1 = '0000';

        // Obtener el siguiente número de serie
        $ultimoDocumento = Documento::where('id_t10tdoc', $this->tipoDocumento)
            ->where('serie', $this->serieNumero1)
            ->orderByRaw('CAST(numero AS UNSIGNED) DESC')
            ->first();

        if ($ultimoDocumento) {
            $this->serieNumero2 = intval($ultimoDocumento->numero) + 1;
        } else {
            $this->serieNumero2 = '1';
        }

        $this->destinatarios = TipoDeCaja::all();
        

        $fecha = (new DateTime($this->apertura->fecha))->format('Y-m-d');
        Log::info('Fecha formateada: ', ['fecha' => $fecha]);
        $this->fechaEmi = $fecha;
        $this->fechaVen = $fecha;
    }


    // Establecer valores por defecto para transferencias
    public function setDefaultTransferenciasValues()
    {
        $subfamilia = SubFamilia::select('id as ic','desripcion')
                            -> where('desripcion', 'GENERAL')-> get() ->toarray();
        $this->subfamiliaId = $subfamilia[0]['ic'];

        $detalle = Detalle::where('id', '001000001')->first(); // Aquí obtienes el objeto completo

        if (!empty($detalle)) {
            // Si se encuentra el detalle, asignamos el ID y generamos un log
            $this->detalleId = $detalle->id; // Asignamos el ID
            Log::info('Detalle encontrado: ', ['id' => $detalle->id, 'descripcion' => $detalle->descripcion]); // Generamos el log con los detalles correctos
        } else {
            // Si no se encuentra el detalle, generamos un log de advertencia
            Log::warning('No se encontró el detalle con la descripción: TRANSFERENCIAS ENTRE CAJAS');
        }

        // Encontrar la tasa de IGV por la descripcion seleccionada
        $tasaIgv = TasaIgv::where('tasa', 'No Gravado')->first();

        if ($tasaIgv) {
            $this->tasaIgvId = $tasaIgv->tasa; // Usamos el id internamente si es necesario
            Log::info('Tasa IGV encontrada: ', ['id' => $tasaIgv->id, 'tasa' => $tasaIgv->tasa]);
        } else {
            Log::warning('No se encontró la Tasa IGV con el valor: ' . $this->tasaIgvDescripcion);
        }

        $this->monedaId = TipoDeMoneda::where('id', 'PEN')->first()->id;
        $this->tipoDocumento = '74';
        $tipoComprobante = TipoDeComprobanteDePagoODocumento::where('id', $this->tipoDocumento)->first();
        $this->tipoDocDescripcion = $tipoComprobante ? $tipoComprobante->descripcion : null;

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

        $this->tipoDocId = '6'; // RUC
        $this->docIdent = '20606566558'; // Valor por defecto

        $entidad = Entidad::where('id', $this->docIdent)->first();
        $this->entidad = $entidad->descripcion;
        $fecha = (new DateTime($this->apertura->fecha))->format('Y-m-d');
        Log::info('Fecha formateada: ', ['fecha' => $fecha]);
        $this->fechaEmi = $fecha;
        $this->fechaVen = $fecha;
    }

    // Establecer valores por defecto para anticipos
    public function setDefaultAnticiposValues()
    {
        // Obtener subfamilia por descripcion 'GENERAL'
        $subfamilia = SubFamilia::select('id as ic','desripcion')
                            -> where('desripcion', 'GENERAL')-> get() ->toarray();
        $this->subfamiliaId = $subfamilia[0]['ic'];

        // Obtener detalle por descripcion 'ANTICIPOS A CLIENTES'
        $detalle = Detalle::where('descripcion', 'ANTICIPOS A CLIENTES')->first();

        if (!empty($detalle)) {
            $this->detalleId = $detalle->id; // Asignamos el ID
            Log::info('Detalle encontrado: ', ['id' => $detalle->id, 'descripcion' => $detalle->descripcion]);
        } else {
            Log::warning('No se encontró el detalle con la descripción: ANTICIPOS A CLIENTES');
        }
        $tasaIgv = TasaIgv::where('tasa', 'No Gravado')->first();

        if ($tasaIgv) {
            // Asignar el ID de la tasa y generar un log
            $this->tasaIgvId = $tasaIgv->tasa;
            Log::info('Tasa IGV encontrada: ', ['id' => $tasaIgv->id, 'tasa' => $tasaIgv->tasa]);
        } else {
            // Si no se encuentra la tasa, generar un log de advertencia
            Log::warning('No se encontró la Tasa IGV con el valor: No Gravado');
        }

        $this->monedaId = TipoDeMoneda::where('id', 'PEN')->first()->id;
        $this->tipoDocumento = '76';
        $tipoComprobante = TipoDeComprobanteDePagoODocumento::where('id', $this->tipoDocumento)->first();
        $this->tipoDocDescripcion = $tipoComprobante ? $tipoComprobante->descripcion : null;
        $this->serieNumero1 = '0000';
        // Obtener el siguiente número de serie utilizando el modelo Documento
        $ultimoDocumento = Documento::where('id_t10tdoc', $this->tipoDocumento) // Tipo de documento 74
            ->where('serie', $this->serieNumero1) // Serie 0000
            ->orderByRaw('CAST(numero AS UNSIGNED) DESC') // Ordenar por número de documento de manera descendente
            ->first(); // Obtener el primer registro (el número más alto)

        // Asignar el siguiente número de serie
        if ($ultimoDocumento) {
            $this->serieNumero2 = intval($ultimoDocumento->numero) + 1; // Incrementar el número en 1
        } else {
            $this->serieNumero2 = '1'; // Si no hay registros, empezar con 1
        }

        $fecha = (new DateTime($this->apertura->fecha))->format('Y-m-d');
        Log::info('Fecha formateada: ', ['fecha' => $fecha]);
        $this->fechaEmi = $fecha;
        $this->fechaVen = $fecha;
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
            'tasaIgvId',
            'observaciones',
            'entidad'
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
            'observaciones' => 'required|string|max:500',
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
    
        // Validación de destinatario si la familiaId es '001'
        if ($this->familiaId == '001') { 
            if ($this->nuevoDestinatario == '') {
                session()->flash('error', 'Tiene que tener un destinatario');
                return;
            }
        }
    
        // Iniciar una transacción para asegurar la atomicidad de las operaciones
        DB::beginTransaction();
    
        try {
            // Insertar o actualizar el documento con bloqueo pesimista
            $documento = Documento::updateOrCreate(
                ['id' => $this->numMov], // Condición de búsqueda
                [
                    'id_tipmov' => 1,
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
                    'detraccion' => $this->detraccion ?? 0,
                    'montoNeto' => $this->montoNeto ?? 0,
                    'id_t10tdocMod' => $this->id_t10tdocMod ?? null,
                    'observaciones' => $this->observaciones,
                    'serieMod' => $this->serieMod ?? null,
                    'numeroMod' => $this->numeroMod ?? null,
                    'id_user' => $this->user ?? Auth::user()->id,
                    'fecha_Registro' => now(),
                    'id_dest_tipcaja' => $this->destinatarioVisible ? $this->nuevoDestinatario : null,
                ]
            );
    
            // Borrar registros si es necesario y obtener datos
            $datos = $this->borrarRegistrosed($this->numMov);
    
            $movcaja = count($datos) == '1' ? $datos['movcaja'] : $datos['movcaja'];
            $movlibro = count($datos) == '1' ? null : $datos['movlibro'];
    
            // Obtener el producto con bloqueo pesimista
            $producto = Producto::select('id')
                ->where('id_detalle', $this->detalleId)
                ->where('descripcion', 'GENERAL')
                ->lockForUpdate() // Bloqueo pesimista
                ->firstOrFail();
    
            // Verificar si se asignó centro de costos
            $centroDeCosts = $this->centroDeCostos != '' ? $this->centroDeCostos : null;
    
            Log::info('Centro de Costos:' . $centroDeCosts);
    
            // Insertar el detalle del documento
            DDetalleDocumento::create([
                'id_referencia' => $this->numMov,
                'orden' => '1',
                'id_producto' => $producto->id,
                'id_tasas' => '1',
                'cantidad' => '1',
                'cu' => $this->precio,
                'total' => $this->precio,
                'id_centroDeCostos' => $centroDeCosts,
            ]);
    
            // Registrar log
            Log::info('Documento registrado exitosamente', ['documento_id' => $this->numMov]);
    
            // Llamar a la función para registrar movimientos de caja
            $this->registrarMovimientoCaja($this->numMov, $this->docIdent, $this->fechaEmi, $movcaja, $movlibro);
    
            // Confirmar la transacción
            DB::commit();
    
            // Limpiar el formulario y emitir eventos
            session()->flash('message', 'Documento registrado con éxito.');
            $this->dispatch('actualizar-tabla-apertura', $this->aperturaId); 
            $this->dispatch('scroll-up');
    
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            Log::error('Error al registrar el documento', ['exception' => $e]);
            session()->flash('error', 'Ocurrió un error al registrar el documento.');
        }
    }
    

    public function borrarRegistrosed($idmov)
    {
        DB::beginTransaction();
    
        try {
            $data = [];
    
            // Si familiaId es '002', obtener movlibro con bloqueo pesimista
            if ($this->familiaId == '002') {
                $datos = MovimientoDeCaja::select('mov')
                    ->where('id_documentos', $idmov)
                    ->where('id_libro', '1')
                    ->lockForUpdate() // Bloqueo pesimista
                    ->get()
                    ->toArray();
    
                if (!empty($datos)) {
                    $data['movlibro'] = $datos[0]['mov'];
                }else{
                    $ultimoMovimiento = MovimientoDeCaja::where('id_libro', '1')
                        ->lockForUpdate() // Bloqueo pesimista
                        ->orderByRaw('CAST(mov AS UNSIGNED) DESC')
                        ->first();
                    $nuevoMov = $ultimoMovimiento ? intval($ultimoMovimiento->mov) + 1 : 1;
                    $data['movlibro'] = $nuevoMov;
                }
            }
    
            // Obtener movcaja con bloqueo pesimista
            $datos = MovimientoDeCaja::select('mov')
                ->distinct()
                ->where('id_documentos', $idmov)
                ->where('id_libro', '3')
                ->lockForUpdate() // Bloqueo pesimista
                ->get()
                ->toArray();
    
            if (!empty($datos)) {
                $data['movcaja'] = $datos[0]['mov'];
            }
    
            // Eliminar registros de MovimientoDeCaja y DDetalleDocumento
            MovimientoDeCaja::where('id_documentos', $idmov)->delete();
            DDetalleDocumento::where('id_referencia', $idmov)->delete();
    
            // Confirmar la transacción
            DB::commit();
    
            return $data;
    
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            Log::error('Error al borrar registros', ['exception' => $e]);
            session()->flash('error', 'Ocurrió un error al borrar los registros.');
            return [];
        }
    }
    

    public function registrarMovimientoCaja($documentoId, $entidadId, $fechaEmi, $movcaja, $movlibro)
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
                'familiaId' => $this->familiaId
            ]);
    
            // Determinar si es una transferencia o no
            $lib = ($this->familiaId == '001') ? '5' : '1';
            Log::info('Determinado tipo de libro', ['lib' => $lib]);
    
            // Obtener la cuenta de caja o el ID de cuenta desde Logistica.detalle con bloqueo pesimista
            if ($this->familiaId == '001') { 
                $cuentaId = 9; // Transferencias
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
                Log::info('Tipo de cambio calculado', [
                    'tipoCambio' => $tipoCambio,
                    'precioConvertido' => $precioConvertido
                ]);
            } else {
                $precioConvertido = null;
                Log::info('Precio sin conversión aplicado', ['precioConvertido' => $precioConvertido]);
            }
    
            // Registro en movimientosdecaja para ingresos
            if ($this->familiaId == '002') { // INGRESOS
                MovimientoDeCaja::create([
                    'id_libro' => $lib,
                    'mov' => $movlibro,
                    'fec' => $fechaEmi,
                    'id_documentos' => $documentoId,
                    'id_cuentas' => 1,
                    'id_dh' => 1,
                    'monto' => $this->monedaId == "USD"? $precioConvertido:$this->precio,
                    'montodo' => $this->monedaId == "USD"? $this->precio:$precioConvertido,
                    'glosa' => $this->observaciones,
                ]);
                Log::info('Registro de ingresos en movimientosdecaja realizado', [
                    'id_documentos' => $documentoId,
                    'monto' => $precioConvertido
                ]);
            }
    
            // Obtener y registrar la apertura relacionada con bloqueo pesimista
            $apertura = Apertura::lockForUpdate() // Bloqueo pesimista
                ->where('numero', $this->apertura->numero)
                ->where('id_tipo',$this->apertura->id_tipo)
                ->whereHas('mes', function ($query) {
                    $query->where('descripcion', $this->apertura->mes->descripcion);
                })
                ->where('año', $this->apertura->año)
                ->first();
    
            if ($apertura) {
                // Obtener cuenta de caja relacionada con bloqueo pesimista
                $descaja = TipoDeCaja::lockForUpdate() // Bloqueo pesimista
                    ->select('descripcion')
                    ->where('id', $this->tipoCaja)
                    ->get()
                    ->toArray();
    
                $cuenta = Cuenta::lockForUpdate() // Bloqueo pesimista
                    ->select('id')
                    ->where('descripcion', $descaja[0]['descripcion'])
                    ->get()
                    ->toArray();
    
                // La transacción en caja
                MovimientoDeCaja::create([
                    'id_libro' => 3,
                    'id_apertura' => $apertura->id,
                    'mov' => $movcaja,
                    'fec' => $apertura->fecha,
                    'id_documentos' => $documentoId,
                    'id_cuentas' => $cuenta[0]['id'], // Abelardo = que se jale del select de la apertura
                    'id_dh' => 1,
                    'monto' => $this->monedaId == "USD"? $precioConvertido:$this->precio,
                    'montodo' => $this->monedaId == "USD"? $this->precio:$precioConvertido,
                    'glosa' => $this->observaciones,
                    'numero_de_operacion' => $this->cod_operacion ?? null,
                ]);
    
                // El pago de documento
                MovimientoDeCaja::create([
                    'id_libro' => 3,
                    'id_apertura' => $apertura->id,
                    'mov' => $movcaja,
                    'fec' => $apertura->fecha,
                    'id_documentos' => $documentoId,
                    'id_cuentas' => $cuentaId,
                    'id_dh' => 2,
                    'monto' => $this->monedaId == "USD"? $precioConvertido:$this->precio,
                    'montodo' => $this->monedaId == "USD"? $this->precio:$precioConvertido,
                    'glosa' => $this->observaciones,
                    'numero_de_operacion' => $this->cod_operacion ?? null,
                ]);
    
                Log::info('Registro de movimientos relacionado con apertura realizado', [
                    'id_documentos' => $documentoId,
                    'id_apertura' => $apertura->id,
                    'nuevoCaja' => $movcaja
                ]);
            }
    
            // Confirmar la transacción
            DB::commit();
    
            // Confirmación de registro exitoso
            Log::info('Documento y movimiento de caja registrados exitosamente');
            session()->flash('message', 'Documento y movimiento de caja registrados exitosamente.');
    
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            Log::error('Error al registrar movimiento de caja', ['exception' => $e]);
            session()->flash('error', 'Ocurrió un error al registrar movimiento de caja.');
        }
    }
    

    
    public function render()
    {
        return view('livewire.ed-registro-documentos-ingreso');
    }
}
