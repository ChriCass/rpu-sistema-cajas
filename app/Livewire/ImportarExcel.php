<?php

namespace App\Livewire;
use App\Exports\CabecerasComprobantes;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use App\Imports\DocumentosImport;
use Livewire\WithFileUploads;
use App\Models\TipoDocumentoIdentidad;
use App\Models\TipoDeComprobanteDePagoODocumento;
use App\Models\TipoDeMoneda;
use App\Models\TasaIgv;
use App\Models\Cuenta;
use App\Models\Detalle;
use App\Models\Producto;
use App\Models\Documento;
use App\Models\CentroDeCostos;
use App\Services\ApiService;
use App\Services\RegistroDocAvanzService;
use \PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use DateTime;

class ImportarExcel extends Component
{

    use WithFileUploads; // Aquí se añade el trait necesario
    private $options;
    public $optionsEl;
    public $excelFile;
    protected $ApiService;
    protected $RegistroDocAvanzService;

    public function getOptions(){
        return $this->options;
    }

    public function setOptions($value)
    {
        $this->options = $value;
    }


    public function mount(ApiService $apiService,RegistroDocAvanzService $RegistroDocAvanzService)
    {
        $this->setOptions([
            ['id' => 'cxc', 'name' => 'Cuentas por Cobrar (CXC)'],
            ['id' => 'cxp', 'name' => 'Cuentas por Pagar (CXP)'],
        ]);
        $this->ApiService = $apiService;
        $this->RegistroDocAvanzService = $RegistroDocAvanzService;
    }

    public function hydrate(ApiService $apiService,RegistroDocAvanzService $RegistroDocAvanzService) // Abelardo = Hidrate la inyecion del servicio puesto que no esta funcionando el servicio, con esta opcion logre pasar el service por las diferentes funciones
    {
        $this->ApiService = $apiService;
        $this->RegistroDocAvanzService = $RegistroDocAvanzService;
    }


    public function Plantilla(){
        // Log para depuración

        // Exportar el archivo Excel con datos personalizados
        return Excel::download(new CabecerasComprobantes, 'plantilla.xlsx');
    }


    public function Procesar(){
        try {
            // Validar el archivo
            Log::info('Iniciando validación del archivo Excel.');
            $this->validate([
                'excelFile' => 'required|file|mimes:xls,xlsx|max:10240', // 10MB máximo
            ]);

            if(empty($this -> optionsEl)){
                session()->flash('error', 'Elige cxc o cxp.');
                return;
            }
    
            // Guardar temporalmente el archivo subido
            Log::info('El archivo ha sido validado correctamente. Guardando el archivo temporalmente.');
            $path = $this->excelFile->store('excel_files');
            Log::info('El archivo se guardó en la ruta temporal: ' . $path);
    
            // Procesar el archivo Excel
            Log::info('Iniciando procesamiento del archivo Excel.');
            $dataArray = Excel::toArray(new DocumentosImport, $path);
            Log::info('El archivo Excel se ha procesado, obteniendo datos: ',$dataArray);
            
            $data = $this->DataExcel($dataArray[0]);
            Log::info('Datos procesados con éxito: ', $data);
            
            // Borrar el archivo después de procesarlo si es necesario
            Log::info('Eliminando el archivo temporal después del procesamiento.');
            $path = null; // Si realmente necesitas eliminar el archivo puedes usar: Storage::delete($path);
    
            if ($data['success'] == 1) {
                session()->flash('message', 'El archivo se procesó correctamente.');
                Log::info('El archivo Excel se procesó correctamente.');
            } else {
                session()->flash('error', $data['error']);
                Log::error('Error en el procesamiento del archivo Excel: ' . $data['error']);
            }
            
            // Mensaje de confirmación
        } catch (\Exception $e) {
            // Captura cualquier excepción inesperada y la registra en el log
            Log::error('Ha ocurrido un error inesperado durante el procesamiento del archivo Excel: ' . $e->getMessage());             
            session()->flash('error', 'Ha ocurrido un error inesperado. Inténtelo de nuevo.');

        }
    }

