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
    public  $monedaId;
    public        $tasaIgvId;

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
                CO1.montoNeto
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
                    montoNeto
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
                tipoDeCaja ON CO1.id_dest_tipcaja = tipoDeCaja.id 
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


    public function calculateIgv()
    {
        // Cálculo del IGV en función de la tasa seleccionada
        if (!$this->basImp || !$this->tasaIgvId) {
            return;
        }

        switch ($this->tasaIgvId) {
            case '18%':
                $this->igv = round($this->basImp * 0.18, 2);
                break;
            case '10%':
                $this->igv = round($this->basImp * 0.10, 2);
                break;
            case 'No Gravado':
            default:
                $this->igv = 0; // No se aplica IGV
                break;
        }
    }

    public function calculatePrecio()
    {
        // Calcular el precio total dinámicamente
        if (is_numeric($this->basImp) && is_numeric($this->igv) && is_numeric($this->otrosTributos) && is_numeric($this->noGravado)) {
            $this->precio = round($this->basImp + $this->igv + $this->otrosTributos + $this->noGravado, 2);
        }
    }

    public function updatedBasImp()
    {
        // Recalcular IGV y precio cuando la base imponible cambie
        $this->calculateIgv();
        $this->calculatePrecio();
    }

    public function updatedTasaIgvId()
    {
        // Recalcular IGV y precio cuando cambie la tasa de IGV
        $this->calculateIgv();
        $this->calculatePrecio();
    }

    public function updatedOtrosTributos()
    {
        // Recalcular el precio total cuando cambie "otros tributos"
        $this->calculatePrecio();
    }

    public function updatedNoGravado()
    {
        // Recalcular el precio total cuando cambie "no gravado"
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
        $this->familias = Familia::where('id', 'not like', '0%')->get();
        $this->tasasIgv = TasaIgv::all();
        $this->monedas = TipoDeMoneda::all();
        $this->detalles = Detalle::all();
        $this->CC = CentroDeCostos::all(); 
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
            'observaciones' => 'nullable|string|max:500', // TextBox29
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


        Documento::updateOrCreate(
            ['id' => $this -> idcxp], // Condición de búsqueda
            [
                'id_tipmov' => 2, // Valor fijo 2
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

        $datos = $this->borrarRegistrosed($this -> idcxp);
        $movlibro = $datos['movlibro'];

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
                    
        DDetalleDocumento::create(['id_referencia' => $this -> idcxp,
                    'orden' => '1',
                    'id_producto' => $producto[0]['id'],
                    'id_tasas' => '1',
                    'cantidad' => '1',
                    'cu' => $this->precio,
                    'total' => $this->precio,
                    'id_centroDeCostos' => $centroDeCosts,]);

        // Registrar log

        Log::info('Documento registrado exitosamente', ['documento_id' => $this -> idcxp]);
           // Llamar a la función para registrar movimientos de caja
        $this->registrarMovimientoCaja($this -> idcxp, $this->docIdent, $this->fechaEmi,$movlibro);
        // Limpiar el formulario
        $this->resetForm();

    
        session()->flash('message', 'Documento registrado con éxito.');
        $this->dispatch('cxc-updated');
        // Emitir el evento para actualizar la tabla en `TablaDetalleApertura`
        //$this->dispatch('actualizar-tabla-apertura', $this->aperturaId); 

        //$this->dispatch('scroll-up');
         
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

    public function borrarRegistrosed($idmov){
        $tipoFamilia = Familia::select('id_tipofamilias')
                    ->where('id',$this->familiaId)
                    ->get()
                    ->toarray();

        // Registro en movimientosdecaja para ingresos
        if ($tipoFamilia[0]['id_tipofamilias'] == '2'){
            $datos = MovimientoDeCaja::select('mov')
                    ->where('id_documentos',$idmov)
                    ->where('id_libro','2')
                    ->get()
                    ->toarray();
            $data['movlibro'] = $datos[0]['mov'];
        }
        
        MovimientoDeCaja::where('id_documentos',$idmov)->delete();
        DDetalleDocumento::where('id_referencia',$idmov)->delete();
        return $data;

    }

    public function registrarMovimientoCaja($documentoId, $entidadId, $fechaEmi, $movLibro)
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
        $lib = ($this->familiaId == '001') ? '5' : '2';
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
    
        // Calcular tipo de cambio si la moneda es USD
        if ($this->monedaId == 'USD') {
            $tipoCambio = TipoDeCambioSunat::where('fecha', $this->fechaEmi)->first()->venta ?? 1;
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
            $precioConvertido = $this->precio;
            if($this -> validacionDet == '1'){
                $detraConvertido = $this->montoDetraccion;
                $netoConvertido = $this->montoNeto;
            }
            Log::info('Precio sin conversión aplicado', ['precioConvertido' => $precioConvertido]);
        }
    
        $tipoFamilia = Familia::select('id_tipofamilias')
                    ->where('id',$this->familiaId)
                    ->get()
                    ->toarray();
    
        // Registro en movimientosdecaja para ingresos
        if ($tipoFamilia[0]['id_tipofamilias'] == '2') {
            // Crear el primer registro en todos los casos
            MovimientoDeCaja::create([
                'id_libro' => $lib,
                'mov' => $movLibro,
                'fec' => $fechaEmi,
                'id_documentos' => $documentoId,
                'id_cuentas' => $cuentaId,
                'id_dh' => 2,
                'monto' => $this->validacionDet == '1' ? $netoConvertido : $precioConvertido,
                'montodo' => null,
                'glosa' => $this->observaciones,
            ]);

            // Si $this->toggle es verdadero, crear el segundo registro
            if ($this->validacionDet == '1') {
                MovimientoDeCaja::create([
                    'id_libro' => $lib,
                    'mov' => $movLibro,
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
            Log::info('Registro de ingresos en movimientosdecaja realizado', [
                'id_documentos' => $documentoId,
                'monto' => $this->validacionDet == '1' ? $netoConvertido : $precioConvertido
            ]);

        }
    
            
        // Confirmación de registro exitoso
        Log::info('Documento y movimiento de caja registrados exitosamente');
        session()->flash('message', 'Documento y movimiento de caja registrados exitosamente.');
    }



    public function render()
    {
        return view('livewire.ed-registro-documentos-cxp');
    }
}
