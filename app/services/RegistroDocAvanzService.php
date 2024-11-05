<?php

namespace App\Services;

 
use App\Models\Detalle;
 
use Illuminate\Support\Facades\Log;
use App\Models\Documento;
use App\Models\Apertura;
 

 
use App\Models\TipoDeCaja;
use App\Models\TipoDeCambioSunat;
use App\Models\MovimientoDeCaja;
use App\Models\Producto;
 
use App\Models\DDetalleDocumento;
use App\Models\Cuenta;

use Illuminate\Support\Facades\Auth;
 
use Illuminate\Support\Facades\DB;


class RegistroDocAvanzService
{
    public function guardarDocumento(array $data)
    {
        DB::beginTransaction();

        try {
            // Validar si el documento ya está registrado con bloqueo pesimista
            $documentoExistente = Documento::where('id_entidades', $data['docIdent'])
                ->where('id_t10tdoc', $data['tipoDocumento'])
                ->where('serie', $data['serieNumero1'])
                ->where('numero', $data['serieNumero2'])
                ->where('id_tipmov', '1')  // cxc (puede variarse según lógica)
                ->lockForUpdate()
                ->first();

            if ($documentoExistente) {
                return ['error' => 'Documento ya registrado'];
            }

            // Insertar el nuevo documento
            $nuevoDocumento = Documento::create([
                'id_tipmov' => 1,
                'fechaEmi' => $data['fechaEmi'],
                'fechaVen' => $data['fechaVen'],
                'id_t10tdoc' => $data['tipoDocumento'],
                'id_t02tcom' => $data['tipoDocId'],
                'id_entidades' => $data['docIdent'],
                'id_t04tipmon' => $data['monedaId'],
                'id_tasasIgv' => $this->mapearTasaIgv($data['tasaIgvId']),
                'serie' => $data['serieNumero1'],
                'numero' => $data['serieNumero2'],
                'basImp' => $data['basImp'],
                'IGV' => $data['igv'],
                'noGravadas' => $data['noGravado'],
                'precio' => $data['precio'],
                'observaciones' => $data['observaciones'],
                'id_user' => $data['user'] ?? Auth::user()->id,
                'fecha_Registro' => now(),
                'id_dest_tipcaja' => $data['destinatarioVisible'] ? $data['nuevoDestinatario'] : null,
            ]);

            // Registrar detalle del documento
            $this->registrarDetalleDocumento($nuevoDocumento->id, $data);

            // Registrar movimiento en caja
            $this->registrarMovimientoCaja($nuevoDocumento->id, $data);

            DB::commit();

            return ['success' => 'Documento registrado con éxito'];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al registrar el documento', ['exception' => $e]);
            return ['error' => 'Ocurrió un error al registrar el documento'];
        }
    }

    protected function mapearTasaIgv(string $tasaIgv): int
    {
        return match ($tasaIgv) {
            '18%' => 1,
            '10%' => 2,
            'No Gravado' => 0,
            default => 0,
        };
    }

    protected function registrarDetalleDocumento(int $documentoId, array $data)
    {
        $producto = Producto::select('id')
            ->where('id_detalle', $data['detalleId'])
            ->where('descripcion', 'GENERAL')
            ->lockForUpdate()
            ->first();

        DDetalleDocumento::create([
            'id_referencia' => $documentoId,
            'orden' => '1',
            'id_producto' => $producto->id,
            'id_tasas' => '1',
            'cantidad' => '1',
            'cu' => $data['precio'],
            'total' => $data['precio'],
            'id_centroDeCostos' => $data['centroDeCostos'] ?? null,
        ]);
    }

