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



class EdRegistroDocumentosCxp extends Component
{
    public $visible = false;
    public $idcxp;
    public $documentoCxp;

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

    #[On('showEdCxp')]
    public function showEdcxp($idcxp)
    {
        $this->idcxp = $idcxp;
        $this->consultaBD();
        $this->consultaDetallesCxp();
        // Verificar si el tipo de documento es uno de los restringidos
        if (
            $this->documentoCxp &&
            ($this->documentoCxp->tipoDocumento === "Vaucher de Transferencia" ||
                $this->documentoCxp->tipoDocumento === "Comprobante de Anticipo" ||
                $this->documentoCxp->tipoDocumento === "Vaucher de Rendicion")
        ) {
            $this->dispatch('mostrarAlerta');
            $this->visible = false; // No mostrar el componente si es un documento restringido
        } else {
            $this->visible = true; // Mostrar el componente si el documento es válido
        }
    }

    public function consultaBD()
    {
        if ($this->idcxp) {
            $this->documentoCxp = Documento::select(
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
                ->where('documentos.id_tipmov', 2)
                ->where('documentos.id', $this->idcxp)
                ->first();

            Log::info('Resultado de la consulta de documentoCxp:', ['documentoCxp' => $this->documentoCxp]);
        }
    }

    public function consultaDetallesCxp()
    {
        if ($this->idcxp) {
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
                CO1.id_tipmov = 2-- cxc
                AND CO1.id = ?
        ", [$this->idcxp]);
        
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
                                    ->where('id_familias', $this -> familiaId)
                                    ->get();
        
        $this->reset('detalleId'); // Reiniciar detalle
    }


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

    public function mount()
    {
        $this->tipoDocIdentidades = TipoDocumentoIdentidad::whereIn('id', ['1', '6'])->get();
        $this->user = Auth::user()->id;
        $this->loadInitialData();
        $this->consultaDetallesCxp();
    }

    public function loadInitialData()
    {
        $this->familias = Familia::where('id', '<>', '002')->get();
        $this->tasasIgv = TasaIgv::all();
        $this->monedas = TipoDeMoneda::all();
        $this->detalles = Detalle::all();
        $this->CC = CentroDeCostos::all(); 
        $this->tipoDocumentoRef = TipoDeComprobanteDePagoODocumento::all();
    }

    public function submit()
    {
        DB::beginTransaction();
    
        try {
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
    
            // Comprobación de movimientos de caja
            $comprobacion = MovimientoDeCaja::whereIn('id_libro', ['3', '4'])
                ->where('id_documentos', $this->idcxp)
                ->lockForUpdate() // Bloqueo pesimista
                ->get()
                ->toArray();
    
            Log::info(count($comprobacion));
            if (count($comprobacion) <> 0) {
                session()->flash('error', 'No se puede eliminar el documento de caja porque tiene movimientos de caja.');
                DB::rollBack();
                return $this->dispatch('cxp-updated');
            }
    
            // Validar si el precio es 0
            if ($this->precio == 0) {
                session()->flash('error', 'No puede ser el monto cero');
                DB::rollBack();
                return;
            }
    
            if ($this->validacionDet == '1') {
                if ($this->porcentaje == '' || $this->porcentaje == 0) {
                    session()->flash('error', 'No puede ser el monto cero');
                    DB::rollBack();
                    return;
                } elseif ($this->montoDetraccion == '' || $this->montoDetraccion == 0) {
                    session()->flash('error', 'No puede ser el monto cero');
                    DB::rollBack();
                    return;
                }
            }
    
            if ($this->familiaId == '001') {
                if ($this->nuevoDestinatario == '') {
                    session()->flash('error', 'Tiene que tener un destinatario');
                    DB::rollBack();
                    return;
                }
            }
    
            // Insertar o actualizar documento con bloqueo pesimista
            Documento::updateOrCreate(
                ['id' => $this->idcxp], // Condición de búsqueda
                [
                    'id_tipmov' => 2,
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
    
            // Bloquear y borrar registros relacionados
            $datos = $this->borrarRegistrosed($this->idcxp);
            $movlibro = $datos['movlibro'];
    
            // Consultar producto con bloqueo pesimista
            $producto = Producto::select('id')
                ->where('id_detalle', $this->detalleId)
                ->where('descripcion', 'GENERAL')
                ->lockForUpdate() // Bloqueo pesimista
                ->firstOrFail()
                ->toArray();
    
            $centroDeCosts = $this->centroDeCostos !== '' ? $this->centroDeCostos : null;
    
            Log::info('Centro de Costos: ' . $centroDeCosts);
    
            // Crear el detalle del documento
            DDetalleDocumento::create([
                'id_referencia' => $this->idcxp,
                'orden' => '1',
                'id_producto' => $producto['id'],
                'id_tasas' => '1',
                'cantidad' => '1',
                'cu' => $this->precio,
                'total' => $this->precio,
                'id_centroDeCostos' => $centroDeCosts,
            ]);
    
            // Registrar log de éxito
            Log::info('Documento registrado exitosamente', ['documento_id' => $this->idcxp]);
    
            // Registrar movimientos de caja
            $this->registrarMovimientoCaja($this->idcxp, $this->docIdent, $this->fechaEmi, $movlibro);
    
            // Confirmar la transacción
            DB::commit();
    
            // Limpiar el formulario
            $this->resetForm();
            session()->flash('message', 'Documento registrado con éxito.');
            $this->dispatch('cxp-updated');
    
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            Log::error('Error al registrar documento', ['exception' => $e]);
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
            'entidad',
            'id_t10tdocMod',
            'serieMod',
            'numeroMod'
        ]);
    }

    public function borrarRegistrosed($idmov)
    {
        DB::beginTransaction(); // Iniciar la transacción para concurrencia
    
        try {
            // Bloquear la fila de Familia para evitar lecturas concurrentes
            $tipoFamilia = Familia::select('id_tipofamilias')
                ->lockForUpdate() // Bloqueo pesimista
                ->where('id', $this->familiaId)
                ->get()
                ->toArray();
    
            // Verificar si es del tipo de familia que necesita ingresar movimientos
            if ($tipoFamilia[0]['id_tipofamilias'] == '2') {
                $Lib = '2'; 
            }else{
                $Lib = '7';
            }

            Log::info('Datos obtenidos de MovimientoDeCaja:', [
                'familiaId' => $this->familiaId,
                'idmov' => $idmov,
            ]);
    



                // Obtener el movimiento de caja con bloqueo pesimista
                $datos = MovimientoDeCaja::select('mov')
                    ->lockForUpdate() // Bloqueo pesimista
                    ->where('id_documentos', $idmov)
                    ->where('id_libro', $Lib)
                    ->get()
                    ->toArray();


                // Asegurarse de que existan datos
                if (count($datos) > 0) {
                    $data['movlibro'] = $datos[0]['mov'];
                } else {
                    $data['movlibro'] = null; // Si no hay registros, se guarda como null
                }
            
    
            // Eliminar registros relacionados de movimientos de caja
            MovimientoDeCaja::where('id_documentos', $idmov)->delete();
    
            // Eliminar los detalles de documentos relacionados
            DDetalleDocumento::where('id_referencia', $idmov)->delete();
    
            DB::commit(); // Confirmar la transacción
    
            return $data;
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción en caso de error
            Log::error('Error al borrar registros', ['exception' => $e]);
            throw $e; // Relanzar la excepción para manejarla externamente si es necesario
        }
    }
    

    public function registrarMovimientoCaja($documentoId, $entidadId, $fechaEmi, $movLibro)
    {
        DB::beginTransaction(); // Iniciar una transacción
    
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
    
            $tipoFamilia = Familia::select('id_tipofamilias')
            ->lockForUpdate() // Bloqueo pesimista
            ->where('id', $this->familiaId)
            ->get()
            ->toArray();

            // Determinar si es una transferencia o no
            $lib = ($tipoFamilia[0]['id_tipofamilias'] == '2') ? '2' : '7';
            Log::info('Determinado tipo de libro', ['lib' => $lib]);
    
            // Obtener la cuenta de caja o el ID de cuenta desde Logistica.detalle
            if ($this->familiaId == '001') {
                $cuentaId = 8; // Transferencias
                Log::info('Cuenta para transferencias asignada', ['cuentaId' => $cuentaId]);
            } else {
                $cuentaDetalle = Detalle::lockForUpdate()->find($this->detalleId); // Bloqueo pesimista
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
    
    
            // Registro en movimientosdecaja para ingresos
                // Crear el primer registro en todos los casos
                MovimientoDeCaja::create([
                    'id_libro' => $lib,
                    'mov' => $movLibro,
                    'fec' => $fechaEmi,
                    'id_documentos' => $documentoId,
                    'id_cuentas' => $cuentaId,
                    'id_dh' => $this->tipoDocumento == '07' ? 1 : 2,
                    'monto' => ($this->monedaId == "USD") 
                        ? ($this->validacionDet == '1' ? $netoConvertido : $precioConvertido) 
                        : ($this->validacionDet == '1' ? $this->montoNeto : $this->precio),
                    'montodo' => ($this->monedaId == "USD") 
                                ? ($this->validacionDet == '1' ? $this->montoNeto : $this->precio) 
                                : ($this->validacionDet == '1' ? $netoConvertido : $precioConvertido),
                    'glosa' => $this->observaciones,
                ]);
    
                // Si validacionDet es verdadero, crear el segundo registro
                if ($this->validacionDet == '1') {
                    MovimientoDeCaja::create([
                        'id_libro' => $lib,
                        'mov' => $movLibro,
                        'fec' => $fechaEmi,
                        'id_documentos' => $documentoId,
                        'id_cuentas' => 4,
                        'id_dh' => $this->tipoDocumento == '07' ? 1 : 2,
                        'monto' => $this->monedaId == "USD" ? $detraConvertido : $this->montoDetraccion,
                        'montodo' => $this->monedaId == "USD" ? $this->montoDetraccion : $detraConvertido,
                        'glosa' => $this->observaciones,
                    ]);
                }
    
                // Registrar en el log
                Log::info('Registro de ingresos en movimientosdecaja realizado', [
                    'id_documentos' => $documentoId,
                    'monto' => $this->validacionDet == '1' ? $netoConvertido : $precioConvertido
                ]);
            
            // Confirmación de registro exitoso
            Log::info('Documento y movimiento de caja registrados exitosamente');
            session()->flash('message', 'Documento y movimiento de caja registrados exitosamente.');
    
            DB::commit(); // Confirmar la transacción
    
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción en caso de error
            Log::error('Error al registrar movimiento de caja', ['exception' => $e]);
            session()->flash('error', 'Ocurrió un error al registrar el movimiento de caja.');
        }
    }
    



    public function render()
    {
        return view('livewire.ed-registro-documentos-cxp');
    }
}
