<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use App\Models\Documento;
use Illuminate\Support\Facades\Log;
use App\Models\TipoDeComprobanteDePagoODocumento;
use App\Models\TipoDocumentoIdentidad;
use Illuminate\Support\Facades\Auth;
use App\Models\TasaIgv;
use App\Models\TipoDeMoneda;
use App\Models\Detalle;
use App\Models\Familia;
use App\Models\SubFamilia;
use Illuminate\Support\Carbon;
use App\Models\CentroDeCostos;
use App\Models\TipoDeCambioSunat;
use App\Models\MovimientoDeCaja;
use App\Models\Producto;
use App\Models\DDetalleDocumento;
use App\Models\Cuenta;
use App\Models\Apertura;
use App\Services\ApiService;


class EdRegistroDocumentosCxc extends Component
{
    public $visible = false;
    public $idcxc;
    public $documentoCxc;

    // Variables del documento
    public $familiaId;
    public $subfamiliaId;
    public $detalleId;
    public $tipoDocumento;
    public $serieNumero1;
    public $serieNumero2;
    public $tipoDocId;
    public $docIdent;
    public $fechaEmi;
    public $fechaVen;
    public $tipoDocDescripcion;
    public $observaciones;
    public $entidad;
    public $nuevoDestinatario;
    public $centroDeCostos;
    public $monedaId;
    public $tasaIgvId;
    public $tipoDocumentoRef;

    // Variables para la consulta secundaria
    public $basImp;
    public $igv = 0;
    public $otrosTributos = 0;
    public $noGravado = 0;
    public $precio = 0;
    public $detraccion;
    public $porcetajeDetraccion;
    public $disableFieldsEspecial;
    public $lenIdenId;
    public  $CC;
    public $PruebaArray;
    // Variables de interfaz
    public $familias = [];
    public $subfamilias = [];
    public $detalles = [];
    public $tasasIgv = [];
    public $monedas = [];
    public $disableFields = false;
    public $tipoDocIdentidades;
    public $destinatarioVisible = false;
    public $user;
    public $id_t10tdocMod;
    public $serieMod;
    public $numeroMod;


    ///tiene detraccion

    public $toggle = false;
    public $montoDetraccion;
    public $montoNeto;
    public $porcentaje;
    public $validacionDet;
    public $cod_operacion;
    protected $apiService; 


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

    #[On('showEdCxc')]
    public function showEdcxc($idcxc)
    {
        $this->idcxc = $idcxc;
        $this->consultaBD();
        $this->consultaDetallesCxc();
        // Verificar si el tipo de documento es uno de los restringidos
        if (
            $this->documentoCxc &&
            ($this->documentoCxc->tipoDocumento === "Vaucher de Transferencia" ||
                $this->documentoCxc->tipoDocumento === "Comprobante de Anticipo" ||
                $this->documentoCxc->tipoDocumento === "Vaucher de Rendicion")
        ) {
            $this->dispatch('mostrarAlerta');
            $this->visible = false; // No mostrar el componente si es un documento restringido
        } else {
            $this->visible = true; // Mostrar el componente si el documento es válido
        }
    }

    public function consultaBD()
    {
        if ($this->idcxc) {
            $this->documentoCxc = Documento::select(
                'documentos.id',
                DB::raw("DATE_FORMAT(fechaEmi, '%d/%m/%Y') AS fechaEmi"),
                'tabla10_tipodecomprobantedepagoodocumento.descripcion AS tipoDocumento',
                'documentos.id_entidades as id_entidades',
                'entidades.descripcion AS entidadDescripcion',
                'documentos.serie',
                'documentos.numero',
                'documentos.id_t04tipmon',
                'tasas_igv.tasa',
                'documentos.precio',
                'users.name AS usuario'
            )
                ->leftJoin('entidades', 'documentos.id_entidades', '=', 'entidades.id')
                ->leftJoin('users', 'documentos.id_user', '=', 'users.id')
                ->leftJoin('tabla10_tipodecomprobantedepagoodocumento', 'documentos.id_t10tdoc', '=', 'tabla10_tipodecomprobantedepagoodocumento.id')
                ->leftJoin('tasas_igv', 'documentos.id_tasasIgv', '=', 'tasas_igv.id')
                ->where('documentos.id_tipmov', 1)
                ->where('documentos.id', $this->idcxc)
                ->first();
        }
    }

