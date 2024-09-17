<?php

namespace App\Livewire;

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

class EdRegistroDocumentosEgreso extends Component
{   
    public $numMov;


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
    public $detraccion;

    public $familias = []; // Lista de familias
    public $subfamilias = []; // Lista de subfamilias filtradas
    public $detalles = []; // Lista de detalles filtrados
    public $tasasIgv = []; // Lista de tasas de IGV
    public $monedas = []; // Lista de monedas

    public $tipoDocIdentidades;
    public $disableFields = false; // Para manejar el estado de desactivación de campos
    public $destinatarios;

    public $user;

    /////

    public $apertura;

    public $basImp = 0;
    public $igv = 0;
    public $otrosTributos = 0;
    public $noGravado = 0;
    public $precio = 0;

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

    public function mount($numeroMovimiento)
    {
        $this->numMov = $numeroMovimiento;
        $this->user = Auth::user()->id;
        $this->loadInitialData();
        $this->loadDocumentData($this->numMov);
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
            tipoDeCaja.descripcion AS tipo_caja_descripcion, 
            CO1.basImp AS base_imponible, 
            CO1.IGV, 
            CO1.noGravadas, 
            CO1.otroTributo, 
            CO1.precio, 
            CO1.detalle_producto -- Detalle del producto
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
                detalle.descripcion AS detalle_producto -- Descripción del producto
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
            CO1.id_tipmov = 2 --  cxp
            AND CO1.id = ?
    ", [$idMovimiento]);

    // Log del resultado completo de la consulta
    Log::info('Resultado de la consulta SQL:', (array) $result);
     
    // Asignar los resultados a las variables del componente
    $this->familiaId = $result->familia_id; // ID de la familia
    $this->subfamiliaId = (int) $result->subfamilia_id; // ID de la subfamilia

        // Log del subfamilia_id
        Log::info('Subfamilia ID:', ['subfamilia_id' => $this->subfamiliaId]);
    $this->detalleId = $result->detalle_id; // ID del detalle
    $this->tasaIgvId = $result->tasa_igv; // Tasa de IGV seleccionada
    $this->monedaId = $result->tipo_moneda_id; // ID de la moneda seleccionada
    $this->tipoDocumento = $result->tipo_documento_id; // ID del tipo de documento seleccionado
    $this->serieNumero1 = $result->serie; // Parte 1 del número de serie
    $this->serieNumero2 = $result->numero; // Parte 2 del número de serie
    $this->tipoDocId = $result->tipo_comprobante_id; // Tipo de documento de identificación
    $this->docIdent = $result->entidad_id; // Documento de identidad
    $this->fechaEmi = $result->fechaEmision; // Fecha de emisión
    $this->fechaVen = $result->fechaVencimiento; // Fecha de vencimiento
    $this->tipoDocDescripcion = $result->tipo_documento_descripcion; // Descripción del tipo de documento
    $this->observaciones = $result->observaciones; // Observaciones
    $this->entidad = $result->entidad_descripcion; // Descripción de la entidad
    $this->nuevoDestinatario = $result->tipo_caja_descripcion; // Destinatario o tipo de caja

    // Variables financieras
    $this->basImp = $result->base_imponible; // Base imponible
    $this->igv = $result->IGV; // IGV
    $this->noGravado = $result->noGravadas; // No gravado
    $this->otrosTributos = $result->otroTributo; // Otros tributos
    $this->precio = $result->precio; // Precio total
}

    public function loadInitialData()
    {
        $this->familias = Familia::where('id', '<>', '002')->get();
        $this->tasasIgv = TasaIgv::all();
        $this->monedas = TipoDeMoneda::all();
        $this->detalles = Detalle::all();
        $this->subfamilias = SubFamilia::all();
    }

    public function buscarDescripcionTipoDocumento()
    {
        // Buscar el tipo de documento en la base de datos
        $tipoComprobante = TipoDeComprobanteDePagoODocumento::where('id', $this->tipoDocumento)->first();

        // Si se encuentra el tipo de documento, actualizamos la descripción
        if ($tipoComprobante) {
            $this->tipoDocDescripcion = $tipoComprobante->descripcion;
        } else {
            // Si no se encuentra, puedes asignar un mensaje de error o dejar vacío
            $this->tipoDocDescripcion = 'Descripción no encontrada';
        }
    }

    public function updatedFamiliaId($value)
    {
        // Actualizar las subfamilias según la familia seleccionada
        $this->subfamilias = SubFamilia::where('id_familias', $value)->get();
        $this->reset('subfamiliaId', 'detalleId'); // Reiniciar las selecciones
        $this->checkFieldState(); // Verificar el estado de los campos
    }

    // Método que se ejecuta cuando se selecciona una subfamilia
    public function updatedSubfamiliaId($value)
    {
        // Filtrar los detalles según la subfamilia seleccionada
        $this->detalles = Detalle::where('id_subfamilia', $value)->get();
        $this->reset('detalleId'); // Reiniciar detalle
    }

