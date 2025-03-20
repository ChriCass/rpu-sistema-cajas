<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CabecerasCajaExport;
use App\Imports\CajaImport;
use App\Models\Apertura;
use Illuminate\Support\Facades\Log;
use App\Models\TipoDocumentoIdentidad;
use App\Models\TipoDeComprobanteDePagoODocumento;
use App\Models\TipoDeMoneda;
use App\Models\TasaIgv;
use App\Models\Familia;
use App\Models\TipoDeCaja;
use App\Models\Mes;
use App\Models\Cuenta;
use App\Models\Detalle;
use App\Models\Producto;
use App\Models\Documento;
use App\Models\CentroDeCostos;
use App\Services\ApiService;
use App\Services\RegistroDocAvanzService;
use App\Services\RegistroVauchers;
use App\Services\RegistroDocCajaBalance;
use \PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use DateTime;
use PhpParser\Node\Stmt\Else_;

class ImportadorGeneral extends Component
{
    use WithFileUploads; // Aquí se añade el trait necesario
    private $options;
    public $optionsEl;
    public $excelFile;
    protected $ApiService;
    protected $RegistroDocAvanzService;
    protected $RegistroVauchers;
    protected $RegistroDocCajaBalance;


    ///// RECUERDA SEGUIR LOS PRINCIPIOS DE RESPONSABILIDAD UNICA. ESTO SOLO FUNCIONA COMO FRONT, LO DEMAS UTILIZAMOS MEDIANTE SERVICES
    public function getOptions(){
        return $this->options;
    }

    public function setOptions($value)
    {
        $this->options = $value;
    }

    

    public function mount(ApiService $apiService,RegistroDocAvanzService $RegistroDocAvanzService, RegistroVauchers $RegistroVauchers, RegistroDocCajaBalance $RegistroDocCajaBalance)
    {
        $this->setOptions([
            ['id' => 'cxc', 'name' => 'Cuentas por Cobrar (CXC)'],
            ['id' => 'cxp', 'name' => 'Cuentas por Pagar (CXP)'],
        ]);
        $this->ApiService = $apiService;
        $this->RegistroDocAvanzService = $RegistroDocAvanzService;
        $this->RegistroVauchers = $RegistroVauchers;
        $this->RegistroDocCajaBalance = $RegistroDocCajaBalance;
    }

    public function hydrate(ApiService $apiService,RegistroDocAvanzService $RegistroDocAvanzService, RegistroVauchers $RegistroVauchers, RegistroDocCajaBalance $RegistroDocCajaBalance) // Abelardo = Hidrate la inyecion del servicio puesto que no esta funcionando el servicio, con esta opcion logre pasar el service por las diferentes funciones
    {
        $this->ApiService = $apiService;
        $this->RegistroDocAvanzService = $RegistroDocAvanzService;
        $this->RegistroVauchers = $RegistroVauchers;
        $this->RegistroDocCajaBalance = $RegistroDocCajaBalance;

    }

    public function Plantilla()
    {
        /// DESCARGAR PLANTILLA MEDIANTE EXPORT
        return Excel::download(new CabecerasCajaExport, 'plantilla.xlsx');
        // php artisan make:export NombreDelExportadorParaPlantillaExport 
        
    }