    public function consultaDetallesCxc()
    {
        if ($this->idcxc) {
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
                CO1.detraccion,
                CO1.montoNeto,
                CO1.id_t10tdocMod,
                CO1.serieMod,
                CO1.numeroMod
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
                    d_detalledocumentos.id_centroDeCostos,
                    detraccion,
                    montoNeto,
                    id_t10tdocMod,
                    serieMod,
                    numeroMod
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
            WHERE 
                CO1.id_tipmov = 1
                AND CO1.id = ?
        ", [$this->idcxc]);
            Log::info('Resultado de la consulta de documentoCxc:', ['documentoCxc' => $result]);
            // Asignar los resultados a las variables del componente
            $this->familiaId = $result->familia_id; // ID de la familia
            Log::info('log para la familia', ['familia' => $this->familiaId]);
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

            // Variables financieras
            $this->basImp = $result->base_imponible; // Base imponible
            $this->igv = $result->IGV; // IGV
            $this->noGravado = $result->noGravadas; // No gravado
            $this->otrosTributos = $result->otroTributo; // Otros tributos
            $this->precio = $result->precio; // Precio total
            $this->montoDetraccion = $result->detraccion;
            $this->id_t10tdocMod = $result->id_t10tdocMod;
            $this->serieMod = $result->serieMod;
            $this->numeroMod = $result->numeroMod;
            if($result->montoNeto>0){
                $this->montoNeto = $result->montoNeto;
                $this->porcentaje = round(($this->montoDetraccion/$result->precio) * 100,0);
                $this->toggle = true;
                if($this->toggle == '1'){
                    $this -> validacionDet = '1';
                }else{
                    $this -> validacionDet = '0';
                };
            }
        }
    }

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
            ->where('id_familias', $this->familiaId)
            ->get();

        $this->reset('detalleId'); // Reiniciar detalle
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
    

    public function buscarDescripcionTipoDocumento()
    {
        // Buscar la descripción del tipo de documento
        $tipoComprobante = TipoDeComprobanteDePagoODocumento::where('id', $this->tipoDocumento)->first();

        if ($tipoComprobante) {
            $this->tipoDocDescripcion = $tipoComprobante->descripcion;
        } else {
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


    public function loadInitialData()
    {
        $familiasBase = Familia::where('id', 'like', '0%')->get();

        $familiasBalance = Familia::where('id', 'like', '1%')
            ->where('id_tipofamilias', '=', '1')
            ->get();

        $this->familias = $familiasBase->merge($familiasBalance);
        $this->tasasIgv = TasaIgv::all();
        $this->monedas = TipoDeMoneda::all();
        $this->detalles = Detalle::all();
        $this->CC = CentroDeCostos::all();
        $this->tipoDocumentoRef = TipoDeComprobanteDePagoODocumento::all();
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
            'entidad',
            'id_t10tdocMod',
            'serieMod',
            'numeroMod'
        ]);
    }