    public function checkFieldState()
    {
        switch ($this->familiaId) {
            case '001': // TRANSFERENCIAS
                $this->disableFields = true;
                $this->destinatarioVisible = true;
                $this->setDefaultTransferenciasValues();
                break;

            case '003': // ANTICIPOS
                $this->disableFields = true;
                $this->destinatarioVisible = true;
                $this->setDefaultAnticiposValues();
                break;

            case '004': // RENDICIONES
                $this->disableFields = true;
                $this->destinatarioVisible = true;
                $this->setDefaultRendicionesValues(); // Función especial para rendiciones
                break;

            default:
                // Habilitar todos los campos y ocultar destinatario
                $this->disableFields = false;
                $this->destinatarioVisible = false;
                $this->resetForm();
                break;
        }
    }

    public function setDefaultRendicionesValues()
    {
        $this->subfamiliaId = SubFamilia::where('desripcion', 'GENERAL')->first()->id;

        $detalle = Detalle::where('descripcion', 'RENDICIONES POR COBRAR')->first(); // Encontrar el detalle correcto

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

        $this->tipoDocId = '6'; // RUC
        $this->docIdent = '20606566558';

        $entidad = Entidad::where('id', $this->docIdent)->first();
        $this->entidad = $entidad ? $entidad->descripcion : null;

        $fecha = (new DateTime($this->apertura->fecha))->format('Y-m-d');
        Log::info('Fecha formateada: ', ['fecha' => $fecha]);
        $this->fechaEmi = $fecha;
        $this->fechaVen = $fecha;
    }