    public function DataExcel($array){
        $headers = [
            "TIPO DOC IDEN",
            "NUM IDENT",
            "ENTIDAD",
            "FECHA EMISION",
            "FECHA DE VENCIMIENTO",
            "T.DOC",
            "SERIE",
            "NUMERO",
            "MONEDA",
            "TASA IMPOSITIVA",
            "OBSERVACION",
            "B.I",
            "IGV",
            "OTROS TRIBUTOS",
            "NO GRAVADO",
            "PRECIO",
            "MONTO DETRACCION",
            "MONTO NETO",
            "T.DOC REFERENCIA",
            "SER REFERENCIA",
            "NUM REFERENCIA",
            "CUENTA",
            "DETALLE",
            "DESCRIPCION",
            "ES GRAVADO",
            "CANTIDAD",
            "C/U",
            "TOTAL",
            "CENTRO DE COSTOS"
        ];

        // Verificamos si la colección tiene datos
        if (count($array) != 0) {
            // Convertimos la colección en un array PHP
            $arr = $array;

            // Log para depurar las cabeceras obtenidas del archivo
            Log::info('Cabeceras del archivo:', $arr[0]);

            // Comparamos las cabeceras
            if ($arr[0] === $headers) {
                Log::info('Validamos las cabeceras correctamente.');

                // Obtenemos el número de filas de datos
                $totalFilas = count($arr);

                if ($totalFilas > 1) {
                    Log::info('Se encontraron ' . ($totalFilas - 1) . ' filas de datos.');
                    
                    $associativeArray = [];

                    // Iteramos sobre las filas de datos, empezando desde la segunda fila
                    for ($k = 1; $k < $totalFilas; $k++) {
                        // Creamos el array asociativo para cada fila
                        $filaAsociativa = array_combine($headers, $arr[$k]);

                        if ($filaAsociativa === false) {
                            Log::error('Error al combinar cabeceras con datos en la fila: ' . $k);
                        } else {
                            $associativeArray[] = $filaAsociativa;
                        }
                    }

                    // Mostramos el array asociativo con los datos procesados
                    Log::info('Datos procesados:', $associativeArray);
                     
                    $data = $this -> PrepararDatos($associativeArray);
                    
                    return $data;
                } else {
                    Log::info('No hay filas de datos para procesar.');
                    $data['success'] = '2';
                    $data['error'] = 'No hay filas de datos para procesar.';
                    return $data;
                }
            } else {
                Log::error('Las cabeceras no coinciden con las esperadas.');
                $data['success'] = '2';
                $data['error'] = 'Las cabeceras no coinciden con las esperadas.';
                return $data;
            }
        } else {
            Log::info('La colección está vacía. No hay datos para procesar.');
            $data['success'] = '2';
            $data['error'] = 'La colección está vacía. No hay datos para procesar';
            return $data;
        }
    }

    public function PrepararDatos($array){
        $dataArray = [];
        $cont = 0;
        foreach($array as $row) {
            // Log para ver el contenido de cada fila antes de la validación
            if ($row['TIPO DOC IDEN'] <> null){
                $cont++;
                $row['FILA'] = $cont;
                Log::info('Fila antes de validación:', ['row' => $row]);
                // Procesa la fila con validación
                 
                $resultado = $this->validacionDeDatos($row);
                if($resultado['success']  == 2){
                    return $resultado;
                }
                // Agregar el resultado validado al array final
                $dataArray[] = $resultado['data'];
                
            } else {
                if ($cont == 0){
                    $resultado['success'] = 2;
                    $resultado['error'] = 'No se proceso ninguna fila';
                    return $resultado;
                }
                
            }
        }

        // Log para ver el array final después del procesamiento completo
        Log::info('Array final después de la preparación de datos:', ['dataArray' => $dataArray]);
        
        $dataN = $this -> manipulacionDatos($dataArray);
        /** 
        $dataN = $this -> insertData($dataArray);        
        Log::info('Datos a procesar: ',$dataN);
        return $dataN; // Devuelve el array final si es necesario
        */

        return $dataN; // Devuelve el array final si es necesario
    }

