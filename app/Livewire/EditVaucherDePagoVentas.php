<?php

namespace App\Livewire;

use Livewire\Component;
use DateTime;
use App\Models\Apertura;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\TipoDeCaja;
use App\Models\Cuenta;
use App\Models\MovimientoDeCaja;
use App\Models\TipoDeCambioSunat;
use Livewire\Attributes\On;
use Carbon\Carbon;

class EditVaucherDePagoVentas extends Component
{
    public $aperturaId;
    public $numMov;
    public $fechaApertura;
    public $moneda = "PEN";
    public $contenedor = [];
    public $debe = 0.0; // Variable para almacenar el total del debe
    public $haber = 0.0; // Variable para almacenar el total del haber
    public $selectedIndex = null;
    public $editingIndex = null; // Para manejar qué fila está en edición
    public $editingMonto = null; // Para almacenar temporalmente el monto que se está editando
    public $warningMessage = []; // Para manejar mensajes de advertencia por cada fila
    public $balance = 0.0; // Nueva variable para almacenar el balance (Debe - Haber)
    public $caja;
    public $tipoCaja;
    public $fecha;
    public $observacion;
    public $cod_operacion;

    public function mount($numeroMovimiento, $aperturaId)
    {
        $this->aperturaId = $aperturaId;

        // Recuperar la apertura y el tipo de caja
        $apertura = Apertura::findOrFail($this->aperturaId);
        $this->caja = $apertura->id_tipo;

        // Obtener el tipo de caja relacionado con el ID de caja
        $this->tipoCaja = TipoDeCaja::where('id', $this->caja)->first();
        $this->moneda = $this->tipoCaja->t04_tipodemoneda;
        $this->numMov = $numeroMovimiento;
        $this->fechaApertura = (new DateTime($apertura->fecha))->format('d/m/Y');
        $ctaCaja = Cuenta::where('Descripcion', $this->tipoCaja['descripcion'])
                ->firstOrFail()
                ->toArray();
        $glosa = MovimientoDeCaja::where('id_libro','3')
                ->where('id_apertura',$this->aperturaId)
                ->where('id_cuentas',$ctaCaja['id'])
                ->where('mov',$this->numMov)
                ->select('glosa')
                ->first();
        $this->observacion = $glosa->glosa;
        // Cargar los datos del vaucher al inicializar el componente
        $this->loadVaucherData();
    }

