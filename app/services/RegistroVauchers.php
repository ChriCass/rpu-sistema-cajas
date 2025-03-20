<?php

namespace App\Services;

 
use App\Models\Detalle;
 
use Illuminate\Support\Facades\Log;
use App\Models\Documento;
use App\Models\Apertura;
use App\Models\CentroDeCostos;

 
use App\Models\TipoDeCaja;
use App\Models\TipoDeCambioSunat;
use App\Models\MovimientoDeCaja;
use App\Models\Producto;
 
use App\Models\DDetalleDocumento;
use App\Models\Cuenta;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
 
use Illuminate\Support\Facades\DB;


class RegistroVauchers
{
    public function guardarVaucher(array $data)
    {
        //DB::beginTransaction();
        //try {
            // Obtener id de apertura
            $idapt = $data['APERTURA'];
            Log::info("idapt obtenido correctamente: {$idapt}");

            // Obtener el último número de movimiento y bloquear para evitar concurrencia
            $movc = MovimientoDeCaja::where('id_apertura', $idapt)
                ->lockForUpdate()
                ->orderBy('mov', 'desc')
                ->first()
                ->mov ?? 1;

            $movc++; // Incrementa para el siguiente movimiento
            Log::info("Número de movimiento generado correctamente: {$movc}");

            // Calcular montos según moneda
            $dbr = 0;
            foreach ($data['DATOS'] as $detalle) {
                if ($data['MONEDA'] == 'USD') {
                    $monto = $this->TipoDeCambio($detalle, $data);
                    $dbr += $monto;
                }
            }

            if ($data['MONEDA'] == 'USD') {
                $debe = $dbr;
                $debedo = $data['TOTAL'];
            } else {
                $debe = $data['TOTAL'];
                $debedo = null;
            }

            Log::info("Montos calculados: Debe: {$debe}, Monto Dólares: {$debedo}");

            // Obtener la cuenta de caja desde las aperturas
            $ctaCaja = Apertura::leftJoin('tipodecaja', 'aperturas.id_tipo', '=', 'tipodecaja.id')
                ->leftJoin('cuentas', 'tipodecaja.descripcion', '=', 'cuentas.descripcion')
                ->where('aperturas.id', $data['APERTURA'])
                ->value('cuentas.id');

            Log::info("Cuenta de caja obtenida: " . ($ctaCaja ?? 'No encontrada'));

            // Insertar movimiento para PAGO DE CXC
            if ($data['TIPOMOVIENTO'] == 'CXC') {
                
                Log::info("Insertando movimiento de caja principal...", [
                    'id_libro' => 3,
                    'id_apertura' => $idapt,
                    'mov' => $movc,
                    'fec' => $data['FECHA'],
                    'id_documentos' => null,
                    'id_cuentas' => $ctaCaja,
                    'id_dh' => 1, // Debe
                    'monto' => $debe,
                    'montodo' => $debedo,
                    'glosa' => $data['DATOS'][0]['OBSERVACION'],
                    'numero_de_operacion' => $data['DATOS'][0]['NUMERO DE OPERACIÓN'] ?? null,
                ]);

                $movCaja = MovimientoDeCaja::create([
                    'id_libro' => 3,
                    'id_apertura' => $idapt,
                    'mov' => $movc,
                    'fec' => $data['FECHA'],
                    'id_documentos' => null,
                    'id_cuentas' => $ctaCaja,
                    'id_dh' => 1, // Debe
                    'monto' => $debe,
                    'montodo' => $debedo,
                    'glosa' => $data['DATOS'][0]['OBSERVACION'],
                    'numero_de_operacion' => $data['DATOS'][0]['NUMERO DE OPERACIÓN'] ?? null,
                ]);

                Log::info("Movimiento principal insertado: ID Apertura: {$idapt}, Mov: {$movc}, ID Cuenta: {$ctaCaja}");

                // Procesar cada detalle
                foreach ($data['DATOS'] as $detalle) {
                    $iddoc = $detalle['DOCUMENTO'] ?? 'NULL';
                    $glo = $detalle['OBSERVACION'];
                    Log::info("Procesando detalle: ID Documento: {$iddoc}, Glosa: {$glo}");

                    // Obtener la cuenta
                    $cta = $detalle['CUENTA'];
                    Log::info("Cuenta obtenida: {$cta}");

                    // Calcular montos según moneda
                    if ($data['MONEDA'] == 'USD') {
                        $monto = $this->TipoDeCambio($detalle, $data);
                        $montodo = $detalle['MONTO'];
                    } else {
                        $monto = $detalle['MONTO'];
                        $montodo = null;
                    }

                    Log::info("Insertando movimiento de caja...", [
                        'id_libro' => 3,
                        'id_apertura' => $idapt,
                        'mov' => $movc,
                        'fec' => $data['FECHA'],
                        'id_documentos' => $iddoc,
                        'id_cuentas' => $cta,
                        'id_dh' => 2, // Haber
                        'monto' => $monto,
                        'montodo' => $montodo,
                        'glosa' => $glo,
                        'numero_de_operacion' => $detalle['NUMERO DE OPERACIÓN'] ?? null,
                    ]);

                    // Insertar el movimiento en la base de datos
                    $movDetalle = MovimientoDeCaja::create([
                        'id_libro' => 3,
                        'id_apertura' => $idapt,
                        'mov' => $movc,
                        'fec' => $data['FECHA'],
                        'id_documentos' => $iddoc,
                        'id_cuentas' => $cta,
                        'id_dh' => 2, // Haber
                        'monto' => $monto,
                        'montodo' => $montodo,
                        'glosa' => $glo,
                        'numero_de_operacion' => $detalle['NUMERO DE OPERACIÓN'] ?? null,
                    ]);

                    Log::info("Movimiento de caja insertado: ID Cuenta: {$cta}, Debe/Haber: 2, Monto: {$monto}");
                }
            }else{
                // Procesar cada detalle
                foreach ($data['DATOS'] as $detalle) {
                    $iddoc = $detalle['DOCUMENTO'] ?? 'NULL';
                    $glo = $detalle['OBSERVACION'];
                    Log::info("Procesando detalle: ID Documento: {$iddoc}, Glosa: {$glo}");

                    // Obtener la cuenta
                    $cta = $detalle['CUENTA'];
                    Log::info("Cuenta obtenida: {$cta}");

                    // Calcular montos según moneda
                    if ($data['MONEDA'] == 'USD') {
                        $monto = $this->TipoDeCambio($detalle, $data);
                        $montodo = $detalle['MONTO'];
                    } else {
                        $monto = $detalle['MONTO'];
                        $montodo = null;
                    }

                    Log::info("Insertando movimiento de caja...", [
                        'id_libro' => 3,
                        'id_apertura' => $idapt,
                        'mov' => $movc,
                        'fec' => $data['FECHA'],
                        'id_documentos' => $iddoc,
                        'id_cuentas' => $cta,
                        'id_dh' => 1, // Haber
                        'monto' => $monto,
                        'montodo' => $montodo,
                        'glosa' => $glo,
                        'numero_de_operacion' => $detalle['NUMERO DE OPERACIÓN'] ?? null,
                    ]);

                    // Insertar el movimiento en la base de datos
                    $movDetalle = MovimientoDeCaja::create([
                        'id_libro' => 3,
                        'id_apertura' => $idapt,
                        'mov' => $movc,
                        'fec' => $data['FECHA'],
                        'id_documentos' => $iddoc,
                        'id_cuentas' => $cta,
                        'id_dh' => 1, // Haber
                        'monto' => $monto,
                        'montodo' => $montodo,
                        'glosa' => $glo,
                        'numero_de_operacion' => $detalle['NUMERO DE OPERACIÓN'] ?? null,
                    ]);

                    Log::info("Movimiento de caja insertado: ID Cuenta: {$cta}, Debe/Haber: 2, Monto: {$monto}");
                }
                Log::info("Insertando movimiento de caja principal...", [
                    'id_libro' => 3,
                    'id_apertura' => $idapt,
                    'mov' => $movc,
                    'fec' => $data['FECHA'],
                    'id_documentos' => null,
                    'id_cuentas' => $ctaCaja,
                    'id_dh' => 1, // Debe
                    'monto' => $debe,
                    'montodo' => $debedo,
                    'glosa' => $data['DATOS'][0]['OBSERVACION'],
                    'numero_de_operacion' => $data['DATOS'][0]['NUMERO DE OPERACIÓN'] ?? null,
                ]);

                $movCaja = MovimientoDeCaja::create([
                    'id_libro' => 3,
                    'id_apertura' => $idapt,
                    'mov' => $movc,
                    'fec' => $data['FECHA'],
                    'id_documentos' => null,
                    'id_cuentas' => $ctaCaja,
                    'id_dh' => 2, // Debe
                    'monto' => $debe,
                    'montodo' => $debedo,
                    'glosa' => $data['DATOS'][0]['OBSERVACION'],
                    'numero_de_operacion' => $data['DATOS'][0]['NUMERO DE OPERACIÓN'] ?? null,
                ]);

                Log::info("Movimiento principal insertado: ID Apertura: {$idapt}, Mov: {$movc}, ID Cuenta: {$ctaCaja}");
            }            
        //} catch (\Exception $e) {
          //  DB::rollBack();
            //Log::error('Error al registrar el documento', [
              //  'exception' => $e->getMessage(),
                //'trace' => $e->getTraceAsString(),
           // ]);
           // return ['error' => 'Ocurrió un error al registrar el documento'];
        //}
    }

    public function TipoDeCambio($detalle,$data){

        $fechaFormateada = $data['FECHA'];
        
        $cuenta = $detalle['CUENTA']; // Obtener un solo registro

        $dh = cuenta::where('id',$cuenta)->select('descripcion')->value() == 2 ? 1 : 2; // Acceder correctamente a la propiedad del objeto

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

        if ($resultado->total_montodo - $detalle['MONTO'] == 0){
            return $resultado->total_monto;
        }else{
            $tipoCambio = TipoDeCambioSunat::where('fecha',$fechaFormateada)
            ->lockForUpdate()
            ->first()->venta ?? 1;
    
            $montoConvertido = round($detalle['MONTO'] * $tipoCambio, 2);
            
            Log::info('Se aplicó tipo de cambio', [
                'fecha' => $fechaFormateada,
                'tipo_cambio' => $tipoCambio,
                'monto_original' => $detalle['MONTO'],
                'monto_convertido' => $montoConvertido
            ]);
        
            return $montoConvertido;
        }

    }
}