    public function Procesar()
    {
        try {
            // Validar el archivo
            Log::info('Iniciando validación del archivo Excel.');
            $this->validate([
                'excelFile' => 'required|file|mimes:xls,xlsx|max:10240', // 10MB máximo
            ]);

    
            // Guardar temporalmente el archivo subido
            Log::info('El archivo ha sido validado correctamente. Guardando el archivo temporalmente.');
            $path = $this->excelFile->store('excel_files');
            Log::info('El archivo se guardó en la ruta temporal: ' . $path);
    
            // Procesar el archivo Excel
            Log::info('Iniciando procesamiento del archivo Excel.');
            $dataArray = Excel::toArray(new CajaImport, $path);
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
            'TIPO DE CAJA',
            'AÑO',
            'MES',
            'NUMERO',
            'TIPO DE MOVIMIENTO',
            'NUMERO DE MOVIMIENTO',
            'MONTO',
            'TIPO DOC IDEN',
            'NUM IDENT',
            'ENTIDAD',
            'T.DOC',
            'SERIE',
            'CORRELATIVO',
            'CUENTA',
            'OBSERVACION',
            'NUMERO DE OPERACIÓN',
            'MONEDA',
            'FECHA EMISION',
            'FECHA DE VENCIMIENTO',
            'TASA IMPOSITIVA',
            'B.I',
            'IGV',
            'OTROS TRIBUTOS',
            'NO GRAVADO',
            'DETALLE',
            'DESCRIPCION',
            'ES GRAVADO',
            'CANTIDAD',
            'C/U',
            'TOTAL',
            'CENTRO DE COSTOS',
            'CAJA DESTINO'
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
                Log::error($arr);
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
            if ($row['TIPO DE CAJA'] <> null){
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

        return $dataN; // Devuelve el array final si es necesario
    }

    public function validacionDeDatos($row){
        $campos = [
            'TIPO DE CAJA' => $row['TIPO DE CAJA'] ?? 'No definido',
            'AÑO' => $row['AÑO'] ?? 'No definido',
            'MES' => $row['MES'] ?? 'No definido',
            'NUMERO' => $row['NUMERO'] ?? 'No definido',
            'TIPO DE MOVIMIENTO' => $row['TIPO DE MOVIMIENTO'] ?? 'No definido',
            'NUMERO DE MOVIMIENTO' => $row['NUMERO DE MOVIMIENTO'] ?? 'No definido',
            'MONTO' => $row['MONTO'] ?? 'No definido',
            'TIPO DOC IDEN' => $row['NUM IDENT'] ?? 'No definido',
            'ENTIDAD' => $row['ENTIDAD'] ?? 'No definido',
            'T.DOC' => $row['T.DOC'] ?? 'No definido',
            'SERIE' => $row['SERIE'] ?? 'No definido',
            'CORRELATIVO' => $row['CORRELATIVO'] ?? 'No definido',
            'CUENTA' => $row['CUENTA'] ?? 'No definido',
            'OBSERVACION' => $row['OBSERVACION'] ?? 'No definido',
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
        
        if (TipoDeCaja::where('id', $row['TIPO DE CAJA'])->exists()) {
            $validatedData['TIPO DE CAJA'] = $row['TIPO DE CAJA'];
        } else {
            $Ndata['success'] = 2;
            $Ndata['error'] = "La fila número " . $row['FILA'] . " tiene una caja no valida.";
            return $Ndata;
        }        
        if (Mes::where('id', $row['MES'])->exists()) {
            $validatedData['MES'] = $row['MES'];
        } else {
            $Ndata['success'] = 2;
            $Ndata['error'] = "La fila número " . $row['FILA'] . " no tiene un mes valido.";
            return $Ndata;
        }
        if($row['TIPO DE MOVIMIENTO'] == '1' || $row['TIPO DE MOVIMIENTO'] == '2'){
            $validatedData['TIPO DE MOVIMIENTO'] = $row['TIPO DE MOVIMIENTO'];
        } else {
            $Ndata['success'] = 2;
            $Ndata['error'] = "La fila número " . $row['FILA'] . " la columna no tiene un tipo de movimeinto valido.";
            return $Ndata;
        }

        $fieldsToValidate = ['NUMERO', 'NUMERO DE MOVIMIENTO'];

        foreach ($fieldsToValidate as $field) {
            // Verificar si el valor es un número entero
            if (filter_var($row[$field], FILTER_VALIDATE_INT) !== false) {
                $validatedData[$field] = intval($row[$field]); // Convertir y almacenar como entero
            } else {
                $Ndata['success'] = 2;
                $Ndata['error'] = "La fila número " . $row['FILA'] . " el campo " . $field . " no tiene formato de número entero.";
                return $Ndata;
            }
        }

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

        if (TipoDeComprobanteDePagoODocumento::where('id', $row['T.DOC'])->exists()) {
            $validatedData['T.DOC'] = $row['T.DOC'];
        } else {
            $Ndata['success'] = 2;
            $Ndata['error'] = "La fila número " . $row['FILA'] . " tiene un tipo de documento no válido en T.DOC.";
            return $Ndata;
        }

        foreach (['FECHA EMISION', 'FECHA DE VENCIMIENTO'] as $fec) {
            $fecha = $row[$fec] ?? null; // Si no existe, asignar null directamente
        
            // Si la fecha es nula, asignar null y continuar con la siguiente iteración
            if (is_null($fecha)) {
                $validatedData[$fec] = null;
                Log::info("Fecha nula asignada para el campo " . $fec . " en la fila " . ($row['FILA'] ?? 'desconocida'));
                continue;
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
                    $Ndata['error'] = "La fila número " . ($row['FILA'] ?? 'desconocida') . " el campo " . $fec . " no es válido";
                    return $Ndata;
                }
            }
        }
        

        if (!empty($row['MONEDA'])) { // Verifica si el campo existe y no está vacío
            if (TipoDeMoneda::where('id', $row['MONEDA'])->exists()) {
                $validatedData['MONEDA'] = $row['MONEDA'];
            } else {
                $Ndata['success'] = 2;
                $Ndata['error'] = "La fila número " . ($row['FILA'] ?? 'desconocida') . " tiene un código de moneda no válido.";
                return $Ndata;
            }
        } else {
            $validatedData['MONEDA'] = null; // Asignar null si está vacío o no existe
        }
        

        if (!empty($row['TASA IMPOSITIVA'])) { // Verifica si el campo existe y no está vacío
            if (TasaIgv::where('tasa', $row['TASA IMPOSITIVA'])->exists()) {
                $validatedData['TASA IMPOSITIVA'] = $row['TASA IMPOSITIVA'];
            } else {
                $Ndata['success'] = 2;
                $Ndata['error'] = "La fila número " . ($row['FILA'] ?? 'desconocida') . " tiene una operación de IGV no válida.";
                return $Ndata;
            }
        } else {
            $validatedData['TASA IMPOSITIVA'] = null; // Asignar null si está vacío o no existe
        }
        

        $fieldsToValidate = ['B.I', 'IGV', 'OTROS TRIBUTOS', 'NO GRAVADO', 'MONTO', 'CANTIDAD' , 'C/U' , 'TOTAL'];

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

        if (!empty($row['DETALLE'])) { // Verificar si el campo no está vacío
            if (Detalle::where('id', $row['DETALLE'])->exists()) {
                $validatedData['DETALLE'] = $row['DETALLE'];
            } else {
                $Ndata['success'] = 2;
                $Ndata['error'] = "La fila número " . $row['FILA'] . " el detalle no es válido.";
                return $Ndata;
            }
        } else {
            $validatedData['DETALLE'] = null; // Asignar null si está vacío o no existe
        }
        
        if (!empty($row['ES GRAVADO'])) { // Verifica si el campo no está vacío o nulo
            if ($row['ES GRAVADO'] == 'SI' || $row['ES GRAVADO'] == 'NO') {
                $validatedData['ES GRAVADO'] = ($row['ES GRAVADO'] == 'SI') ? '1' : '0';
            } else {
                $Ndata['success'] = 2;
                $Ndata['error'] = "La fila número " . $row['FILA'] . " la columna de gravado no es válida.";
                return $Ndata;
            }
        } else {
            $validatedData['ES GRAVADO'] = null; // Asignar null si está vacío o no existe
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

        $fields = ['SERIE', 'CORRELATIVO', 'OBSERVACION', 'AÑO', 'ENTIDAD','NUMERO DE OPERACIÓN','NUMERO DE OPERACIÓN','CAJA DESTINO','DESCRIPCION'];
        
        foreach ($fields as $field) {
            $validatedData[$field] = $row[$field] ?? null;
        }


        if(!Apertura::where('id_tipo',$row['TIPO DE CAJA'])->where('numero',$row['NUMERO'])->where('año',$row['AÑO'])->where('id_mes',$row['MES'])->exists()){
            $Ndata['success'] = 2;
            $Ndata['error'] = "La fila número " . $row['FILA'] . " la apertura no existe.";
            return $Ndata;
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
        $docmov = 0;
        $docmov74 = 0;
        $docmov76 = 0;
        $docmov77 = 0;
        foreach ($codigosUnicos as $cod){
            $productos = [];
            $k = 0;
            $contenedor = [];
            $movimiento = [];
            $fila = [];
            $sumaTotal = 0;
            foreach($dataExtendida as $daEx){                
                if($cod == $daEx['codigoUnico']){
                    if($daEx['TIPO DE MOVIMIENTO'] == '1'){
                        if ($daEx['codigoFila'] == '1') {
                             $contenedor['TIPOMOVIENTO'] = $daEx['MONTO'] > 0 ? 'CXC' : 'CXP';
                             $caja = TipoDeCaja::where('id',$daEx['TIPO DE CAJA'])->first();
                             $contenedor['MONEDA'] = $caja->t04_tipodemoneda;
                             $apertura = Apertura::where('id_tipo',$daEx['TIPO DE CAJA'])->where('numero',$daEx['NUMERO'])->where('año',$daEx['AÑO'])->where('id_mes',$daEx['MES'])->first();
                             $contenedor['APERTURA'] = $apertura->id;
                             $contenedor['FECHA'] = $apertura->fecha;
                        }
                        $fila['CUENTA'] = $daEx['CUENTA'];
                        $fila['TIPO'] =  $daEx['MONTO'] > 0 ? '1' : '2';
                        $documento = Documento::where('id_tipmov',$daEx['MONTO'] > 0 ? '1' : '2')->where('id_t10tdoc',$daEx['T.DOC'])->where('id_entidades',$daEx['NUM IDENT'])->where('serie',$daEx['SERIE'])->where('numero',$daEx['CORRELATIVO'])->first();
                        $fila['DOCUMENTO'] = $documento->id;
                        $fila['MONTO'] = abs($daEx['MONTO']);
                        $fila['OBSERVACION'] = $daEx['OBSERVACION'];
                        $fila['NUMERO DE OPERACIÓN'] = $daEx['NUMERO DE OPERACIÓN'];
                        $sumaTotal += abs($daEx['MONTO']);
                        $movimiento[] = $fila;
                    }else{
                        if ($daEx['codigoFila'] == '1'){
                            if($daEx['T.DOC'] == '75'){
                                $daEx['SERIE'] = '0000';
                                $ultimoDocumento = Documento::where('id_t10tdoc',  $daEx['T.DOC']) // Tipo de documento 74
                                    ->where('serie', $daEx['SERIE']) // Serie 0000
                                    ->orderByRaw('CAST(numero AS UNSIGNED) DESC') // Ordenar por número de documento de manera descendente
                                    ->first(); // Obtener el primer registro (el número más alto)

                                // Asignar el siguiente número de serie
                                if ($ultimoDocumento) {
                                    $daEx['CORRELATIVO'] = intval($ultimoDocumento->numero) + 1 + $docmov; // Incrementar el número en 1
                                    $docmov++;
                                } else {
                                    $daEx['CORRELATIVO'] = '1'; // Si no hay registros, empezar con 1
                                }
                                $daEx['TIPO DOC IDEN'] = '1';
                                $daEx['NUM IDENT'] = '10000001';
                            }elseif($daEx['DETALLE'] == '001000001'){
                                $daEx['T.DOC'] = '74';
                                $daEx['SERIE'] = '0000';
                                $ultimoDocumento = Documento::where('id_t10tdoc',  $daEx['T.DOC']) // Tipo de documento 74
                                    ->where('serie', $daEx['SERIE']) // Serie 0000
                                    ->orderByRaw('CAST(numero AS UNSIGNED) DESC') // Ordenar por número de documento de manera descendente
                                    ->first(); // Obtener el primer registro (el número más alto)

                                // Asignar el siguiente número de serie
                                if ($ultimoDocumento) {
                                    $daEx['CORRELATIVO'] = intval($ultimoDocumento->numero) + 1 + $docmov74; // Incrementar el número en 1
                                    $docmov74++;
                                } else {
                                    $daEx['CORRELATIVO'] = '1'; // Si no hay registros, empezar con 1
                                }
                                $daEx['TIPO DOC IDEN'] = '6';
                                $daEx['NUM IDENT'] = '20603436955';
                                $daEx['CUENTA'] = $daEx['MONTO'] > 0 ? 9 : 8;
                            }elseif($daEx['DETALLE'] == '003000001' || $daEx['DETALLE'] == '003000002'){
                                $daEx['DETALLE'] = $daEx['MONTO'] > 0 ? '003000001' : '003000002';
                                $daEx['T.DOC'] = '76';
                                $daEx['SERIE'] = '0000';
                                $ultimoDocumento = Documento::where('id_t10tdoc',  $daEx['T.DOC']) // Tipo de documento 74
                                    ->where('serie', $daEx['SERIE']) // Serie 0000
                                    ->orderByRaw('CAST(numero AS UNSIGNED) DESC') // Ordenar por número de documento de manera descendente
                                    ->first(); // Obtener el primer registro (el número más alto)

                                // Asignar el siguiente número de serie
                                if ($ultimoDocumento) {
                                    $daEx['CORRELATIVO'] = intval($ultimoDocumento->numero) + 1 + $docmov76; // Incrementar el número en 1
                                    $docmov76++;
                                } else {
                                    $daEx['CORRELATIVO'] = '1'; // Si no hay registros, empezar con 1
                                }   
                            }elseif($daEx['DETALLE'] == '004000001' || $daEx['DETALLE'] == '004000002'){
                                $daEx['DETALLE'] = $daEx['MONTO'] > 0 ? $daEx['DETALLE'] = '004000001' : $daEx['DETALLE'] == '004000002';
                                $daEx['T.DOC'] = '77';
                                $daEx['SERIE'] = '0000';
                                $ultimoDocumento = Documento::where('id_t10tdoc',  $daEx['T.DOC']) // Tipo de documento 74
                                    ->where('serie', $daEx['SERIE']) // Serie 0000
                                    ->orderByRaw('CAST(numero AS UNSIGNED) DESC') // Ordenar por número de documento de manera descendente
                                    ->first(); // Obtener el primer registro (el número más alto)

                                // Asignar el siguiente número de serie
                                if ($ultimoDocumento) {
                                    $daEx['CORRELATIVO'] = intval($ultimoDocumento->numero) + 1 + $docmov77; // Incrementar el número en 1
                                    $docmov77++;
                                } else {
                                    $daEx['CORRELATIVO'] = '1'; // Si no hay registros, empezar con 1
                                }   
                            }
                            $contenedor['TIPOMOVIENTO'] = $daEx['MONTO'] > 0 ? 'ingreso' : 'egreso';
                            $contenedor['TIPOFAM'] = Familia::where('id', substr($daEx['DETALLE'], 0, 3))->value('id_tipofamilias') == '1' ? 'Balance' : 'Resultados';
                            $contenedor['CABECERAS'] = [
                                'tipoDocumento' => $daEx['T.DOC'],
                                'serieNumero1' => $daEx['SERIE'],
                                'serieNumero2' => $daEx['CORRELATIVO'],
                                'tipoDocId' => $daEx['TIPO DOC IDEN'],
                                'docIdent' => $daEx['NUM IDENT'],
                                'monedaId' => $daEx['MONEDA'],
                                'tasaIgvId' => $daEx['TASA IMPOSITIVA'],
                                'fechaEmi' => $daEx['FECHA EMISION'],
                                'fechaVen' => $daEx['FECHA DE VENCIMIENTO'],
                                'basImp' => $daEx['B.I'] ?? 0,
                                'igv' => $daEx['IGV'] ?? 0,
                                'noGravado' => $daEx['NO GRAVADO'] ?? 0,
                                'precio' => abs($daEx['MONTO']),
                                'observaciones' => $daEx['OBSERVACION'],
                                'user' => Auth::user()->id,
                                'origen' => $daEx['MONTO'] > 0 ? 'ingreso' : 'egreso',
                                'cuenta' => $daEx['CUENTA'],
                                'apertura' => [
                                    'numero' => $daEx['NUMERO'],
                                    'id_tipo' => $daEx['TIPO DE CAJA'],
                                    'mes' => ['descripcion' => Mes::where('id', $daEx['MES'])->value('descripcion')],
                                    'año' => $daEx['AÑO'],
                                ],
                                'cod_operacion' => $daEx['NUMERO DE OPERACIÓN'],
                                'caja_destino' => $daEx['CAJA DESTINO'] ?? null,
                            ];
                        }
                        Log::info($daEx['CORRELATIVO']);
                        Log::info($daEx['DETALLE']);
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
            }

            if ($contenedor['TIPOMOVIENTO'] == 'CXC' || $contenedor['TIPOMOVIENTO'] == 'CXP'){
                $contenedor['TOTAL'] = $sumaTotal;
                $contenedor['DATOS'] = $movimiento;
            }else{
                $contenedor['CABECERAS']['productos'] = $productos;
            } 

            $dataManipulada[] = $contenedor;
            $contenedor = null;
        }


        $this->insertardata($dataManipulada);
        
        
        $Ndata['success'] = 1;
        return $Ndata;
    }

    public function codigosunicos($data) {
        $unicos = [];
        foreach($data as $dat){
            $unicos[] = $dat['TIPO DE CAJA'] . $dat['AÑO'] . $dat['MES'] . $dat['NUMERO'] . $dat['NUMERO DE MOVIMIENTO']; 
        }
        $unicos = array_unique($unicos);
        return $unicos;
    }

    public function codigosfilas($data,$codigosUnicos){
        $dataManipulada = [];
        foreach ($codigosUnicos as $cod){
            $i = 1;
            foreach ($data as $dat){
                if ($cod == $dat['TIPO DE CAJA'] . $dat['AÑO'] . $dat['MES'] . $dat['NUMERO'] . $dat['NUMERO DE MOVIMIENTO']){
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
            $tipmov = null;

            foreach ($data as $dat) {
                if ($cod == $dat['codigoUnico']) {
                    if ($dat['codigoFila'] == '1') {
                        $tipmov = $dat['TIPO DE MOVIMIENTO'];
                        //$total = $dat['B.I'] + $dat['NO GRAVADO']; // Calculamos el total solo para la 
                    }
                    if ($dat['MONTO'] > 0){
                        $cxcxcp = 1;
                    }else{
                        $cxcxcp = 2;
                    }
                    if ($tipmov == '1'){   
                        if(!Documento::where('id_tipmov',$cxcxcp)->where('id_t10tdoc',$dat['T.DOC'])->where('id_entidades',$dat['NUM IDENT'])->where('serie',$dat['SERIE'])->where('numero',$dat['CORRELATIVO'])->exists()){
                            $codi = $dat['NUM IDENT'] .'-'. $dat['T.DOC'] .'-'. $dat['SERIE'] .'-'. $dat['CORRELATIVO'];
                            $Ndata['success'] = 2;
                            $Ndata['error'] = "Este documento no esta registrado: $codi";
                            return $Ndata;
                        } 
                    }else{
                        $codi = $dat['NUM IDENT'] .'-'. $dat['T.DOC'] .'-'. $dat['SERIE'] .'-'. $dat['CORRELATIVO'];
                        if(Documento::where('id_tipmov',$cxcxcp)->where('id_t10tdoc',$dat['T.DOC'])->where('id_entidades',$dat['NUM IDENT'])->where('serie',$dat['SERIE'])->where('numero',$dat['CORRELATIVO'])->exists()){
                            $Ndata['success'] = 2;
                            $Ndata['error'] = "Este documento ya esta registrado: $codi";
                            return $Ndata;
                        }
                        $campos = [
                            'MONEDA' => $dat['MONEDA'] ?? 'No definido',
                            'FECHA EMISION' => $dat['FECHA EMISION'] ?? 'No definido',
                            'FECHA DE VENCIMIENTO' => $dat['FECHA DE VENCIMIENTO'] ?? 'No definido',
                            'TASA IMPOSITIVA' => $dat['TASA IMPOSITIVA'] ?? 'No definido',
                            'B.I' => $dat['B.I'] ?? 'No definido',
                            'IGV' => $dat['IGV'] ?? 'No definido',
                            'OTROS TRIBUTOS' => $dat['OTROS TRIBUTOS'] ?? 'No definido',
                            'NO GRAVADO' => $dat['NO GRAVADO'] ?? 'No definido',
                            'DETALLE' => $dat['DETALLE'] ?? 'No definido',
                            'DESCRIPCION' => $dat['DESCRIPCION'] ?? 'No definido',
                            'ES GRAVADO' => $dat['ES GRAVADO'] ?? 'No definido',
                            'CANTIDAD' => $dat['CANTIDAD'] ?? 'No definido',
                            'C/U' => $dat['C/U'] ?? 'No definido',
                            'TOTAL' => $dat['TOTAL'] ?? 'No definido',
                        ];
                        Log::info($campos);
                        // Array para almacenar los campos que no pasan la validación
                        $camposNoValidados = [];
                    
                        // Revisar cada campo para verificar si está vacío o no definido
                        foreach ($campos as $campo => $valor) {
                            if (($valor === '' || is_null($valor) || $valor === 'No definido') && $valor !== 0) {
                                // Almacenar el campo que no pasa la validación
                                $camposNoValidados[] = $campo;
                            }
                        }
                    
                        // Registrar los campos que no pasaron la validación si hay alguno
                        if (!empty($camposNoValidados)) {
                            Log::warning("La fila no pasó la validación. Campos faltantes o vacíos: ", ['camposNoValidados' => $camposNoValidados]);
                            $data['success'] = 2;
                            $data['error'] = "En el documento " .$codi. " no pasó la validación. Campos faltantes o vacíos: " . implode(', ', $camposNoValidados);
                            return $data;
                        }

                        if ($dat['DETALLE']=='001000001'){
                            if(empty($dat['CAJA DESTINO'])){
                                $data['success'] = 2;
                                $data['error'] = "En el codigo unico: " .$dat['codigoUnico']. " la caja de destino es obligatoria";
                                return $data;
                            }
                        }

                        if (Familia::where('id', substr($dat['DETALLE'], 0, 3))->value('id_tipofamilias') == '1'){
                            if ($dat['codigoFila'] > '1') {
                                $data['success'] = 2;
                                $data['error'] = "En el codigo unico: " .$dat['codigoUnico']. " se esta usando un detalle que afecta a balance por consecuencia solo puede tener una fila";
                                return $data;
                            }       
                        }

                        if (ROUND($dat['B.I'] + $dat['IGV'] + $dat['OTROS TRIBUTOS'] + $dat['NO GRAVADO'],2) <> abs($dat['MONTO'])){
                            $data['success'] = 2;
                            $data['error'] = "En el documento: " .$codi. " la sumatoria no cuadra los valores con el monto total";
                            return $data;
                        }


                        if ($dat['codigoFila'] == '1') {
                            $total = $dat['B.I'] + $dat['NO GRAVADO']; // Calculamos el total solo para la 
                        }
                        $sum += $dat['TOTAL']; // Sumamos el total de cada producto
                        
                    }
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

    public function insertardata($dataManipulada){
        Log::info('Iniciando el procesos de Insercion de data');
        foreach($dataManipulada as $insert){
            if ($insert['TIPOMOVIENTO'] == 'CXC' || $insert['TIPOMOVIENTO'] == 'CXP'){
                $this->RegistroVauchers->guardarVaucher($insert);
            }else{
                if($insert['TIPOFAM'] == 'Balance'){
                    $this->RegistroDocCajaBalance->guardarDocumento($insert['CABECERAS']);
                }else{
                    $this->RegistroDocAvanzService->guardarDocumento($insert['CABECERAS']);
                }
            }
        }
    }    

    public function render()
    {
        return view('livewire.importador-general', [
            'options' => $this->getOptions(),  
        ]); 
    }
}