    public function loadVaucherData()
    {

        // Log para verificar que el método se ejecuta correctamente
        Log::info('Ejecutando loadVaucherData', [
            'numMov' => $this->numMov,
            'aperturaId' => $this->aperturaId,
        ]);


        // Ejecutar la consulta SQL y almacenar los resultados en $contenedor
        $resultados = DB::select("
    SELECT 
        CON1.id,
        tabla10_tipodecomprobantedepagoodocumento.descripcion AS tipo_documento_descripcion,
        CON1.id_entidades,
        entidades.descripcion AS entidad_descripcion,
        CON1.num,
        CON1.id_t04tipmon,
        CON1.descripcion AS cuenta_descripcion,
        CON1.monto,
        CON1.montodebe,
        CON1.montohaber,
        CON1.montododebe,
        CON1.montodohaber,
        CON1.numero_de_operacion
    FROM (
        SELECT 
            documentos.id,
            documentos.id_t10tdoc,
            documentos.id_entidades,
            IF(documentos.serie IS NOT NULL, CONCAT(documentos.serie, '-', documentos.numero), NULL) AS num,
            documentos.id_t04tipmon,
            cuentas.Descripcion,
            IF(
                cuentas.Descripcion = 'DETRACCIONES POR COBRAR',
                documentos.detraccion,
                IF(documentos.montoNeto IS NOT NULL, documentos.montoNeto, documentos.precio)
            ) AS monto,
            IF(movimientosdecaja.id_dh = '1', movimientosdecaja.monto, NULL) AS montodebe,
            IF(movimientosdecaja.id_dh = '2', movimientosdecaja.monto, NULL) AS montohaber,
            IF(movimientosdecaja.id_dh = '1', movimientosdecaja.montodo, NULL) AS montododebe,
            IF(movimientosdecaja.id_dh = '2', movimientosdecaja.montodo, NULL) AS montodohaber,
            numero_de_operacion
        FROM movimientosdecaja
        LEFT JOIN libros ON movimientosdecaja.id_libro = libros.id
        LEFT JOIN cuentas ON movimientosdecaja.id_cuentas = cuentas.id
        LEFT JOIN documentos ON movimientosdecaja.id_documentos = documentos.id
        WHERE movimientosdecaja.mov = :numMov
        AND movimientosdecaja.id_libro = '3'
        AND movimientosdecaja.id_apertura = :aperturaId
        AND documentos.id_tipmov in ('1','2')
    ) AS CON1
    LEFT JOIN tabla10_tipodecomprobantedepagoodocumento ON CON1.id_t10tdoc = tabla10_tipodecomprobantedepagoodocumento.id
    LEFT JOIN entidades ON CON1.id_entidades = entidades.id
", ['numMov' => $this->numMov, 'aperturaId' => $this->aperturaId]);


        $this->contenedor = array_map(function ($item) {
            return [
                'id_documentos' => $item->id,
                'tdoc' => $item->tipo_documento_descripcion,    // Mapeo de tipo_documento_descripcion a tdoc
                'id_entidades' => $item->id_entidades,
                'RZ' => $item->entidad_descripcion,             // Mapeo de entidad_descripcion a RZ
                'Num' => $item->num,                            // Mapeo de num a Num
                'Mon' => $item->id_t04tipmon,                   // Mapeo de id_t04tipmon a Mon
                'Descripcion' => $item->cuenta_descripcion,     // Mapeo de cuenta_descripcion a Descripcion
                'monto' => $item->monto,                        // Mapeo directo de monto
                'debe' => $item->id_t04tipmon == 'USD' ? $item->montododebe:$item->montodebe,                     // Mapeo de montodebe a debe
                'haber' => $item->id_t04tipmon == 'USD' ? $item->montodohaber:$item->montohaber,                   // Mapeo de montohaber a haber
                'numero_de_operacion' => $item->numero_de_operacion,                 // Mapeo de montohaber a haber
            ];
        }, $resultados);

        $this -> cod_operacion = $this->contenedor[0]['numero_de_operacion'];

        Log::info('Resultados convertidos a array:', [
            'contenedor' => $this->contenedor
        ]);
        // Procesar los totales de debe, haber y balance
        $this->calculateDebeHaber();


        // Log para ver qué retorna la consulta
        Log::info('Resultados de la consulta:', [
            'contenedor' => $this->contenedor
        ]);

        // Verificar si el contenedor está vacío
        if (empty($this->contenedor)) {
            Log::warning('El contenedor está vacío. No se obtuvieron resultados de la consulta.');
        }
    }

    #[On('sendingContenedor')]
    public function updateVaucherData($nuevoContenedor)
    {
        Log::info('Recibido contenedor actualizado:', ['nuevoContenedor' => $nuevoContenedor]);
    
        // Asignar el nuevo contenedor o vaciarlo si no hay elementos
        if (!empty($nuevoContenedor)) {
            $this->contenedor = array_map(function ($item) use ($nuevoContenedor) {
                $nuevoItem = collect($nuevoContenedor)->firstWhere('id_documentos', $item['id_documentos']);
                if ($nuevoItem) {
                    $item['haber'] = $nuevoItem['monto'] ?? 0;  // Asignar el monto a la key 'haber'
                }
                return $item;
            }, $nuevoContenedor);
    
            Log::info('Contenedor actualizado:', ['contenedor' => $this->contenedor]);
        } else {
            $this->contenedor = [];  // Vaciamos el contenedor si no hay datos seleccionados
            Log::info('Contenedor vaciado');
        }
    }
    
    

    public function calculateDebeHaber()
    {
        $this->debe = 0.0;
        $this->haber = 0.0;
    
        // Verificar si el contenedor no está vacío
        if (!empty($this->contenedor)) {
            foreach ($this->contenedor as $item) {
                // Sumar los valores de debe solo si no son nulos y mayores que 0
                if (isset($item['debe']) && $item['debe'] > 0) {
                    $this->debe += $item['debe'];
                    Log::info('Sumando al Debe', ['debe' => $item['debe'], 'debe_actual' => $this->debe]);
                }
    
                // Sumar los valores de haber solo si no son nulos y mayores que 0
                if (isset($item['haber']) && $item['haber'] > 0) {
                    $this->haber += $item['haber'];
                    Log::info('Sumando al Haber', ['haber' => $item['haber'], 'haber_actual' => $this->haber]);
                }
            }
        } else {
            Log::info('El contenedor está vacío, no se realiza cálculo de Debe y Haber');
        }
    
        // Calcular el balance una vez que se hayan calculado Debe y Haber
        $this->calculateBalance();
    
        Log::info('Cálculo finalizado', ['debe' => $this->debe, 'haber' => $this->haber]);
    }
    
    public function calculateBalance()
    {
        // Verificar si tanto el debe como el haber son cero o nulos
        if (($this->debe === 0.0 || $this->debe === null) && ($this->haber === 0 || $this->haber === null)) {
            // Si ambos son cero o nulos, asignar el balance a 0
            $this->balance = 0.0;
            Log::info('Debe y Haber son cero o nulos. Asignando balance a 0.');
            return;
        }
    
        // Si solo uno de ellos es mayor que cero, asignar ese valor al balance
        if ($this->debe === 0.0 || $this->debe === null) {
            $this->balance = $this->haber;
            Log::info('Debe es cero o nulo. Asignando balance al valor de Haber.', ['haber' => $this->haber]);
            return;
        }
    
        if ($this->haber === 0.0 || $this->haber === null) {
            $this->balance = $this->debe;
            Log::info('Haber es cero o nulo. Asignando balance al valor de Debe.', ['debe' => $this->debe]);
            return;
        }
    
        // Si ambos son mayores a cero, calcular el balance normalmente
        $this->balance = $this->debe - $this->haber;
        Log::info('Balance calculado normalmente', ['balance' => $this->balance]);
    }


    public function submit()
    {
        Log::info('Iniciando el proceso de submit en VaucherPagoCompras.');
    
        // Validación de campos
        if (empty($this->fechaApertura) || empty($this->contenedor) || empty($this->observacion)) {
            Log::warning('Falta llenar campos: fecha o contenedor están vacíos.');
            session()->flash('error', 'Falta llenar campos');
            return;
        }
        
        if (count($this->contenedor) <= 0) {
            Log::warning('El contenedor no tiene suficientes detalles.');
            session()->flash('error', 'Debe haber más de un detalle en la transacción.');
            return;
        }
    
        Log::info('Campos validados correctamente.');
    
        DB::beginTransaction(); // Iniciar transacción
    
        try {
            // Eliminar movimientos previos relacionados al numMov
            MovimientoDeCaja::where('id_libro', '3')
                ->where('mov', $this->numMov)
                ->where('id_apertura',$this->aperturaId)
                ->delete();
    
            // Obtener idapt de la apertura
            $idapt = $this->aperturaId;
            Log::info("idapt obtenido correctamente: {$idapt}");
    
            // Obtener el número de movimiento (movc) con bloqueo
            $movc = MovimientoDeCaja::where('id_apertura', $idapt)
                ->lockForUpdate()
                ->orderBy('mov', 'desc')
                ->first()
                ->mov ?? 1;
            $movc++; // Incrementa para el siguiente movimiento
            Log::info("Número de movimiento generado correctamente: {$movc}");
    
            // Si la moneda no es PEN, calcular el tipo de cambio
            $dbr = 0;
            foreach ($this->contenedor as $detalle) {
                if($detalle['Mon'] == 'USD'){
                    $monto = $this -> TipoDeCambio($detalle); 
                    $dbr += $monto;
                }
            }

            if($detalle['Mon'] == 'USD'){
                $debe = $dbr;
                $debedo = $this->haber;
            }else{
                $debe = $this->haber;
                $debedo = null;
            }

            // Insertar el movimiento de caja para el haber
            $ctaCaja = Cuenta::where('Descripcion', $this->tipoCaja['descripcion'])
                ->firstOrFail(); // Utilizar firstOrFail para asegurar que obtengamos un resultado
            Log::info($ctaCaja);
    
            MovimientoDeCaja::create([
                'id_libro' => 3,
                'id_apertura' => $idapt,
                'mov' => $this->numMov,
                'fec' => DateTime::createFromFormat('d/m/Y', $this->fechaApertura)->format('Y-m-d'),
                'id_documentos' => null,
                'id_cuentas' => $ctaCaja->id,
                'id_dh' => 1, // Debe
                'monto' => $debe,
                'montodo' => $debedo,
                'glosa' => $this->observacion,
                'numero_de_operacion' => $this->cod_operacion ?? null,
            ]);
    
            // Procesar cada detalle en el contenedor
            foreach ($this->contenedor as $detalle) {
                $iddoc = $detalle['id_documentos'] ?? 'NULL';
                $glo = $this->observacion;
                Log::info("Procesando detalle: ID Documento: {$iddoc}, Glosa: {$glo}");
    
                // Obtener la cuenta
                $cta = Cuenta::where('Descripcion', $detalle['Descripcion'])->firstOrFail()->id;
                Log::info("Cuenta obtenida: {$cta}");

                if($detalle['Mon'] == 'USD'){
                    $monto = $this -> TipoDeCambio($detalle); 
                    $montodo = $detalle['haber'];
                }else{
                    $monto = $detalle['haber'];
                    $montodo = null;
                }
    
                // Insertar el movimiento de Haber
                MovimientoDeCaja::create([
                    'id_libro' => 3,
                    'id_apertura' => $idapt,
                    'mov' => $this->numMov,
                    'fec' => DateTime::createFromFormat('d/m/Y', $this->fechaApertura)->format('Y-m-d'),
                    'id_documentos' => $iddoc,
                    'id_cuentas' => $cta,
                    'id_dh' => 2, // Haber
                    'monto' => $monto,
                    'montodo' => $montodo,
                    'glosa' => $glo,
                    'numero_de_operacion' => $this->cod_operacion ?? null,
                ]);
    
                Log::info("Movimiento de caja insertado: ID Cuenta: {$cta}, Debe/Haber: 2, Monto: {$detalle['haber']}");
            }
    
            DB::commit(); // Confirmar la transacción
    
            // Si todo salió bien
            session()->flash('message', 'Transacción Exitosa.');
            Log::info('Transacción procesada exitosamente.');
            return $this->redirect(route('apertura.edit', ['aperturaId' => $this->aperturaId]), navigate: true);
    
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción si ocurre un error
            Log::error('Error insertando movimiento de caja: ' . $e->getMessage());
            session()->flash('error', 'Error al procesar los detalles.');
            return;
        }
    
        // Resetear los campos después de procesar la transacción
        $this->reset(['fechaApertura', 'contenedor', 'debe', 'haber', 'balance']);
        Log::info('Formulario reseteado.');
    }
    
    public function TipoDeCambio($data){

        $fechaFormateada = Carbon::createFromFormat('d/m/Y', $this->fechaApertura)->format('Y-m-d');

        Log::info('Fecha formateada correctamente', [
            'fecha_original' => $this->fechaApertura,
            'fecha_formateada' => $fechaFormateada
        ]);
        
        $cuenta = Cuenta::where('descripcion', $data['Descripcion'])->first(); // Obtener un solo registro

        $dh = $cuenta->id_tcuenta == 2 ? 1 : 2; // Acceder correctamente a la propiedad del objeto

        $consulta = DB::select("
            SELECT 
                id_documentos, 
                id_cuentas, 
                ROUND(SUM(IF(id_dh = :dh1, monto, monto * -1)), 2) AS total_monto,
                ROUND(SUM(IF(id_dh = :dh2, montodo, montodo * -1)), 2) AS total_montodo 
            FROM movimientosdecaja 
            WHERE id_cuentas = :cuenta 
            AND id_documentos = :id 
            GROUP BY id_cuentas, id_documentos;
        ", [
            'dh1' => $dh,
            'dh2' => $dh,
            'cuenta' => $cuenta->id, // Acceder correctamente al ID de la cuenta
            'id' => $data['id_documentos'],
        ]);
        
        $resultado = $consulta[0];

        if ($resultado->total_montodo - $data['haber'] == 0){
            return $resultado->total_monto;
        }else{
            $tipoCambio = TipoDeCambioSunat::where('fecha',$fechaFormateada)
            ->lockForUpdate()
            ->first()->venta ?? 1;
    
            $montoConvertido = round($data['haber'] * $tipoCambio, 2);
            
            Log::info('Se aplicó tipo de cambio', [
                'fecha' => $fechaFormateada,
                'tipo_cambio' => $tipoCambio,
                'monto_original' => $data['haber'],
                'monto_convertido' => $montoConvertido
            ]);
        
            return $montoConvertido;
        }

    }

