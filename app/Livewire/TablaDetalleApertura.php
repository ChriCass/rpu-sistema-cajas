<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cuenta;
use App\Models\TipoDeCuenta;
use App\Models\MovimientoDeCaja;
use App\Models\Apertura;
use App\Models\Documento;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Attributes\On;
use Illuminate\Support\Collection;
use DateTime;

class TablaDetalleApertura extends Component
{
    public $aperturaId;
    public $caja;
    public $movimientos;
    public $comboBox5;
    public $comboBox6;
    public $comboBox7;
    public $textBox6;
    public $textBox7;
    public $textBox8;
    public $textBox4;
    public $rutaFormulario;
    public $moneda;

    public function mount($aperturaId,$moneda)
    {
        $this->aperturaId = $aperturaId;
        $this->movimientos = collect(); // Asegurarse de que sea una colección vacía
        $this->consultaBD();
        $this->$moneda = $moneda;
    }


    #[On('actualizar-tabla-apertura')]
    public function actualizarTabla($aperturaId)
    {
        // Si el evento incluye un id de apertura, actualiza la propiedad
        if ($aperturaId) {
            $this->aperturaId = $aperturaId;
        }

        // Llama a la consulta para actualizar los datos
        $this->consultaBD();
    }

    public function consultaBD()
    {
        try {
            // Obtener detalles de la apertura
            $apertura = Apertura::select(
                'aperturas.id',
                'tipodecaja.descripcion as tipoCajaDescripcion',
                'numero',
                'año',
                'meses.descripcion as mesDescripcion',
                DB::raw("DATE_FORMAT(fecha, '%d/%m/%Y') as fecha")
            )
                ->leftJoin('tipodecaja', 'tipodecaja.id', '=', 'aperturas.id_tipo')
                ->leftJoin('meses', 'meses.id', '=', 'aperturas.id_mes')
                ->where('aperturas.id', $this->aperturaId)
                ->firstOrFail();

            Log::info('Detalles de la apertura obtenidos', ['apertura' => $apertura]);

            // Llenar los campos del formulario
            $this->comboBox5 = $apertura->tipoCajaDescripcion;
            $this->comboBox6 = $apertura->año;
            $this->comboBox7 = $apertura->mesDescripcion;
            $this->textBox6 = $apertura->fecha;
            $this->textBox7 = $apertura->numero;
            Log::info('Campos del formulario llenados', [
                'comboBox5' => $this->comboBox5,
                'comboBox6' => $this->comboBox6,
                'comboBox7' => $this->comboBox7,
                'textBox6' => $this->textBox6,
                'textBox7' => $this->textBox7
            ]);

            // Obtener ID de la cuenta de caja
            $cuenta = Cuenta::where('descripcion', $this->comboBox5)->firstOrFail();
            $this->caja = $cuenta->id;
            Log::info('ID de la cuenta de caja obtenido', ['caja' => $this->caja]);

            // Convertir la fecha de 'd/m/Y' a 'Y-m-d'
            $fechaFormatted = DateTime::createFromFormat('d/m/Y', $this->textBox6)->format('Y-m-d');
            Log::info('Fecha formateada:', ['fechaFormatted' => $fechaFormatted]);

            if($this->moneda == 'USD'){
                $query = "ROUND(SUM(IF(id_dh = '1', montodo, montodo * -1)), 2) as monto";
            }else{
                $query = "ROUND(SUM(IF(id_dh = '1', monto, monto * -1)), 2) as monto";
            }

            // Consulta para obtener el monto inicial y redondearlo a dos decimales
            $montoInicialQuery = MovimientoDeCaja::select(DB::raw($query))
                ->where('id_cuentas', $this->caja)
                ->where('fec', '<', $fechaFormatted);

            Log::info('Consulta SQL Generada:', ['query' => $montoInicialQuery->toSql(), 'bindings' => $montoInicialQuery->getBindings()]);

            $montoInicial = $montoInicialQuery->first();

            if ($montoInicial) {
                $this->textBox8 = number_format($montoInicial->monto, 2, '.', '');
            } else {
                $this->textBox8 = 0;
                Log::info('No se encontró un registro coincidente para la consulta del monto inicial.');
            }

            Log::info('Monto inicial calculado', ['montoInicial' => $this->textBox8]);


            if($this->moneda == 'USD'){
                $query = "if(id_dh = '1',movimientosdecaja.montodo,movimientosdecaja.montodo*-1) AS Monto";
            }else{
                $query = "if(id_dh = '1',movimientosdecaja.monto,movimientosdecaja.monto*-1) AS Monto";
            }

            // Nueva consulta SQL cruda proporcionada
            $sql = "
            SELECT 
                IF(documentos.id IS NULL, movimientosdecaja.mov, documentos.id) AS NumeroMovimiento, -- Número de movimiento o documento
                IF(entidades.descripcion IS NULL, 'MOVIMIENTOS', entidades.descripcion) AS Entidad, -- Descripción de la entidad relacionada
                CONCAT(documentos.serie, '-', documentos.numero) AS NumeroDocumento, -- Número del documento (serie y número)
                ".$query.", -- Monto del documento (unificado)
                movimientosdecaja.glosa AS Glosa, -- Glosa o descripción adicional
                numero_de_operacion
            FROM 
                movimientosdecaja
            LEFT JOIN 
                documentos ON movimientosdecaja.id_documentos = documentos.id
            LEFT JOIN 
                entidades ON documentos.id_entidades = entidades.id
            WHERE 
                movimientosdecaja.id_cuentas = ? -- Filtramos por la cuenta con ID proporcionado
                AND movimientosdecaja.id_apertura = ? -- Filtramos por el ID de apertura proporcionado
            ORDER BY 
                movimientosdecaja.mov
        ";

            // Log de caja y aperturaId antes de enviar la consulta SQL
            Log::info('Preparando consulta SQL', [
                'caja' => $this->caja,
                'aperturaId' => $this->aperturaId
            ]);

            // Ejecutar la consulta utilizando SQL crudo y convertir el resultado en una colección
            $this->movimientos = collect(DB::select($sql, [$this->caja, $this->aperturaId]));

            // Log para verificar los resultados
            Log::info('Movimientos obtenidos con SQL crudo:', ['movimientos' => $this->movimientos]);

            // Calcular el total de la tabla
            Log::info('Suma', ['suma' => $this->movimientos->sum('Monto')]);
            $this->textBox4 = $this->textBox8 + $this->movimientos->sum('Monto');
            Log::info('Total calculado', ['total' => $this->textBox4]);

            // Despachar los eventos con los valores calculados
            $this->dispatch('monto-inicial', $this->textBox8);
            $this->dispatch('total-calculado', $this->textBox4);
        } catch (ModelNotFoundException $e) {
            Log::error('Cuenta no encontrada: ' . $this->comboBox5);
            session()->flash('error', 'No se encontró la cuenta con la descripción: ' . $this->comboBox5);
        }
    }