    public function setDefaultTransferenciasValues()
    {
        $this->subfamiliaId = SubFamilia::where('desripcion', 'GENERAL')->first()->id;

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

    public function setDefaultAnticiposValues()
    {
        // Obtener subfamilia por descripcion 'GENERAL'
        $this->subfamiliaId = SubFamilia::where('desripcion', 'GENERAL')->first()->id;

        // Obtener detalle por descripcion 'ANTICIPOS A CLIENTES'
        $detalle = Detalle::where('descripcion', 'ANTICIPOS DE PROVEEDORES')->first();

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

        $this->tipoDocId = '6';
        $this->docIdent = '20606566558';
        $entidad = Entidad::where('id', $this->docIdent)->first();
        $this->entidad = $entidad->descripcion;
        $fecha = (new DateTime($this->apertura->fecha))->format('Y-m-d');
        Log::info('Fecha formateada: ', ['fecha' => $fecha]);
        $this->fechaEmi = $fecha;
        $this->fechaVen = $fecha;
    }

    public function resetForm()
    {
        $this->reset([
            'subfamiliaId',
            'entidad',
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
            'tasaIgvId'
        ]);

        $this->disableFields = false;
    }

/**
    public function registrarMovimientoCaja($documentoId, $entidadId, $fechaEmi)
    {
        // Log de variables iniciales
        Log::info('Iniciando registro de movimiento de caja para egresos', [
            'documentoId' => $documentoId,
            'entidadId' => $entidadId,
            'tipoDocumento' => $this->tipoDocumento,
            'serieNumero1' => $this->serieNumero1,
            'serieNumero2' => $this->serieNumero2,
            'fechaEmi' => $fechaEmi,
            'familiaId' => $this->familiaId
        ]);
    
        // Determinar si es una transferencia o no
        if ($this->familiaId != 'TRANSFERENCIAS') {
            $lib = "2"; // Egresos
            $cuentaDetalle = Detalle::find($this->detalleId);
            $cuentaId = $cuentaDetalle->id_cuenta ?? null; // Cuenta extraída de Logistica.detalle
            Log::info('Cuenta obtenida de Logistica.detalle', ['cuentaId' => $cuentaId]);
        } else {
            $lib = "5"; // Transferencias
            $cuentaId = 9; // Cuenta para transferencias
            Log::info('Transferencia detectada, cuenta asignada', ['cuentaId' => $cuentaId]);
        }
    
        // Si la moneda es USD, aplicar tipo de cambio
        if ($this->monedaId == 'USD') {
            $tipoCambio = TipoDeCambioSunat::where('fecha', $this->fechaEmi)->first()->venta ?? 1;
            $precioConvertido = round($this->precio * $tipoCambio, 2);
            Log::info('Tipo de cambio aplicado', ['tipoCambio' => $tipoCambio, 'precioConvertido' => $precioConvertido]);
        } else {
            $precioConvertido = $this->precio;
            Log::info('Precio sin conversión aplicado', ['precioConvertido' => $precioConvertido]);
        }
    
        // Obtener el último número de movimiento de caja
        $ultimoMovimiento = MovimientoDeCaja::where('id_libro', $lib)
            ->orderByRaw('CAST(mov AS UNSIGNED) DESC')
            ->first();
        $nuevoMov = $ultimoMovimiento ? intval($ultimoMovimiento->mov) + 1 : 1;
        Log::info('Nuevo movimiento de caja asignado', ['nuevoMov' => $nuevoMov]);
    
        // Determinar si es necesario registrar movimientos específicos
        $tipoFamilia = Familia::where('descripcion', $this->familiaId)->first()->id_tipofamilias ?? null;
    
        if ($this->familiaId != 'TRANSFERENCIAS' && $this->familiaId != 'ANTICIPOS' && $this->familiaId != 'RENDICIONES') {
            if ($tipoFamilia == '2') {
                MovimientoDeCaja::create([
                    'id_libro' => $lib,
                    'mov' => $nuevoMov,
                    'fec' => $fechaEmi,
                    'id_documentos' => $documentoId,
                    'id_cuentas' => 3,
                    'id_dh' => 2, // Crédito para egresos
                    'monto' => $precioConvertido,
                    'montodo' => null,
                    'glosa' => $this->observaciones,
                ]);
                Log::info('Movimiento de caja registrado', [
                    'id_documentos' => $documentoId,
                    'monto' => $precioConvertido
                ]);
            }
        }
    
        // Registrar la apertura
        $apertura = Apertura::where('numero', $this->apertura->numero)
            ->whereHas('mes', function ($query) {
                $query->where('descripcion', $this->apertura->mes->descripcion);
            })
            ->where('año', $this->apertura->año)
            ->first();
    
        if ($apertura) {
            $ultimoMovimientoApertura = MovimientoDeCaja::where('id_apertura', $apertura->id)
                ->orderByRaw('CAST(mov AS UNSIGNED) DESC')
                ->first();
            $nuevoMovApertura = $ultimoMovimientoApertura ? intval($ultimoMovimientoApertura->mov) + 1 : 1;
    
            MovimientoDeCaja::create([
                'id_libro' => 3, // Registro de apertura en libro 3
                'id_apertura' => $apertura->id,
                'mov' => $nuevoMovApertura,
                'fec' => $fechaEmi,
                'id_documentos' => $documentoId,
                'id_cuentas' => $cuentaId,
                'id_dh' => 1,
                'monto' => $precioConvertido,
                'montodo' => null,
                'glosa' => $this->observaciones,
            ]);
            Log::info('Movimiento de caja relacionado con apertura registrado', [
                'id_documentos' => $documentoId,
                'id_apertura' => $apertura->id,
                'nuevoMovApertura' => $nuevoMovApertura
            ]);
        }
    
        // Confirmación de registro
        Log::info('Documento y movimiento de caja registrados exitosamente');
        session()->flash('message', 'Documento y movimiento de caja registrados exitosamente.');
    }  */
    
/***
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
        'basImp' => 'required|numeric|min:0.01', // TextBox11
        'igv' => 'required|numeric|min:0', // TextBox14
        'noGravado' => 'required|numeric|min:0', // TextBox13
        'precio' => 'required|numeric|min:0.01', // TextBox17
        'observaciones' => 'nullable|string|max:500', // TextBox29
    ], [
        'required' => 'El campo es obligatorio',
        'numeric' => 'Debe ser un valor numérico',
        'min' => 'El valor debe ser mayor a :min',
    ]);

    // Verificar si el documento ya está registrado
    $documentoExistente = Documento::where('id_entidades', $this->docIdent)
        ->where('id_t10tdoc', $this->tipoDocumento)
        ->where('serie', $this->serieNumero1)
        ->where('numero', $this->serieNumero2)
        ->first();

    if ($documentoExistente) {
        session()->flash('error', 'Documento ya registrado');
        return;
    }

    // Registrar el nuevo documento de egreso
    $nuevoDocumento = Documento::create([
        'id_tipmov' => 1, // Cambiado a 1 para Egresos
        'fechaEmi' => $this->fechaEmi,
        'fechaVen' => $this->fechaVen,
        'id_t10tdoc' => $this->tipoDocumento,
        'id_t02tcom' => $this->tipoDocId,
        'id_entidades' => $this->docIdent,
        'id_t04tipmon' => $this->monedaId,
        'id_tasasIgv' => $this->tasaIgvId === 'No Gravado' ? 0 : ($this->tasaIgvId === '18%' ? 1 : ($this->tasaIgvId === '10%' ? 2 : null)),
        'serie' => $this->serieNumero1,
        'numero' => $this->serieNumero2,
        'basImp' => $this->basImp,
        'IGV' => $this->igv,
        'noGravadas' => $this->noGravado ?? 0,
        'otroTributo' => $this->otrosTributos ?? 0,
        'precio' => $this->precio,
        'detraccion' => $this->detraccion ?? 0,
        'montoNeto' => $this->montoNeto ?? 0,
        'id_t10tdocMod' => $this->id_t10tdocMod ?? null,
        'observaciones' => $this->observaciones,
        'serieMod' => $this->serieMod ?? null,
        'numeroMod' => $this->numeroMod ?? null,
        'id_user' => Auth::user()->id,
        'fecha_Registro' => now(),
        'id_dest_tipcaja' => $this->destinatarioVisible ? $this->nuevoDestinatario : null,
    ]);

    // Registrar movimiento en Tesorería
    $this->registrarMovimientoCaja($nuevoDocumento->id, $this->docIdent, $this->fechaEmi);

           // Limpiar el formulario
           $this->resetForm();



    session()->flash('message', 'Documento de egreso registrado exitosamente.');
}

     */
    public function render()
    {
        return view('livewire.ed-registro-documentos-egreso');
    }
}
