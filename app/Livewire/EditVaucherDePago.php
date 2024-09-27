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


    public function mount($numeroMovimiento, $aperturaId)
    {
        $this->aperturaId = $aperturaId;

        // Recuperar la apertura y el tipo de caja
        $apertura = Apertura::findOrFail($this->aperturaId);
        $this->caja = $apertura->id_tipo;

        // Obtener el tipo de caja relacionado con el ID de caja
        $this->tipoCaja = TipoDeCaja::where('id', $this->caja)->first();
        $this->numMov = $numeroMovimiento;
        $this->fecha = (new DateTime($apertura->fecha))->format('d/m/Y');

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
        CON1.montodohaber
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
            IF(movimientosdecaja.id_dh = '2', movimientosdecaja.montodo, NULL) AS montodohaber
        FROM movimientosdecaja
        LEFT JOIN libros ON movimientosdecaja.id_libro = libros.id
        LEFT JOIN cuentas ON movimientosdecaja.id_cuentas = cuentas.id
        LEFT JOIN documentos ON movimientosdecaja.id_documentos = documentos.id
        WHERE movimientosdecaja.mov = :numMov
        AND movimientosdecaja.id_libro = '3'
        AND movimientosdecaja.id_apertura = :aperturaId
        AND documentos.id_tipmov = '2'
    ) AS CON1
    LEFT JOIN tabla10_tipodecomprobantedepagoodocumento ON CON1.id_t10tdoc = tabla10_tipodecomprobantedepagoodocumento.id
    LEFT JOIN entidades ON CON1.id_entidades = entidades.id
", ['numMov' => $this->numMov, 'aperturaId' => $this->aperturaId]);


        $this->contenedor = array_map(function ($item) {
            return [
                'id' => $item->id,
                'tdoc' => $item->tipo_documento_descripcion,    // Mapeo de tipo_documento_descripcion a tdoc
                'id_entidades' => $item->id_entidades,
                'RZ' => $item->entidad_descripcion,             // Mapeo de entidad_descripcion a RZ
                'Num' => $item->num,                            // Mapeo de num a Num
                'Mon' => $item->id_t04tipmon,                   // Mapeo de id_t04tipmon a Mon
                'Descripcion' => $item->cuenta_descripcion,     // Mapeo de cuenta_descripcion a Descripcion
                'monto' => $item->monto,                        // Mapeo directo de monto
                'debe' => $item->montodebe,                     // Mapeo de montodebe a debe
                'haber' => $item->montohaber                    // Mapeo de montohaber a haber
            ];
        }, $resultados);

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


        // Obtener idapt de la apertura
        try {
            $idapt = $this->aperturaId;
            Log::info("idapt obtenido correctamente: {$idapt}");
        } catch (\Exception $e) {
            Log::error('Error obteniendo idapt: ' . $e->getMessage());
            session()->flash('error', 'Error al obtener la apertura.');
            return;
        }

        // Obtener el número de movimiento (movc)
        try {
            $movc = MovimientoDeCaja::where('id_apertura', $idapt)
                ->orderBy('mov', 'desc')
                ->first()
                ->mov ?? 1;
            $movc++; // Incrementa para el siguiente movimiento
            Log::info("Número de movimiento generado correctamente: {$movc}");
        } catch (\Exception $e) {
            Log::error('Error obteniendo movc: ' . $e->getMessage());
            session()->flash('error', 'Error al generar el número de movimiento.');
            return;
        }

        // Si la moneda no es PEN, calcular el tipo de cambio
        $tipoCambio = 1;
        if ($this->moneda !== 'PEN') {
            try {
                $tipoCambio = TipoDeCambioSunat::where('fecha', $this->fechaApertura)->value('venta');
                Log::info("Tipo de cambio obtenido: {$tipoCambio}");
            } catch (\Exception $e) {
                Log::error('Error obteniendo el tipo de cambio: ' . $e->getMessage());
                session()->flash('error', 'Error al obtener el tipo de cambio.');
                return;
            }
        }
        Log::info($this->contenedor);

        // Procesar cada detalle en el contenedor
        try {
            foreach ($this->contenedor as $detalle) {

                $iddoc = $detalle['id_documentos'] ?? 'NULL';
                $glo = $detalle['RZ'] . ' ' . $detalle['Num'];
                Log::info(DateTime::createFromFormat('d/m/Y', $this->fechaApertura)->format('Y-m-d'));
                Log::info("Procesando detalle: ID Documento: {$iddoc}, Glosa: {$glo}");
                // Obtener la cuenta
                $cta = Cuenta::where('Descripcion', $detalle['Descripcion'])->firstOrFail()->id;
                Log::info("Cuenta obtenida: {$cta}");

                // Determinar si es Debe o Haber y calcular el monto
                $dh = 1; // Debe
                $monto = $detalle['monto'];
                // Insertar el movimiento en la base de datoss
                MovimientoDeCaja::create([
                    'id_libro' => 3,
                    'id_apertura' => $idapt,
                    'mov' => $movc,
                    'fec' => DateTime::createFromFormat('d/m/Y', $this->fechaApertura)->format('Y-m-d'),
                    'id_documentos' => $iddoc,
                    'id_cuentas' => $cta,
                    'id_dh' => $dh,
                    'monto' => $monto,
                    'montodo' => null,
                    'glosa' => $glo,
                ]);

                Log::info("Movimiento de caja insertado: ID Cuenta: {$cta}, Debe/Haber: {$dh}, Monto: {$monto}");
            }

            $ctaCaja = Cuenta::where('Descripcion', $this->tipoCaja['descripcion'])
                ->get()
                ->toarray();

            Log::info($ctaCaja);

            MovimientoDeCaja::create([
                'id_libro' => 3,
                'id_apertura' => $idapt,
                'mov' => $movc,
                'fec' => DateTime::createFromFormat('d/m/Y', $this->fechaApertura)->format('Y-m-d'),
                'id_documentos' => null,
                'id_cuentas' => $ctaCaja[0]['id'],
                'id_dh' => 2,
                'monto' => $this->haber,
                'montodo' => null,
                'glosa' => 'PAGO DE CXP',
            ]);
        } catch (\Exception $e) {
            Log::error('Error insertando movimiento de caja: ' . $e->getMessage());
            session()->flash('error', 'Error al procesar los detalles.');
            return;
        }

        /* 
        // Cálculo del balance
        $this->balance = $this->TotalDebe - $this->TotalHaber;
        Log::info("Balance calculado: {$this->balance}");

        if ($this->balance != 0) {
            Log::warning("El balance no cuadra: {$this->balance}");
            session()->flash('error', 'El asiento no cuadra');
            return;
        }
        */

        // Si todo salió bien
        session()->flash('message', 'Transacción Exitosa.');
       
        // Redireccionar como SPA
        return $this->redirect(route('apertura.edit', ['aperturaId' => $this->aperturaId]), navigate: true);
        Log::info('Transacción procesada exitosamente.');

        // Resetear los campos después de procesar la transacción
        $this->reset(['fechaApertura', 'contenedor', 'debe', 'haber', 'balance']);
        ///  Log::info('Formulario reseteado.');
        ///  $this->dispatch('actualizar-tabla-apertura', $this->aperturaId);

        // Lógica de procesamiento (puedes dejar el resto de la lógica tal como está)

        // Si todo salió bien
        
     //   Log::info('Transacción procesada exitosamente.');

       
       
    }


    public function render()
    {
        return view('livewire.edit-vaucher-de-pago');
    }
}
