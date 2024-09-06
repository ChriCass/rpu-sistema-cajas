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

    public function mount($aperturaId)
    {
        $this->aperturaId = $aperturaId;

        // Recuperar la fecha directamente usando el aperturaId
        $apertura = Apertura::findOrFail($aperturaId);
        $this->fechaApertura = (new DateTime($apertura->fecha))->format('d/m/Y');

        Log::info('Fecha de apertura establecida', ['fechaApertura' => $this->fechaApertura]);
    }

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

    public function selectDebe($index)
    {
        if ($this->selectedIndex === $index) {
            // Si el índice seleccionado es el mismo, se deselecciona
            $this->selectedIndex = null;
            $this->debe = 0.0; // Limpiar el campo "Debe"
            Log::info('Fila deseleccionada', ['index' => $index]);
        } else {
            // Si es una nueva selección, se actualiza
            $this->selectedIndex = $index;
            $this->debe = $this->contenedor[$index]['monto'];
            Log::info('Fila seleccionada', ['index' => $index, 'debe' => $this->debe]);
        }

        // Recalcular el balance cada vez que se selecciona una nueva fila
        $this->calculateBalance();
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

    if (count($this->contenedor) <= 1) {
        Log::warning('El contenedor no tiene suficientes detalles.');
        session()->flash('error', 'Debe haber más de un detalle en la transacción.');
        return;
    }

    Log::info('Campos validados correctamente.');

    // Obtener idapt de la apertura
    try {
        $idapt = Apertura::join('Tesoreria.tipoDeCaja', 'Tesoreria.aperturas.id_tipo', '=', 'Tesoreria.tipoDeCaja.id')
            ->join('General.meses', 'General.meses.id', '=', 'Tesoreria.aperturas.id_mes')
            ->where('numero', $this->numero)
            ->where('General.meses.descripcion', $this->mes)
            ->where('año', $this->año)
            ->where('Tesoreria.tipoDeCaja.descripcion', $this->tipoDeCaja)
            ->firstOrFail()
            ->id;
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

    // Procesar cada detalle en el contenedor
    try {
        foreach ($this->contenedor as $detalle) {
            $iddoc = $detalle['iddoc'] ?? 'NULL';
            $glo = $detalle['descripcion'];
            Log::info("Procesando detalle: ID Documento: {$iddoc}, Glosa: {$glo}");

            // Obtener la cuenta
            $cta = Cuenta::where('Descripcion', $detalle['cuenta'])->firstOrFail()->id;
            Log::info("Cuenta obtenida: {$cta}");

            // Determinar si es Debe o Haber y calcular el monto
            if (isset($detalle['debe'])) {
                $dh = 1; // Debe
                $monto = $detalle['debe'] * $tipoCambio;
            } else {
                $dh = 2; // Haber
                $monto = $detalle['haber'] * $tipoCambio;
            }

            // Insertar el movimiento en la base de datos
            MovimientoDeCaja::create([
                'id_libro' => 3,
                'id_apertura' => $idapt,
                'mov' => $movc,
                'fec' => $this->fechaApertura,
                'id_documentos' => $iddoc,
                'id_cuentas' => $cta,
                'id_dh' => $dh,
                'monto' => $monto,
                'montodo' => null,
                'glosa' => $glo,
            ]);
            Log::info("Movimiento de caja insertado: ID Cuenta: {$cta}, Debe/Haber: {$dh}, Monto: {$monto}");
        }
    } catch (\Exception $e) {
        Log::error('Error insertando movimiento de caja: ' . $e->getMessage());
        session()->flash('error', 'Error al procesar los detalles.');
        return;
    }

    // Cálculo del balance
    $this->balance = $this->TotalDebe - $this->TotalHaber;
    Log::info("Balance calculado: {$this->balance}");

    if ($this->balance != 0) {
        Log::warning("El balance no cuadra: {$this->balance}");
        session()->flash('error', 'El asiento no cuadra');
        return;
    }

    // Si todo salió bien
    session()->flash('message', 'Transacción Exitosa.');
    Log::info('Transacción procesada exitosamente.');

    // Resetear los campos después de procesar la transacción
    $this->reset(['fechaApertura', 'contenedor', 'debe', 'haber', 'balance']);
    Log::info('Formulario reseteado.');
}


    public function render()
    {
        return view('livewire.vaucher-pago-compras', ['fechaApertura' => $this->fechaApertura , 'moneda' => $this->moneda])->layout('layouts.app');
    }
}
