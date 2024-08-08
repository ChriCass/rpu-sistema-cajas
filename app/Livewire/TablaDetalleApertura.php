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
class TablaDetalleApertura extends Component
{   
    public $aperturaId;
    public $caja;
    public $movimientos = [];
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

            // Calcular el monto inicial
            $fechaFormatted = date('Y-m-d', strtotime($this->textBox6));
            $montoInicial = MovimientoDeCaja::select(DB::raw("SUM(IF(id_dh = '1', monto, monto * -1)) as monto"))
                ->where('id_cuentas', $this->caja)
                ->where('fec', '<', $fechaFormatted)
                ->first();
            
            $this->textBox8 = $montoInicial->monto ?? 0;
            Log::info('Monto inicial calculado', ['montoInicial' => $this->textBox8]);

            // Obtener los movimientos de caja relacionados con la apertura
            $this->movimientos = MovimientoDeCaja::select(
                DB::raw("IF(id_documentos IS NULL, mov, id_documentos) as mov_id"),
                DB::raw("IF(familias.descripcion IS NULL, 'MOVIMIENTOS', familias.descripcion) as familia"),
                DB::raw("IF(subfamilias.desripcion IS NULL, '', subfamilias.desripcion) as subfamilia"),
                'detalle.descripcion as detalle',
                'entidades.descripcion as entidad',
                DB::raw('IFNULL(ventas_documentos.serie, compras_documentos.serie) || "-" || IFNULL(ventas_documentos.numero, compras_documentos.numero) as numero'),
                DB::raw('IF(movimientosdecaja.id_dh = 1, movimientosdecaja.monto, movimientosdecaja.monto * -1) as monto'),
                'movimientosdecaja.glosa'
            )
            ->leftJoin('documentos as ventas_documentos', function($join) {
                $join->on('movimientosdecaja.id_documentos', '=', 'ventas_documentos.id')
                    ->where('movimientosdecaja.id_dh', '=', 1);
            })
            ->leftJoin('documentos as compras_documentos', function($join) {
                $join->on('movimientosdecaja.id_documentos', '=', 'compras_documentos.id')
                    ->where('movimientosdecaja.id_dh', '=', 0);
            })
            ->leftJoin('detalle', function($join) {
                $join->on('ventas_documentos.id_detalle', '=', 'detalle.id')
                    ->orOn('compras_documentos.id_detalle', '=', 'detalle.id');
            })
            ->leftJoin('familias', 'detalle.id_familias', '=', 'familias.id')
            ->leftJoin('subfamilias', function ($join) {
                $join->on('detalle.id_familias', '=', 'subfamilias.id_familias')
                     ->on('detalle.id_subfamilia', '=', 'subfamilias.id');
            })
            ->leftJoin('entidades', function($join) {
                $join->on('ventas_documentos.id_entidades', '=', 'entidades.id')
                    ->orOn('compras_documentos.id_entidades', '=', 'entidades.id');
            })
            ->where('movimientosdecaja.id_cuentas', $this->caja)
            ->where('movimientosdecaja.id_apertura', $this->aperturaId)
            ->orderBy('movimientosdecaja.mov')
            ->get();
            Log::info('Movimientos de caja obtenidos', ['movimientos' => $this->movimientos]);

            // Calcular el total de la tabla
            $this->textBox4 = $this->textBox8 + $this->movimientos->sum('monto');
            Log::info('Total calculado', ['total' => $this->textBox4]);
        } catch (ModelNotFoundException $e) {
            // Manejar el caso donde no se encuentra la cuenta
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