    public function validacionDeDatos($row){
        $campos = [
            'TIPO DOC IDEN' => $row['TIPO DOC IDEN'] ?? 'No definido',
            'NUM IDENT' => $row['NUM IDENT'] ?? 'No definido',
            'ENTIDAD' => $row['ENTIDAD'] ?? 'No definido',
            'FECHA EMISION' => $row['FECHA EMISION'] ?? 'No definido',
            'FECHA DE VENCIMIENTO' => $row['FECHA DE VENCIMIENTO'] ?? 'No definido',
            'T.DOC' => $row['T.DOC'] ?? 'No definido',
            'SERIE' => $row['SERIE'] ?? 'No definido',
            'NUMERO' => $row['NUMERO'] ?? 'No definido',
            'MONEDA' => $row['MONEDA'] ?? 'No definido',
            'TASA IMPOSITIVA' => $row['TASA IMPOSITIVA'] ?? 'No definido',
            'OBSERVACION' => $row['OBSERVACION'] ?? 'No definido',
            'PRECIO' => $row['PRECIO'] ?? 'No definido',
            'CUENTA' => $row['CUENTA'] ?? 'No definido',
            'DETALLE' => $row['DETALLE'] ?? 'No definido',
            'DESCRIPCION' => $row['DESCRIPCION'] ?? 'No definido',
            'ES GRAVADO' => $row['ES GRAVADO'] ?? 'No definido',
            'CANTIDAD' => $row['CANTIDAD'] ?? 'No definido',
            'C/U' => $row['C/U'] ?? 'No definido',
            'TOTAL' => $row['TOTAL'] ?? 'No definido',
        ];

        // Array para almacenar los campos que no pasan la validación
        $camposNoValidados = [];
    
        // Revisar cada campo para verificar si está vacío o no definido
        foreach ($campos as $campo => $valor) {
            if (empty($valor) || $valor === 'No definido') {
                // Almacenar el campo que no pasa la validación
                $camposNoValidados[] = $campo;
            }
        }
    
        // Registrar los campos que no pasaron la validación si hay alguno
        if (!empty($camposNoValidados)) {
            Log::warning("La fila no pasó la validación. Campos faltantes o vacíos: ", ['camposNoValidados' => $camposNoValidados]);
            $data['success'] = 2;
            $data['error'] = "La fila numero " .$row['FILA']. " no pasó la validación. Campos faltantes o vacíos: " . implode(', ', $camposNoValidados);
            return $data;
        }
    
        // Si todos los campos pasaron la validación
        Log::info("La fila pasó la validación correctamente.");

        if (TipoDocumentoIdentidad::where('id', $row['TIPO DOC IDEN'])->exists()) {
            $validatedData['TIPO DOC IDEN'] = $row['TIPO DOC IDEN'];
        } else {
            $Ndata['success'] = 2;
            $Ndata['error'] = "La fila número " . $row['FILA'] . " tiene un número de documento de identidad no válido.";
            return $Ndata;
        }
        
        $data = $this->ApiService->REntidad($row['TIPO DOC IDEN'], $row['NUM IDENT']);
        if ($data['success'] == 1) {
            $correntistaData = $data['desc'];
            $validatedData['NUM IDENT'] = $row['NUM IDENT'];
            $validatedData['ENTIDAD'] = $correntistaData;
            Log::info("Correntista válido para la fila " . $row['FILA'], [
                'correntistaData' => $correntistaData
            ]);
        } else {
            $Ndata['success'] = 2;
            $Ndata['error'] = "La fila número " . $row['FILA'] . " " . $data['desc'];
            return $Ndata;
        }

        foreach (['FECHA EMISION', 'FECHA DE VENCIMIENTO'] as $fec) {
            $fecha = $row[$fec] ?? null; // Fecha en formato DD/MM/YYYY o nulo si no existe
        
            // Verificar si la fecha es nula (para el caso de Fec_Ven)
            if (is_null($fecha)) {
                $validatedData[$fec] = null;
                Log::info("Fecha nula asignada para el campo " . $fec . " en la fila " . $row['FILA']);
                continue; // Saltar a la siguiente iteración sin validación
            }
        
            // Convertir el valor numérico de Excel a una fecha si es necesario
            if (is_numeric($fecha)) {
                $fechaValida = Date::excelToDateTimeObject($fecha);
                $fechaConvertida = $fechaValida->format('Y-m-d'); // Formato de salida deseado
                $validatedData[$fec] = $fechaConvertida;
        
            } else {
                // Validación para fechas en formato d/m/Y
                $formatoEntrada = "d/m/Y";
                $fechaValida = DateTime::createFromFormat($formatoEntrada, $fecha);
        
                if ($fechaValida && $fechaValida->format($formatoEntrada) === $fecha) {
                    $fechaConvertida = $fechaValida->format('Y-m-d');
                    $validatedData[$fec] = $fechaConvertida;
        
                } else {
                    $Ndata['success'] = 2;
                    $Ndata['error'] = "La fila número " . $row['FILA'] . " el campo " . $fec . " no es válido";
                    return $Ndata;
                }
            }
        }

        $fieldsToValidate = ['T.DOC', 'T.DOC REFERENCIA'];

        foreach ($fieldsToValidate as $field) {

            // Verificar si el valor existe y no es nulo
            if (isset($row[$field]) && !is_null($row[$field])) {
                // Verificar si el comprobante existe en la base de datos
                if (TipoDeComprobanteDePagoODocumento::where('id', $row[$field])->exists()) {
                    $validatedData[$field] = $row[$field];
                } else {
                    $Ndata['success'] = 2;
                    $Ndata['error'] = "La fila número " . $row['FILA'] . " tiene un tipo de documento no válido en " . $field . ".";
                    return $Ndata;
                }
            } else {
                $validatedData[$field] = null;
            }
        }

        $fields = ['SERIE', 'NUMERO', 'OBSERVACION', 'SER REFERENCIA', 'NUM REFERENCIA','DESCRIPCION'];
        
        foreach ($fields as $field) {
            $validatedData[$field] = $row[$field] ?? null;
        }

        // Validación del Tipo de Moneda
        
        if (TipoDeMoneda::where('id', $row['MONEDA'])->exists()) {
            $validatedData['MONEDA'] = $row['MONEDA'];
        } else {
            $Ndata['success'] = 2;
            $Ndata['error'] = "La fila número " . $row['FILA'] . " tiene un código de moneda no válido.";
            return $Ndata;
        }
        // Validación de la Operación IGV
        
        if (TasaIgv::where('tasa', $row['TASA IMPOSITIVA'])->exists()) {
            $validatedData['TASA IMPOSITIVA'] = $row['TASA IMPOSITIVA'];
        } else {
            $Ndata['success'] = 2;
            $Ndata['error'] = "La fila número " . $row['FILA'] . " tiene una operación de IGV no válida.";
            return $Ndata;
        }
        
        $fieldsToValidate = ['B.I', 'IGV', 'OTROS TRIBUTOS', 'NO GRAVADO', 'PRECIO', 'MONTO DETRACCION', 'MONTO NETO' , 'CANTIDAD' , 'C/U' , 'TOTAL'];

        foreach ($fieldsToValidate as $field) {

            // Verificar si el valor existe y no es nulo
            if (isset($row[$field]) && !is_null($row[$field])) {
                // Verificar si el valor es numérico
                if (is_numeric($row[$field])) {
                    $validatedData[$field] = floatval($row[$field]); // Convertir y almacenar como float
                } else {
                    $Ndata['success'] = 2;
                    $Ndata['error'] = "La fila número " . $row['FILA'] . " el campo " . $field . " no tiene formato numérico.";
                    return $Ndata;
                }
            } else {
                $validatedData[$field] = null;
            }
        }

        if (Cuenta::where('id', $row['CUENTA'])->exists()) {
            $validatedData['CUENTA'] = $row['CUENTA'];
        } else {
            $Ndata['success'] = 2;
            $Ndata['error'] = "La fila número " . $row['FILA'] . " la cuenta no es válido.";
            return $Ndata;
        }

        if (Detalle::where('id', $row['DETALLE'])->exists()) {
            $validatedData['DETALLE'] = $row['DETALLE'];
        } else {
            $Ndata['success'] = 2;
            $Ndata['error'] = "La fila número " . $row['FILA'] . " el detalle no es válido.";
            return $Ndata;
        }

        if($row['ES GRAVADO'] == 'SI' || $row['ES GRAVADO'] == 'NO'){
            if ($row['ES GRAVADO'] == 'SI'){
                $validatedData['ES GRAVADO'] = '1';    
            }else{
                $validatedData['ES GRAVADO'] = '0';
            }
        } else {
            $Ndata['success'] = 2;
            $Ndata['error'] = "La fila número " . $row['FILA'] . " la columna de gravado no es válida.";
            return $Ndata;
        }
        if (isset($row['CENTRO DE COSTOS']) && !is_null($row['CENTRO DE COSTOS'])) {
            if (CentroDeCostos::where('descripcion', $row['CENTRO DE COSTOS'])->exists()) {
                $validatedData['CENTRO DE COSTOS'] = $row['CENTRO DE COSTOS']; // Convertir y almacenar como float
            } else {
                $Ndata['success'] = 2;
                $Ndata['error'] = "La fila número " . $row['FILA'] . " el centro de costos no es valido.";
                return $Ndata;
            }
        } else {
            $validatedData['CENTRO DE COSTOS'] = null;
        }
        Log::info("Datos procesados listos para empezar a manipularlos:", $validatedData);
        $resultado['success'] = 1;
        $resultado['FILA'] = $row['FILA'];
        $resultado['data'] = $validatedData;

        return $resultado;
    }

