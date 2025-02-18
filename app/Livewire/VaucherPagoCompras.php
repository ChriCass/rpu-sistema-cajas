<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;
use App\Models\Apertura;
use DateTime;
use App\Models\TipoDeCambioSunat;
use App\Models\MovimientoDeCaja;
use App\Models\Cuenta;
use App\Models\TipoDeCaja;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class VaucherPagoCompras extends Component
{
    public $aperturaId;
    public $fechaApertura;
    public $moneda = "PEN";
    public $contenedor = []; // Variable para almacenar los datos recibidos
    public $debe = 0.0; // Variable para almacenar el total del debe
    public $haber = 0.0; // Variable para almacenar el total del haber
    public $balance = 0.0; // Nueva variable para el balance
    public $selectedIndex = null;

    public $caja;
    public $tipoCaja;


    public $editingIndex = null; // Para rastrear la fila en edición
    public $editingMonto = null; // Para almacenar temporalmente el valor del monto que se está editando
    public $warningMessage = [];
    public $cod_operacion;
    public function mount($aperturaId)
    {
        $this->aperturaId = $aperturaId;

        // Recuperar la apertura y el tipo de caja
        $apertura = Apertura::findOrFail($aperturaId);
        $this->caja = $apertura->id_tipo;

        // Obtener el tipo de caja relacionado con el ID de caja
        $this->tipoCaja = TipoDeCaja::where('id', $this->caja)->first();
        $this->moneda = $this->tipoCaja->t04_tipodemoneda;

        // Registrar el tipo de caja en el log
        if ($this->tipoCaja) {
            Log::info('Tipo de caja encontrado', ['tipoCaja' => $this->tipoCaja->descripcion]);
        } else {
            Log::warning('Tipo de caja no encontrado para el ID', ['id' => $this->caja]);
        }

        // Recuperar la fecha de apertura
        $this->fechaApertura = (new DateTime($apertura->fecha))->format('d/m/Y');
        Log::info('Fecha de apertura establecida', ['fechaApertura' => $this->fechaApertura]);

        // Verificar si el contenedor está vacío y agregar valores de prueba si es necesario
        /* if (empty($this->contenedor)) {
            $this->generateTestValues();
        } */
    }

    // Función para habilitar la edición del monto
    public function editMonto($index)
    {
        $this->editingIndex = $index;
        $this->editingMonto = $this->contenedor[$index]['monto'];
        Log::info('Editando monto para la fila', ['index' => $index, 'monto' => $this->editingMonto]);
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
        $this->contenedor[$index]['monto'] = $this->editingMonto;
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


    // Nueva función para generar valores de prueba
    /** public function generateTestValues()
    {
        // Generar datos ficticios para pruebas
        $this->contenedor = [
            [
                'id_documentos' => 1,
                'tdoc' => 'Factura',
                'id_entidades' => 1001,
                'RZ' => 'Empresa Ficticia S.A.',
                'Num' => 'F001-00012345',
                'Mon' => 'PEN',
                'Descripcion' => 'Pago de servicios',
                'monto' => 1000.00,
                'debe' => 1000.00,
                'haber' => 0.00,
            ],
            [
                'id_documentos' => 2,
                'tdoc' => 'Boleta',
                'id_entidades' => 1002,
                'RZ' => 'Comercio Ficticio S.A.',
                'Num' => 'B001-00054321',
                'Mon' => 'PEN',
                'Descripcion' => 'Compra de suministros',
                'monto' => 500.00,
                'debe' => 0.00,
                'haber' => 500.00,
            ],
        ];

        Log::info('Valores de prueba generados en el contenedor', ['contenedor' => $this->contenedor]);

        // Calcular debe y haber con los valores generados
        $this->calculateDebeHaber();
    }  */

    #[On('sendingContenedor')]
    public function handleSendingContenedor($contenedor)
    {
        // Guardar los datos recibidos en la variable contenedor
        $this->contenedor = $contenedor;

        // Realizar los cálculos de "Debe" y "Haber"
        $this->calculateDebeHaber();

        Log::info('Datos recibidos en VaucherPagoCompras', ['contenedor' => $this->contenedor]);
    }

    public function calculateDebeHaber()
    {
        $this->debe = 0.0;
        $this->haber = 0.0;

        // Verificar si el contenedor no está vacío
        if (!empty($this->contenedor)) {
            // Asignar directamente el "Debe" al monto del primer elemento del contenedor
            if (isset($this->contenedor[0]['monto'])) {
                $this->debe = $this->contenedor[0]['monto'];
                Log::info('Asignando Debe', ['debe' => $this->debe]);
            }

            // El "Haber" se calcula en base a si hay más de un elemento en el contenedor
            if (count($this->contenedor) > 1) {
                foreach ($this->contenedor as $item) {
                    if (isset($item['monto'])) {
                        $this->haber += $item['monto'];
                        Log::info('Sumando al Haber', ['monto' => $item['monto'], 'haber_actual' => $this->haber]);
                    }
                }
            } else {
                // Si hay un solo elemento, el "Haber" será igual al monto de ese único elemento
                $this->haber = $this->debe;
                Log::info('Asignando Haber igual al Debe por único elemento', ['haber_actual' => $this->haber]);
            }
        } else {
            Log::info('El contenedor está vacío, no se realiza cálculo de Debe y Haber');
        }

        // Calcular el balance una vez que se hayan calculado Debe y Haber
        $this->calculateBalance();

        Log::info('Cálculo finalizado', ['debe' => $this->debe, 'haber' => $this->haber]);
    }

    // Nueva función para calcular el balance
    public function calculateBalance()
    {
        $this->balance = $this->debe - $this->haber;
        Log::info('Balance calculado', ['balance' => $this->balance]);
    }


    public function submit()
    {
        Log::info('Iniciando el proceso de submit en VaucherPagoCompras.');
    
        // Validación de campos
        if (empty($this->fechaApertura) || empty($this->contenedor)) {
            Log::warning('Falta llenar campos: fecha o contenedor están vacíos.');
            session()->flash('error', 'Falta llenar campos');
            return;
        }
        Log::info(count($this->contenedor));
        if (count($this->contenedor) <= 0) {
            Log::warning('El contenedor no tiene suficientes detalles.');
            session()->flash('error', 'Debe haber más de un detalle en la transacción.');
            return;
        }
    
        Log::info('Campos validados correctamente.');
    
        DB::beginTransaction(); // Iniciar transacción para asegurar atomicidad
    
        try {
            // Obtener idapt de la apertura
            $idapt = $this->aperturaId;
            Log::info("idapt obtenido correctamente: {$idapt}");
    
            // Obtener el número de movimiento (movc) con bloqueo pesimista
            $movc = MovimientoDeCaja::where('id_apertura', $idapt)
                ->lockForUpdate() // Bloquear para evitar concurrencia
                ->orderBy('mov', 'desc')
                ->first()
                ->mov ?? 1;
            $movc++; // Incrementa para el siguiente movimiento
            Log::info("Número de movimiento generado correctamente: {$movc}");
    
            Log::info($this->contenedor);

            $hbr = 0;
    
            // Procesar cada detalle en el contenedor
            foreach ($this->contenedor as $detalle) {
                $iddoc = $detalle['id_documentos'] ?? 'NULL';
                $glo = $detalle['RZ'] . ' ' . $detalle['Num'];
                Log::info("Procesando detalle: ID Documento: {$iddoc}, Glosa: {$glo}");
    
                // Obtener la cuenta
                $cta = Cuenta::where('Descripcion', $detalle['Descripcion'])->firstOrFail()->id;
                Log::info("Cuenta obtenida: {$cta}");
    
                // Determinar si es Debe o Haber y calcular el monto
                $dh = 1; // Debe
                if($detalle['Mon'] == 'USD'){
                    $monto = $this -> TipoDeCambio($detalle); 
                    $montodo = $detalle['monto'];
                    $hbr += $monto;
                }else{
                    $monto = $detalle['monto'];
                    $montodo = null;
                }
                
    
                // Insertar el movimiento en la base de datos
                MovimientoDeCaja::create([
                    'id_libro' => 3,
                    'id_apertura' => $idapt,
                    'mov' => $movc,
                    'fec' => DateTime::createFromFormat('d/m/Y', $this->fechaApertura)->format('Y-m-d'),
                    'id_documentos' => $iddoc,
                    'id_cuentas' => $cta,
                    'id_dh' => $dh,
                    'monto' => $monto,
                    'montodo' => $montodo,
                    'glosa' => $glo,
                    'numero_de_operacion' => $this->cod_operacion ?? null,
                ]);
    
                Log::info("Movimiento de caja insertado: ID Cuenta: {$cta}, Debe/Haber: {$dh}, Monto: {$monto}");
            }
    
            // Crear el movimiento de caja correspondiente al pago de CXP
            $ctaCaja = Cuenta::where('Descripcion', $this->tipoCaja['descripcion'])->firstOrFail()->id;
            Log::info("Cuenta de caja obtenida: {$ctaCaja}");
    
            if($detalle['Mon'] == 'USD'){
                $haber = $hbr;
                $haberdo = $this->haber;
            }else{
                $haber = $this->haber;
                $haberdo = null;
            }
                

            MovimientoDeCaja::create([
                'id_libro' => 3,
                'id_apertura' => $idapt,
                'mov' => $movc,
                'fec' => DateTime::createFromFormat('d/m/Y', $this->fechaApertura)->format('Y-m-d'),
                'id_documentos' => null,
                'id_cuentas' => $ctaCaja,
                'id_dh' => 2,
                'monto' => $haber,
                'montodo' => $haberdo,
                'glosa' => 'PAGO DE CXP',
                'numero_de_operacion' => $this->cod_operacion ?? null,
            ]);
    
            DB::commit(); // Confirmar la transacción
    
            // Si todo salió bien
            session()->flash('message', 'Transacción Exitosa.');
            return $this->redirect(route('apertura.edit', ['aperturaId' => $this->aperturaId]), navigate: true);
    
            Log::info('Transacción procesada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción en caso de error
            Log::error('Error en el proceso de submit: ' . $e->getMessage());
            session()->flash('error', 'Error al procesar la transacción.');
        }
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

        if ($resultado->total_montodo - $data['monto'] == 0){
            return $resultado->total_monto;
        }else{
            $tipoCambio = TipoDeCambioSunat::where('fecha',$fechaFormateada)
            ->lockForUpdate()
            ->first()->venta ?? 1;
    
            $montoConvertido = round($data['monto'] * $tipoCambio, 2);
            
            Log::info('Se aplicó tipo de cambio', [
                'fecha' => $fechaFormateada,
                'tipo_cambio' => $tipoCambio,
                'monto_original' => $data['monto'],
                'monto_convertido' => $montoConvertido
            ]);
        
            return $montoConvertido;
        }

    }

    public function render()
    {
        return view('livewire.vaucher-pago-compras', ['fechaApertura' => $this->fechaApertura, 'moneda' => $this->moneda]);
    }
}
