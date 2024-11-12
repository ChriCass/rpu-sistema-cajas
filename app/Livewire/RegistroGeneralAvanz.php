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
use App\Models\Cuenta;
use App\Models\TipoDocumentoIdentidad;
use App\Services\RegistroDocAvanzService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
    protected $RegistroDocAvanzService;
    public $cod_operacion;
    public $cuentas;
    public $cuenta;
    public $IdDocumento;


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

    public function updatedtipoDocId($value)
    {
        if ($value === '1') {;
            $this->lenIdenId = 8;
            $this->docIdent = "";
        } else {
            $this->lenIdenId = 11;
            $this->docIdent = "";
        };
    }


    public function hydrate(ApiService $apiService, RegistroDocAvanzService $RegistroDocAvanzService) // Abelardo = Hidrate la inyecion del servicio puesto que no esta funcionando el servicio, con esta opcion logre pasar el service por las diferentes funciones
    {
        $this->apiService = $apiService;
        $this->RegistroDocAvanzService = $RegistroDocAvanzService;
    }
    public function EnterRuc()
    { //Abelardo = Evento enter para RUC
        if ($this->tipoDocId <> '') {
            $data = $this->apiService->REntidad($this->tipoDocId, $this->docIdent);
            if ($data['success'] == '1') {
                $this->entidad = $data['desc'];
            } else {
                session()->flash('error', $data['desc']);
                $this->docIdent = '';
                $this->entidad = '';
            }
        } else {
            session()->flash('error', 'Elige un Tip de Indentidad');
            $this->docIdent = '';
            $this->entidad = '';
        }
    }

    public function buscarDescripcionTipoDocumento()
    {
        // Buscar el tipo de documento en la base de datos
        $tipoComprobante = TipoDeComprobanteDePagoODocumento::where('id', $this->tipoDocumento)->first();
        $this->apertura = Apertura::findOrFail($this->aperturaId);
        // Si se encuentra el tipo de documento, actualizamos la descripción
        if ($tipoComprobante) {
            $this->tipoDocDescripcion = $tipoComprobante->descripcion;
            if ($this->tipoDocumento == '75') {
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
            } else {
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
        $this->cuentas = Cuenta::whereIn('id_tcuenta', [2, 3])->get();
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

    public function cargarDocumentos($IdDocumento){
        // Mejorar a Services
        $result = DB::selectOne("
            SELECT 
                CO1.id, 
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
                INN1.id_cuentas
            FROM 
                (SELECT 
                    documentos.id, 
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
                    documentos.precio
                FROM 
                    documentos 
                ) CO1
            LEFT JOIN 
                tabla10_tipodecomprobantedepagoodocumento ON CO1.id_t10tdoc = tabla10_tipodecomprobantedepagoodocumento.id 
            LEFT JOIN 
                entidades ON CO1.id_entidades = entidades.id 
            LEFT JOIN 
                tasas_igv ON CO1.id_tasasIgv = tasas_igv.id 
            LEFT JOIN 
                tipoDeCaja ON CO1.id_dest_tipcaja = tipoDeCaja.id 
            LEFT JOIN
				(select id_documentos,id_cuentas from movimientosdecaja where id_libro in ('1','2')) INN1 on CO1.id = INN1.id_documentos
            WHERE 
                 CO1.id = ?
        ", [$IdDocumento]);

        // Log del resultado completo de la consulta
        Log::info('Resultado de la consulta SQL:', (array) $result);
        
        // Asignar los resultados a las variables del componente
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
        $this->cuenta = $result->id_cuentas;

        // Variables financieras
        $this->basImp = $result->base_imponible; // Base imponible
        $this->igv = $result->IGV; // IGV
        $this->noGravado = $result->noGravadas; // No gravado
        $this->otrosTributos = $result->otroTributo; // Otros tributos
        $this->precio = $result->precio; // Precio total


    $productos = DB::table('d_detalledocumentos as d')
        ->select(
            'd.id_producto',
            DB::raw("INN1.descripcion"),
            'd.observaciones',
            'd.id_tasas',
            'd.cantidad',
            'd.cu',
            'd.total',
            't.descripcion as CC'
        )
        ->leftJoin(
            DB::raw("(select l.id, concat(l.descripcion, '/', d.descripcion, '/', f.descripcion) as descripcion 
                    from l_productos l
                    left join detalle d on l.id_detalle = d.id
                    left join familias f on f.id = d.id_familias) as INN1"),
            'd.id_producto', '=', 'INN1.id'
        )
        ->leftJoin('t_centrodecostos as t', 'd.id_centroDeCostos', '=', 't.id')
        ->where('d.id_referencia', '=', $IdDocumento)
        ->get();

        

        $productosProcesados = [];

        foreach ($productos as $pro) {
            $pr = [];  // Crear un nuevo array para cada producto
            $pr['codigoProducto'] = $pro->id_producto;
            $pr['productoSeleccionado'] = $pro->descripcion;
            $pr['observacion'] = $pro->observaciones;
            $pr['cantidad'] = $pro->cantidad;
            $pr['precioUnitario'] = $pro->cu;
            $pr['total'] = $pro->total;
            $pr['tasaImpositiva'] = $pro->id_tasas;
            $pr['CC'] = $pro->CC;

            $productosProcesados[] = $pr;
        }

        // Ahora $productosProcesados contiene todos los productos procesados.
        $this->productos = $productosProcesados;

    }

    public function mount($aperturaId)
    {
        $this->aperturaId = $aperturaId;
        $this->apertura = Apertura::findOrFail($this->aperturaId);
        $this->origen = request()->get('origen', 'ingreso'); // Default a 'ingreso'
        $this->IdDocumento = request()->get('numeroMovimiento');
        $this->loadInitialData();
        if(!empty($this->IdDocumento)){
            $this->cargarDocumentos($this->IdDocumento);
            Session::put("productos_{$this->origen}", $this->productos);
        }else{
            $this->productos = null;
            Session::put("productos_{$this->origen}", $this->productos);
        }
        
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



    public function submit()
    {
        // Validar los datos del formulario
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
            'cuenta' => 'required',
            'cod_operacion' => 'nullable',
        ], [
            'required' => 'El campo es obligatorio',
            'numeric' => 'Debe ser un valor numérico',
            'min' => 'El valor debe ser mayor a :min',
        ]);

        // Validación adicional en el componente
        if ($this->precio == 0) {
            session()->flash('error', 'No puede ser el monto cero');
            return;
        }

        Log::info("La Lista de productos es:",$this->productos);

        // Preparar los datos para el servicio
        $data = [
            'tipoDocumento' => $this->tipoDocumento,
            'tipoDocDescripcion' => $this->tipoDocDescripcion,
            'serieNumero1' => $this->serieNumero1,
            'serieNumero2' => $this->serieNumero2,
            'tipoDocId' => $this->tipoDocId,
            'docIdent' => $this->docIdent,
            'entidad' => $this->entidad,
            'monedaId' => $this->monedaId,
            'tasaIgvId' => $this->tasaIgvId,
            'fechaEmi' => $this->fechaEmi,
            'fechaVen' => $this->fechaVen,
            'basImp' => $this->basImp,
            'igv' => $this->igv,
            'noGravado' => $this->noGravado,
            'precio' => $this->precio,
            'observaciones' => $this->observaciones,
            'user' => $this->user ?? Auth::user()->id,
            'productos' => $this->productos,
            'apertura' => [
                'numero' => $this->apertura->numero,
                'id_tipo' => $this->apertura->id_tipo,
                'mes' => ['descripcion' => $this->apertura->mes->descripcion],
                'año' => $this->apertura->año,
            ],
            'origen' => $this->origen,
            'cuenta' => $this->cuenta,
            'cod_operacion' => $this->cod_operacion,
            'idDocumento' => $this->IdDocumento,
        ];

        // Llamar al servicio para guardar el documento
         
        $result = $this->RegistroDocAvanzService->guardarDocumento($data);

        // Manejar la respuesta del servicio
        if (isset($result['success'])) {
            session()->flash('message', $result['success']);
            return $this->redirect(route('apertura.edit', ['aperturaId' => $this->aperturaId]), navigate: true);
        } else {
            session()->flash('error', $result['error']);
        }
        
    }


    public function render()
    {
        return view('livewire.registro-general-avanz')->layout('layouts.app');
    }
}