    public function editarMovimiento($monto, $numeroMovimiento, $familia)
    {
        Log::info("Editar Movimiento: Monto={$monto}, NumeroMovimiento={$numeroMovimiento}, Familia={$familia}");
        $form = Documento::where('id',$numeroMovimiento)->get()->toarray();
        // Si la familia es "Movimientos" y el monto es menor a cero
        if ($familia === 'MOVIMIENTOS' && $monto < 0) {
            // Mostrar componente edit-vaucher-de-pago-compras
            $this->dispatch('mostrarComponente', 'EditVaucherDePagoCompras', $numeroMovimiento);
            Log::info("Despachando evento 'mostrarComponente' para EditVaucherDePagoCompras con NumeroMovimiento={$numeroMovimiento}");
        }
        // Si la familia es "Movimientos" y el monto es mayor a cero
        elseif ($familia === 'MOVIMIENTOS' && $monto > 0) {
            // Mostrar componente edit-vaucher-de-pago-ventas
            $this->dispatch('mostrarComponente', 'EditVaucherDePagoVentas', $numeroMovimiento);
            Log::info("Despachando evento 'mostrarComponente' para EditVaucherDePagoVentas con NumeroMovimiento={$numeroMovimiento}");
        }
        // Si el monto es positivo y no cae en las categorías anteriores
        elseif ($monto > 0) {
            if($form[0]['id_tip_form']=='2'){
                $this->redirect(route('apertura.avanzado', ['aperturaId' => $this->aperturaId, 'numeroMovimiento' => $numeroMovimiento , 'origen' => 'editar_ingreso' ]));
            }else{
                // Enviar booleano para mostrar ed-registro-documentos-ingreso
                $this->dispatch('mostrarComponente', 'EditarIngreso', $numeroMovimiento);
                Log::info("Despachando evento 'mostrarComponente' para EditarIngreso con NumeroMovimiento={$numeroMovimiento}");   
            }
        }
        // Si el monto es negativo y no cae en las categorías anteriores
        else {
            if($form[0]['id_tip_form']=='2'){
                $this->redirect(route('apertura.avanzado', ['aperturaId' => $this->aperturaId, 'numeroMovimiento' => $numeroMovimiento , 'origen' => 'editar_egreso' ]));
            }else{
                // Enviar booleano para mostrar ed-registro-documentos-egreso
                $this->dispatch('mostrarComponente', 'EditarSalida', $numeroMovimiento);
                Log::info("Despachando evento 'mostrarComponente' para EditarSalida con NumeroMovimiento={$numeroMovimiento}");
            }
        }
    }


    public function render()
    {
        return view('livewire.tabla-detalle-apertura', [
            'movimientos' => $this->movimientos
        ]);
    }
}