    public function manipulacionDatos($data){
        Log::info("Iniciando proceso de manipulacion de datos");

        $codigosUnicos = $this -> codigosunicos($data);
        $dataExtendida = $this -> codigosfilas($data,$codigosUnicos);
        
        $dataValidada = $this -> ValidacionData ($dataExtendida,$codigosUnicos);
        if ($dataValidada['success'] == 2){
            return $dataValidada;
        }

        $i = 0; 
        $dataManipulada = [];
        foreach ($codigosUnicos as $cod){
            $productos = [];
            $k = 0;
            foreach($dataExtendida as $daEx){
                if($cod == $daEx['codigoUnico']){
                    if ($daEx['codigoFila'] == '1'){
                        $dataManipulada[] = [
                            'tipoDocumento' => $daEx['T.DOC'],
                            'serieNumero1' => $daEx['SERIE'],
                            'serieNumero2' => $daEx['NUMERO'],
                            'tipoDocId' => $daEx['TIPO DOC IDEN'],
                            'docIdent' => $daEx['NUM IDENT'],
                            'monedaId' => $daEx['MONEDA'],
                            'tasaIgvId' => $daEx['TASA IMPOSITIVA'],
                            'fechaEmi' => $daEx['FECHA EMISION'],
                            'fechaVen' => $daEx['FECHA DE VENCIMIENTO'],
                            'basImp' => $daEx['B.I'] ?? 0,
                            'igv' => $daEx['IGV'] ?? 0,
                            'noGravado' => $daEx['NO GRAVADO'] ?? 0,
                            'precio' => $daEx['PRECIO'],
                            'observaciones' => $daEx['OBSERVACION'],
                            'user' => Auth::user()->id,
                            'origen' => $this -> optionsEl,
                            'cuenta' => $daEx['CUENTA'],
                            'montoDetraccion' => $daEx['MONTO DETRACCION'] ?? 0,
                            'montoNeto' => $daEx['MONTO NETO'] ?? 0,
                            'id_t10tdocMod' => $daEx['T.DOC REFERENCIA'] ?? null,
                            'serieMod' => $daEx['SER REFERENCIA'] ?? null,
                            'numeroMod' => $daEx['NUM REFERENCIA'] ?? null,
                        ];
                    }
                    $producto = $this->CodigoProducto($daEx['DETALLE'],$daEx['DESCRIPCION']);
                    $productos[$k]['codigoProducto'] = $producto;
                    $productos[$k]['cantidad'] = $daEx['CANTIDAD'];
                    $productos[$k]['precioUnitario'] = $daEx['C/U'];
                    $productos[$k]['total'] = $daEx['TOTAL'];
                    $productos[$k]['tasaImpositiva'] = $daEx['ES GRAVADO'];
                    $productos[$k]['CC'] = $daEx['CENTRO DE COSTOS'] ?? null;
                    $productos[$k]['observacion'] = null;
                    $k++;
                }
            }
            $dataManipulada[$i]['productos'] = $productos;
            $i++;
        }
        Log::info($dataManipulada);
        foreach ($dataManipulada as $imp){
            $origen = ($imp['origen'] == "cxc" )? 1 : 2;
            $val = $this->DataRepetida($origen,$imp['docIdent'],$imp['tipoDocumento'],$imp['serieNumero1'],$imp['serieNumero2']);
            if ($val['success'] == '2'){
                return $val;
            }
            $result = $this->RegistroDocAvanzService->guardarDocumento($imp);
        }
        $Ndata['success'] = 1;
        return $Ndata;
    }