    public function editMonto($index)
    {
        $this->editingIndex = $index;
        $this->editingMonto = $this->contenedor[$index]['haber'];
        Log::info('Editando monto para la fila', ['index' => $index, 'debe' => $this->editingMonto]);
    }

    // Función para guardar el monto editado
    // Función para guardar el monto editado
    public function saveMonto($index)
    {
        if ($this->editingMonto === null || $this->editingMonto === '') {
            $this->warningMessage[$index] = "Necesitas añadir un monto"; // Mensaje de advertencia para la fila actual
            return;
        }

        // Si el monto es válido, guardamos el valor y limpiamos advertencias
        $this->contenedor[$index]['haber'] = $this->editingMonto;
        $this->warningMessage[$index] = null; // Limpiar advertencia
        $this->editingIndex = null; // Deshabilitar la edición
        $this->editingMonto = null; // Limpiar el valor temporal
        $this->calculateDebeHaber(); // Recalcular debe y haber con el nuevo monto
    }

    public function cancelEdit()
    {
        // Limpiar el índice de edición y el monto editado
        $this->editingIndex = null;
        $this->editingMonto = null;
        $this->warningMessage = null; // Limpiar advertencia si existía
    }

    public function render()
    {
        return view('livewire.edit-vaucher-de-pago-ventas');
    }
}
