<?php

namespace App\Livewire;

use Livewire\Component;
use DateTime;
use App\Models\Apertura;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\TipoDeCaja;
use App\Models\MovimientoDeCaja;
use App\Models\TipoDeCambioSunat;
use App\Models\Cuenta;
use Livewire\Attributes\On;
use Carbon\Carbon;


class EditVaucherDePago extends Component
{

    public $aperturaId;
    public $numMov;
    public $fechaApertura;
    public $moneda = "PEN";
    public $contenedor = []; // Variable para almacenar los datos recibidos
    public $debe = 0.0; // Variable para almacenar el total del debe
    public $haber = 0.0; // Variable para almacenar el total del haber
    public $balance = 0.0; // Nueva variable para el balance
    public $selectedIndex = null;

    public $caja;
    public $tipoCaja;
    public $fecha;
    public $editingIndex = null; // Para rastrear la fila en edición
    public $editingMonto = null; // Para almacenar temporalmente el valor del monto que se está editando
    public $warningMessage = [];
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
                cuentas.Descripcion = 'DETRACCIONES POR PAGAR',
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
                'numero_de_operacion' => $item->numero_de_operacion,
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

    public function calculateDebeHaber()
    {
        $this->debe = 0.0;
        $this->haber = 0.0;

        // Verificar si el contenedor no está vacío
        if (!empty($this->contenedor)) {
            foreach ($this->contenedor as $item) {
                // Sumar los valores de debe y haber
                if (isset($item['debe'])) {
                    $this->debe += $item['debe'];
                    Log::info('Sumando al Debe', ['debe' => $item['debe'], 'debe_actual' => $this->debe]);
                }
                if (isset($item['haber'])) {
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
        $this->balance = $this->debe - $this->haber;
        Log::info('Balance calculado', ['balance' => $this->balance]);
    }




    public function editMonto($index)
    {
        $this->editingIndex = $index;
        $this->editingMonto = $this->contenedor[$index]['debe'];
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
        $this->contenedor[$index]['debe'] = $this->editingMonto;
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

    public function submit()
    {
        Log::info('Iniciando el proceso de submit en VaucherPagoCompras.');
        Log::info($this->contenedor);
        Log::info($this->fechaApertura);
    
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
    
        DB::beginTransaction(); // Iniciar una transacción para asegurar atomicidad
    
        try {
            // Eliminar movimientos previos relacionados al numMov
            MovimientoDeCaja::where('id_libro', '3')
                ->where('mov', $this->numMov)
                ->where('id_apertura',$this->aperturaId)
                ->delete();
    
            // Obtener idapt de la apertura
            $idapt = $this->aperturaId;
            Log::info("idapt obtenido correctamente: {$idapt}");
    
            // Obtener el número de movimiento (movc) con bloqueo pesimista
            $movc = MovimientoDeCaja::where('id_apertura', $idapt)
                ->lockForUpdate() // Evitar que otro proceso acceda mientras este está en ejecución
                ->orderBy('mov', 'desc')
                ->first()
                ->mov ?? 1;
            $movc++; // Incrementa para el siguiente movimiento
            Log::info("Número de movimiento generado correctamente: {$movc}");
    
            $hbr = 0;
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
                    $montodo = $detalle['debe'];
                    $hbr += $monto;
                }else{
                    $monto = $detalle['debe'];
                    $montodo = null;
                }

                // Insertar el movimiento de Debe
                MovimientoDeCaja::create([
                    'id_libro' => 3,
                    'id_apertura' => $idapt,
                    'mov' => $this->numMov,
                    'fec' => DateTime::createFromFormat('d/m/Y', $this->fechaApertura)->format('Y-m-d'),
                    'id_documentos' => $iddoc,
                    'id_cuentas' => $cta,
                    'id_dh' => 1, // Debe
                    'monto' => $monto,
                    'montodo' => $montodo,
                    'glosa' => $glo,
                    'numero_de_operacion' => $this->cod_operacion ?? null,
                ]);
    
                Log::info("Movimiento de caja insertado: ID Cuenta: {$cta}, Debe/Haber: 1, Monto: {$detalle['debe']}");
            }
    
            // Obtener la cuenta de caja
            $ctaCaja = Cuenta::where('Descripcion', $this->tipoCaja['descripcion'])
                ->firstOrFail()
                ->toArray();

                if($detalle['Mon'] == 'USD'){
                    $haber = $hbr;
                    $haberdo = $this->debe;
                }else{
                    $haber = $this->debe;
                    $haberdo = null;
                }
    
            // Insertar el movimiento para el haber (PAGO DE CXP)
            MovimientoDeCaja::create([
                'id_libro' => 3,
                'id_apertura' => $idapt,
                'mov' => $this->numMov,
                'fec' => DateTime::createFromFormat('d/m/Y', $this->fechaApertura)->format('Y-m-d'),
                'id_documentos' => null,
                'id_cuentas' => $ctaCaja['id'],
                'id_dh' => 2, // Haber
                'monto' => $haber,
                'montodo' => $haberdo,
                'glosa' => $this->observacion,
                'numero_de_operacion' => $this->cod_operacion ?? null,
            ]);
    
            DB::commit(); // Confirmar la transacción
            Log::info('Transacción procesada exitosamente.');
    
            // Si todo salió bien
            session()->flash('message', 'Transacción Exitosa.');
            return $this->redirect(route('apertura.edit', ['aperturaId' => $this->aperturaId]), navigate: true);
    
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir los cambios si ocurre un error
            Log::error('Error insertando movimiento de caja: ' . $e->getMessage());
            session()->flash('error', 'Error al procesar los detalles.');
        }
    
        // Resetear los campos después de procesar la transacción
        $this->reset(['fechaApertura', 'contenedor', 'debe', 'haber', 'balance']);
        Log::info('Formulario reseteado.');
    }
    


      // Escuchar el evento desde el modal
      #[On('updateVaucherData')]
      public function updateVaucherData($nuevoContenedor)
      {
          Log::info('Recibido contenedor actualizado:', ['nuevoContenedor' => $nuevoContenedor]);
      
          // Asignar directamente el nuevo contenedor
          $this->contenedor = array_map(function ($item) use ($nuevoContenedor) {
              $nuevoItem = collect($nuevoContenedor)->firstWhere('id_documentos', $item['id_documentos']);
              if ($nuevoItem) {
                  // Asignar el monto de 'debe' a partir del nuevo contenedor
                  $item['debe'] = $nuevoItem['monto'] ?? 0; // Asignar monto o 0 si no existe
              }
              return $item;
          }, $nuevoContenedor); // Cambiar esto para que el nuevo contenedor reemplace el actual
      
          Log::info('Contenedor actualizado:', ['contenedor' => $this->contenedor]);
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

        if ($resultado->total_montodo - $data['debe'] == 0){
            return $resultado->total_monto;
        }else{
            $tipoCambio = TipoDeCambioSunat::where('fecha',$fechaFormateada)
            ->lockForUpdate()
            ->first()->venta ?? 1;
    
            $montoConvertido = round($data['debe'] * $tipoCambio, 2);
            
            Log::info('Se aplicó tipo de cambio', [
                'fecha' => $fechaFormateada,
                'tipo_cambio' => $tipoCambio,
                'monto_original' => $data['debe'],
                'monto_convertido' => $montoConvertido
            ]);
        
            return $montoConvertido;
        }

    }


    public function render()
    {
        return view('livewire.edit-vaucher-de-pago');
    }
}