    public function codigosunicos($data) {
        $unicos = [];
        foreach($data as $dat){
            $unicos[] = $dat['NUM IDENT'] . $dat['T.DOC'] . $dat['SERIE'] . $dat['NUMERO']; 
        }
        $unicos = array_unique($unicos);
        return $unicos;
    }

    public function codigosfilas($data,$codigosUnicos){
        $dataManipulada = [];
        foreach ($codigosUnicos as $cod){
            $i = 1;
            foreach ($data as $dat){
                if ($cod == $dat['NUM IDENT'] . $dat['T.DOC'] . $dat['SERIE'] . $dat['NUMERO']){
                    $dat['codigoUnico'] = $cod;
                    $dat['codigoFila'] = $i;
                    $dataManipulada[] = $dat;
                    $i++;
                }
                
            }
            
        }
        return $dataManipulada;
    }

    public function ValidacionData($data, $codigosUnicos) {
        $Ndata = []; // Inicializamos la variable de retorno
    
        foreach ($codigosUnicos as $cod) {
            $sum = 0;  // Reiniciamos la suma para cada código único
            $total = 0; // Inicializamos el total
    
            foreach ($data as $dat) {
                if ($cod == $dat['codigoUnico']) {
                    if ($dat['codigoFila'] == '1') {
                        $total = $dat['B.I'] + $dat['NO GRAVADO']; // Calculamos el total solo para la 
                    }
                    $sum += $dat['TOTAL']; // Sumamos el total de cada producto
                }
            }
    
            if (round($total,2) != round($sum,2)) { // Comparamos total de cabecera con suma de productos
                $Ndata['success'] = 2;
                $Ndata['error'] = "La suma de las cabeceras no es igual a la suma de productos para el código: $cod los totales son $total y $sum";
                return $Ndata;
            }
        }
    
        // Si todo es correcto, retornamos éxito
        $Ndata['success'] = 1;
        Log::info("Todos los códigos han sido validados correctamente.");
        return $Ndata;
    }
    
