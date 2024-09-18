<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cuenta;
use App\Models\TipoDeCuenta;
use App\Models\MovimientoDeCaja;
use App\Models\Apertura;
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

    public function mount($aperturaId)
    {
        $this->aperturaId = $aperturaId;
        $this->movimientos = collect(); // Asegurarse de que sea una colección vacía
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

            // Consulta para obtener el monto inicial y redondearlo a dos decimales
            $montoInicialQuery = MovimientoDeCaja::select(DB::raw("ROUND(SUM(IF(id_dh = '1', monto, monto * -1)), 2) as monto"))
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

            // Nueva consulta SQL cruda proporcionada
            $sql = "
            SELECT 
                IF(documentos.id IS NULL, movimientosdecaja.mov, documentos.id) AS NumeroMovimiento, -- Número de movimiento o documento
                IF(familias.descripcion IS NULL, 'MOVIMIENTOS', familias.descripcion) AS Familia, -- Descripción de la familia o 'MOVIMIENTOS'
                IF(subfamilias.desripcion IS NULL, '', subfamilias.desripcion) AS SubFamilia, -- Descripción de la subfamilia
                detalle.descripcion AS Detalle, -- Descripción del detalle
                IF(l_productos.descripcion IS NULL, '', l_productos.descripcion) AS DetalleProducto, -- Descripción del producto
                IF(entidades.descripcion IS NULL, '', entidades.descripcion) AS Entidad, -- Descripción de la entidad relacionada
                CONCAT(documentos.serie, '-', documentos.numero) AS NumeroDocumento, -- Número del documento (serie y número)
                movimientosdecaja.monto AS Monto, -- Monto del documento (unificado)
                documentos.observaciones AS Glosa -- Glosa o descripción adicional
            FROM 
                movimientosdecaja
            LEFT JOIN 
                documentos ON movimientosdecaja.id_documentos = documentos.id
            LEFT JOIN 
                d_detalledocumentos ON documentos.id = d_detalledocumentos.id_referencia -- Relación con el detalle de los documentos
            LEFT JOIN 
                l_productos ON d_detalledocumentos.id_producto = l_productos.id -- Relación con los productos
            LEFT JOIN 
                detalle ON l_productos.id_detalle = detalle.id -- Relación con el detalle de la familia y subfamilia
            LEFT JOIN 
                familias ON detalle.id_familias = familias.id
            LEFT JOIN 
                subfamilias ON CONCAT(detalle.id_familias, detalle.id_subfamilia) = CONCAT(subfamilias.id_familias, subfamilias.id)
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
            $this->textBox4 = $this->textBox8 + $this->movimientos->sum('monto');
            Log::info('Total calculado', ['total' => $this->textBox4]);

            // Despachar los eventos con los valores calculados
            $this->dispatch('monto-inicial', $this->textBox8);
            $this->dispatch('total-calculado', $this->textBox4);
        } catch (ModelNotFoundException $e) {
            Log::error('Cuenta no encontrada: ' . $this->comboBox5);
            session()->flash('error', 'No se encontró la cuenta con la descripción: ' . $this->comboBox5);
        }
    }




    public function editarMovimiento($monto, $numeroMovimiento)
    {
        Log::info("Editar Movimiento: Monto={$monto}, NumeroMovimiento={$numeroMovimiento}");

        // Determinar si el monto es positivo o negativo
        if ($monto > 0) {
            // Enviar booleano a AperturaEditParent para mostrar ed-registro-documentos-ingreso
            $this->dispatch('mostrarComponente', 'EditarIngreso', $numeroMovimiento);
            Log::info("Despachando evento 'mostrarComponente' para EditarIngreso con NumeroMovimiento={$numeroMovimiento}");
        } else {
            // Enviar booleano a AperturaEditParent para mostrar ed-registro-documentos-egreso
            $this->dispatch('mostrarComponente', 'EditarSalida', $numeroMovimiento);
            Log::info("Despachando evento 'mostrarComponente' para EditarSalida con NumeroMovimiento={$numeroMovimiento}");
        }
    }


    public function render()
    {
        return view('livewire.tabla-detalle-apertura', [
            'movimientos' => $this->movimientos
        ]);
    }
}