    public function submit()
{
    DB::beginTransaction(); // Iniciar una transacción para la concurrencia

    try {
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

        // Comprobar si el documento tiene movimientos de caja
        $comprobacion = MovimientoDeCaja::whereIn('id_libro', ['3', '4'])
                        ->where('id_documentos', $this->idcxc)
                        ->lockForUpdate() // Bloqueo pesimista
                        ->get()
                        ->toArray();

        if (count($comprobacion) !== 0) {
            session()->flash('error', 'No se puede eliminar el documento de caja porque tiene movimientos de caja.');
            DB::rollBack(); // Revertir la transacción si hay un error
            return $this->dispatch('cxc-updated');
        }

        // Validar si el precio es 0
        if ($this->precio == 0) {
            session()->flash('error', 'No puede ser el monto cero');
            DB::rollBack(); // Revertir la transacción si hay un error
            return;
        }

        if ($this->validacionDet == '1') {
            if ($this->porcentaje == '' || $this->porcentaje == 0) {
                session()->flash('error', 'No puede ser el monto cero');
                DB::rollBack(); // Revertir la transacción si hay un error
                return;
            } elseif ($this->montoDetraccion == '' || $this->montoDetraccion == 0) {
                session()->flash('error', 'No puede ser el monto cero');
                DB::rollBack(); // Revertir la transacción si hay un error
                return;
            }
        }

        if ($this->familiaId == '001') { // Validar destinatario
            if ($this->nuevoDestinatario == '') {
                session()->flash('error', 'Tiene que tener un destinatario');
                DB::rollBack(); // Revertir la transacción si hay un error
                return;
            }
        }

        // Actualizar o crear el documento
        Documento::updateOrCreate(
            ['id' => $this->idcxc], // Condición de búsqueda
            [
                'id_tipmov' => 1, // Valor fijo 1
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
            ]
        );

        // Borrar registros existentes relacionados
        $datos = $this->borrarRegistrosed($this->idcxc);
        $movlibro = $datos['movlibro'];

        // Procesar el producto
        $producto = Producto::lockForUpdate() // Bloqueo pesimista para el producto
                    ->select('id')
                    ->where('id_detalle', $this->detalleId)
                    ->where('descripcion', 'GENERAL')
                    ->get()
                    ->toArray();

        if ($this->centroDeCostos <> '') {
            Log::info('Paso');
            $centroDeCosts = $this->centroDeCostos;
        } else {
            Log::info('Es nulo');
            $centroDeCosts = null;
        }

        Log::info('Centro de Costos:'.$centroDeCosts);

        // Crear el detalle del documento
        DDetalleDocumento::create([
            'id_referencia' => $this->idcxc,
            'orden' => '1',
            'id_producto' => $producto[0]['id'],
            'id_tasas' => '1',
            'cantidad' => '1',
            'cu' => $this->precio,
            'total' => $this->precio,
            'id_centroDeCostos' => $centroDeCosts,
        ]);

        // Registrar log de documento exitoso
        Log::info('Documento registrado exitosamente', ['documento_id' => $this->idcxc]);

        // Registrar movimientos de caja
        $this->registrarMovimientoCaja($this->idcxc, $this->docIdent, $this->fechaEmi, $movlibro);

        // Limpiar el formulario
        $this->resetForm();

        DB::commit(); // Confirmar la transacción

        session()->flash('message', 'Documento registrado con éxito.');
        $this->dispatch('cxc-updated');
    } catch (\Exception $e) {
        DB::rollBack(); // Revertir la transacción en caso de error
        Log::error('Error al registrar el documento', ['exception' => $e]);
        session()->flash('error', 'Ocurrió un error al registrar el documento.');
    }
}


public function borrarRegistrosed($idmov)
{
    DB::beginTransaction(); // Iniciar una transacción

    try {
        $data = []; // Inicializar variable para almacenar datos

        // Aplicar bloqueo pesimista para evitar conflictos de concurrencia
        if ($this->familiaId == '002') {
            $lib = '1';
        }else{
            $lib = '7';
        }

            $datos = MovimientoDeCaja::select('mov')
                ->where('id_documentos', $idmov)
                ->where('id_libro', $lib)
                ->lockForUpdate() // Bloquear fila para evitar conflictos
                ->get()
                ->toArray();

            if (!empty($datos)) {
                $data['movlibro'] = $datos[0]['mov'];
            }
        

        // Borrar movimientos de caja relacionados al documento
        MovimientoDeCaja::where('id_documentos', $idmov)
            ->lockForUpdate() // Bloquear filas para evitar conflictos
            ->delete();

        // Borrar detalles del documento
        DDetalleDocumento::where('id_referencia', $idmov)
            ->lockForUpdate() // Bloquear filas para evitar conflictos
            ->delete();

        DB::commit(); // Confirmar la transacción

        return $data;

    } catch (\Exception $e) {
        DB::rollBack(); // Revertir la transacción en caso de error
        Log::error('Error al borrar registros', ['exception' => $e]);
        throw $e; // Lanzar la excepción para que el flujo la maneje
    }
}


public function registrarMovimientoCaja($documentoId, $entidadId, $fechaEmi, $movLibro)
{
    DB::beginTransaction(); // Iniciar una transacción para asegurar atomicidad

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
            if ($this->validacionDet == '1') {
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

        // Aplicar bloqueo pesimista en la tabla de movimientos para evitar conflictos de concurrencia
        MovimientoDeCaja::lockForUpdate()->where('id_documentos', $documentoId)->first();

        // Registro en movimientosdecaja para ingresos

            // Crear el primer registro en todos los casos
            MovimientoDeCaja::create([
                'id_libro' => $lib,
                'mov' => $movLibro,
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

            // Si $this->validacionDet es verdadero, crear el segundo registro
            if ($this->validacionDet == '1') {
                MovimientoDeCaja::create([
                    'id_libro' => $lib,
                    'mov' => $movLibro,
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

        DB::commit(); // Confirmar la transacción

        // Confirmación de registro exitoso
        Log::info('Documento y movimiento de caja registrados exitosamente');
        session()->flash('message', 'Documento y movimiento de caja registrados exitosamente.');
    } catch (\Exception $e) {
        DB::rollBack(); // Revertir la transacción en caso de error
        Log::error('Error al registrar movimientos de caja', ['exception' => $e]);
        session()->flash('error', 'Ocurrió un error al registrar el documento y el movimiento de caja.');
        throw $e; // Lanzar la excepción para que se maneje en el flujo de la aplicación
    }
}



    public function render()
    {
        return view('livewire.ed-registro-documentos-cxc');
    }
}