    public function CodigoProducto($detalle, $producto) {
        // Realizar la consulta a la base de datos
        $pro = Producto::where('id_detalle', $detalle)
                       ->where('descripcion', strtoupper($producto))
                       ->get();
    
        // Loguear el resultado de la consulta
        if ($pro->isEmpty()) {
            $ale = strtoupper(Str::random(6));
            Producto::create([
                'id' => $ale,
                'id_detalle' => $detalle,
                'descripcion' => strtoupper($producto),
            ]);
            $cod = $ale;
        } else {
            $cod = $pro[0]['id'];
        }
        
        return $cod; // Si necesitas devolver el resultado de la consulta
    }

    public function DataRepetida($tipmov,$entidad,$tdoc,$ser,$num){
        // Realizar la consulta a la base de datos
        $pro = Documento::where('id_tipmov',$tipmov)
            ->where('id_entidades',$entidad)
            ->where('id_t10tdoc',$tdoc)
            ->where('serie',$ser)
            ->where('numero',$num)
            ->get();
        // Loguear el resultado de la consulta
        if ($pro->isEmpty()) {
            $cod['success'] = 1;
        } else {
            $cod['success'] = 2;
            $cod['error'] = "El documento con codigo " .$entidad."-".$tdoc."-".$ser."-".$num." ya fue registrado";
        }
        
        return $cod; // Si necesitas devolver el resultado de la consulta        
    }
    

    public function render()
    {
        return view('livewire.importar-excel', [
            'options' => $this->getOptions(),  
        ]);
    }
}