    public function registrarMovimientoCaja(int $documentoId, array $data)
    {
        $libro = ($data['familiaId'] === '001') ? '5' : '1';
        $cuentaId = $this->obtenerCuentaId($data['familiaId'], $data['detalleId']);

        $precioConvertido = $this->convertirPrecio($data['monedaId'], $data['precio'], $data['fechaEmi']);

        // Obtener último número de movimiento
        $ultimoMovimiento = MovimientoDeCaja::where('id_libro', $libro)
            ->lockForUpdate()
            ->orderByRaw('CAST(mov AS UNSIGNED) DESC')
            ->first();
        $nuevoMov = $ultimoMovimiento ? intval($ultimoMovimiento->mov) + 1 : 1;

        if ($data['familiaId'] === '002') { // INGRESOS
            MovimientoDeCaja::create([
                'id_libro' => $libro,
                'mov' => $nuevoMov,
                'fec' => $data['fechaEmi'],
                'id_documentos' => $documentoId,
                'id_cuentas' => 1,
                'id_dh' => 1,
                'monto' => $precioConvertido,
                'montodo' => null,
                'glosa' => $data['observaciones'],
            ]);
        }

        // Registrar movimientos relacionados con la apertura
        $this->registrarAperturaRelacionada($documentoId, $nuevoMov, $precioConvertido, $data);
    }

    protected function obtenerCuentaId(string $familiaId, $detalleId): ?int
    {
        if ($familiaId === '001') {
            return 9; // Transferencias
        }

        $detalle = Detalle::lockForUpdate()->find($detalleId);
        return $detalle->id_cuenta ?? null;
    }

    protected function convertirPrecio($monedaId, $precio, $fechaEmi)
    {
        if ($monedaId === 'USD') {
            $tipoCambio = TipoDeCambioSunat::lockForUpdate()
                ->where('fecha', $fechaEmi)
                ->first()->venta ?? 1;
            return round($precio * $tipoCambio, 2);
        }

        return $precio;
    }

    protected function registrarAperturaRelacionada(int $documentoId, int $nuevoMov, float $precioConvertido, array $data)
    {
        $apertura = Apertura::where('numero', $data['apertura']['numero'])
            ->where('id_tipo', $data['apertura']['id_tipo'])
            ->whereHas('mes', fn($query) => $query->where('descripcion', $data['apertura']['mes']['descripcion']))
            ->where('año', $data['apertura']['año'])
            ->lockForUpdate()
            ->first();

        if ($apertura) {
            $nuevoMovApertura = $this->obtenerNuevoMovApertura($apertura->id);
            $cuenta = $this->obtenerCuentaDesdeTipoCaja($data['tipoCaja']);

            // Registro de transacción en caja
            MovimientoDeCaja::create([
                'id_libro' => 3,
                'id_apertura' => $apertura->id,
                'mov' => $nuevoMovApertura,
                'fec' => $apertura->fecha,
                'id_documentos' => $documentoId,
                'id_cuentas' => $cuenta->id,
                'id_dh' => 1,
                'monto' => $precioConvertido,
                'montodo' => null,
                'glosa' => $data['observaciones'],
            ]);

            // Pago de documento
            MovimientoDeCaja::create([
                'id_libro' => 3,
                'id_apertura' => $apertura->id,
                'mov' => $nuevoMovApertura,
                'fec' => $apertura->fecha,
                'id_documentos' => $documentoId,
                'id_cuentas' => $data['cuentaId'],
                'id_dh' => 2,
                'monto' => $precioConvertido,
                'montodo' => null,
                'glosa' => $data['observaciones'],
            ]);
        }
    }

    protected function obtenerNuevoMovApertura(int $aperturaId): int
    {
        $ultimoMovimientoApertura = MovimientoDeCaja::where('id_apertura', $aperturaId)
            ->lockForUpdate()
            ->orderByRaw('CAST(mov AS UNSIGNED) DESC')
            ->first();
        return $ultimoMovimientoApertura ? intval($ultimoMovimientoApertura->mov) + 1 : 1;
    }

    protected function obtenerCuentaDesdeTipoCaja($tipoCaja)
    {
        $descaja = TipoDeCaja::select('descripcion')
            ->where('id', $tipoCaja)
            ->lockForUpdate()
            ->first();
        return Cuenta::select('id')
            ->where('descripcion', $descaja->descripcion)
            ->lockForUpdate()
            ->first();
    }
}
