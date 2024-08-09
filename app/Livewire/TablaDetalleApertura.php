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

            // Consulta para obtener el monto inicial
            $montoInicialQuery = MovimientoDeCaja::select(DB::raw("SUM(IF(id_dh = '1', monto, monto * -1)) as monto"))
                ->where('id_cuentas', $this->caja)
                ->where('fec', '<', $fechaFormatted);

            Log::info('Consulta SQL Generada:', ['query' => $montoInicialQuery->toSql(), 'bindings' => $montoInicialQuery->getBindings()]);

            $montoInicial = $montoInicialQuery->first();

            if ($montoInicial) {
                $this->textBox8 = $montoInicial->monto ?? 0;
            } else {
                $this->textBox8 = 0;
                Log::info('No se encontró un registro coincidente para la consulta del monto inicial.');
            }

            Log::info('Monto inicial calculado', ['montoInicial' => $this->textBox8]);

            // Definir y ejecutar la consulta SQL crudo
            $sql = "
                SELECT 
                    IF(CO2.id_documentos IS NULL, CO2.mov, CO2.id_documentos) AS mov_id,
                    IF(familias.descripcion IS NULL, 'MOVIMIENTOS', familias.descripcion) AS familia,
                    IF(subfamilias.desripcion IS NULL, '', subfamilias.desripcion) AS subfamilia,
                    IF(CO2.descripcion IS NULL, '', CO2.descripcion) AS detalle,
                    IF(entidades.descripcion IS NULL, '', entidades.descripcion) AS entidad,
                    CO2.numero,
                    CO2.monto,
                    CO2.glosa
                FROM (
                    SELECT 
                        CO1.mov,
                        CO1.id_documentos,
                        detalle.id_familias,
                        detalle.id_subfamilia,
                        detalle.descripcion,
                        CO1.id_entidades,
                        CO1.numero,
                        CO1.monto,
                        CO1.glosa
                    FROM (
                        SELECT 
                            movimientosdecaja.mov,
                            movimientosdecaja.id_documentos,
                            IF(movimientosdecaja.id_dh = '1', ventas_documentos.id_detalle, INN1.id_detalle) AS id_detalle,
                            IF(movimientosdecaja.id_dh = '1', ventas_documentos.id_entidades, INN1.id_entidades) AS id_entidades,
                            IF(movimientosdecaja.id_dh = '1', CONCAT(ventas_documentos.serie, '-', ventas_documentos.numero), CONCAT(INN1.serie, '-', INN1.numero)) AS numero,
                            IF(movimientosdecaja.id_dh = '1', movimientosdecaja.monto, movimientosdecaja.monto * -1) AS monto,
                            movimientosdecaja.glosa
                        FROM 
                            movimientosdecaja
                        LEFT JOIN 
                            ventas_documentos ON movimientosdecaja.id_documentos = ventas_documentos.id
                        LEFT JOIN 
                            (SELECT * FROM compras_documentos) INN1 ON movimientosdecaja.id_documentos = INN1.id
                        WHERE 
                            movimientosdecaja.id_cuentas = ? 
                            AND movimientosdecaja.id_apertura = ?
                    ) CO1
                    LEFT JOIN 
                        detalle ON CO1.id_detalle = detalle.id
                ) CO2
                LEFT JOIN 
                    familias ON CO2.id_familias = familias.id
                LEFT JOIN 
                    subfamilias ON CONCAT(CO2.id_familias, CO2.id_subfamilia) = CONCAT(subfamilias.id_familias, subfamilias.id)
                LEFT JOIN 
                    entidades ON CO2.id_entidades = entidades.id
                ORDER BY 
                    CO2.mov
            ";

            // Ejecutar la consulta utilizando SQL crudo y convertir el resultado en una colección
            $this->movimientos = collect(DB::select($sql, [$this->caja, $this->aperturaId]));

            // Log para verificar los resultados
            Log::info('Movimientos obtenidos con SQL crudo:', ['movimientos' => $this->movimientos]);

            // Calcular el total de la tabla
            $this->textBox4 = $this->textBox8 + $this->movimientos->sum('monto');
            Log::info('Total calculado', ['total' => $this->textBox4]);
        } catch (ModelNotFoundException $e) {
            Log::error('Cuenta no encontrada: ' . $this->comboBox5);
            session()->flash('error', 'No se encontró la cuenta con la descripción: ' . $this->comboBox5);
        }
    }

    public function render()
    {
        return view('livewire.tabla-detalle-apertura', [
            'movimientos' => $this->movimientos
        ]);
    }
}
