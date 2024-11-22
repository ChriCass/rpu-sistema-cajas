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

use Illuminate\Support\Facades\Auth;
 
use Illuminate\Support\Facades\DB;


class RegistroDocAvanzService
{
    public function guardarDocumento(array $data)
    {
        DB::beginTransaction();

        try {
            Log::info('Iniciando proceso de registro de documento', ['data' => $data]);

            // Validar si el documento ya está registrado con bloqueo pesimista
            $documentoExistente = Documento::where('id_entidades', $data['docIdent'])
                ->where('id_t10tdoc', $data['tipoDocumento'])
                ->where('serie', $data['serieNumero1'])
                ->where('numero', $data['serieNumero2'])
                ->where('id_tipmov', '1')  // cxc (puede variarse según lógica)
                ->lockForUpdate()
                ->first();

            if($data['origen'] == 'ingreso' || $data['origen'] == 'egreso' || $data['origen'] == 'cxc' || $data['origen'] == 'cxp'){
                if ($documentoExistente) {
                    Log::warning('Intento de registrar un documento ya existente', [
                        'documentoExistente' => $documentoExistente,
                        'data' => $data,
                    ]);
                    return ['error' => 'Documento ya registrado'];
                }
            }

            if($data['origen'] == 'ingreso' || $data['origen'] == 'editar ingreso' || $data['origen'] == 'cxc' || $data['origen'] == 'editar cxc'){
                $idTipMov = 1;
            }else{
                $idTipMov = 2;
            }

            if($data['origen'] == 'ingreso' || $data['origen'] == 'egreso'  || $data['origen'] == 'cxc' || $data['origen'] == 'cxp'){
                // Insertar el nuevo documento
                $nuevoDocumento = Documento::create([
                    'id_tipmov' => $idTipMov,
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
                    'detraccion' => $data['montoDetraccion'] ?? null,
                    'montoNeto' => $data['montoNeto'] ?? null,
                    'observaciones' => $data['observaciones'],
                    'id_user' => $data['user'] ?? Auth::user()->id,
                    'id_t10tdocMod' => $data['id_t10tdocMod'] ?? null,
                    'serieMod' => $data['serieMod'] ?? null,
                    'numeroMod' => $data['numeroMod'] ?? null,
                    'fecha_Registro' => now(),
                    'id_dest_tipcaja' => null,
                    'id_tip_form' => 2,
                ]);    
            }else{
                $nuevoDocumento = Documento::find($data['idDocumento']);
                $nuevoDocumento->update([
                    'id_tipmov' => $idTipMov,
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
                    'detraccion' => $data['montoDetraccion'] ?? null,
                    'montoNeto' => $data['montoNeto'] ?? null,
                    'observaciones' => $data['observaciones'],
                    'id_t10tdocMod' => $data['id_t10tdocMod'] ?? null,
                    'serieMod' => $data['serieMod'] ?? null,
                    'numeroMod' => $data['numeroMod'] ?? null,
                    'id_user' => $data['user'] ?? Auth::user()->id,
                    'fecha_Registro' => now(),
                    'id_dest_tipcaja' => null,
                    'id_tip_form' => 2,
                ]);

                $data['movimientosEditables'] = $this->borrarRegistrosed($data['idDocumento']);
            }
            // Registrar detalle del documento  
            $this->registrarDetalleDocumento($nuevoDocumento->id, $data);

            // Registrar movimiento en caja
            $this->registrarMovimientoCaja($nuevoDocumento->id, $data);
          

            DB::commit();

            return ['success' => 'Documento registrado con éxito'];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al registrar el documento', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return ['error' => 'Ocurrió un error al registrar el documento'];
        }
    }

    public function borrarRegistrosed($idmov)
    {
        DB::beginTransaction();
    
        try {
            $data = [];
    
            // Si familiaId es '002', obtener movlibro con bloqueo pesimista
            
            $datos = MovimientoDeCaja::select('mov')
                ->where('id_documentos', $idmov)
                ->whereIn('id_libro', ['1','2'])
                ->lockForUpdate() // Bloqueo pesimista
                ->get()
                ->toArray();

            if (!empty($datos)) {
                $data['movlibro'] = $datos[0]['mov'];
            }
        
    
            // Obtener movcaja con bloqueo pesimista
            $datos = MovimientoDeCaja::select('mov')
                ->distinct()
                ->where('id_documentos', $idmov)
                ->where('id_libro', '3')
                ->lockForUpdate() // Bloqueo pesimista
                ->get()
                ->toArray();
    
            if (!empty($datos)) {
                $data['movcaja'] = $datos[0]['mov'];
            }
    
            // Eliminar registros de MovimientoDeCaja y DDetalleDocumento
            MovimientoDeCaja::where('id_documentos', $idmov)->delete();
            DDetalleDocumento::where('id_referencia', $idmov)->delete();
    
            // Confirmar la transacción
            DB::commit();
    
            return $data;
    
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            Log::error('Error al borrar registros', ['exception' => $e]);
            session()->flash('error', 'Ocurrió un error al borrar los registros.');
            return [];
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

        $i = 1;
        foreach ($data['productos'] as $producto) {
            try {
                // Obtener el ID del centro de costos
                $CCid = CentroDeCostos::where('descripcion', $producto['CC'])->first();
                // Crear el detalle del documento
                DDetalleDocumento::create([
                    'id_referencia' => $documentoId,
                    'orden' => $i,
                    'id_producto' => $producto['codigoProducto'],
                    'id_tasas' => $producto['tasaImpositiva'],
                    'cantidad' => $producto['cantidad'],
                    'cu' => $producto['precioUnitario'],
                    'total' => $producto['total'],
                    'id_centroDeCostos' => $CCid->id ?? null,
                ]);
                
                $i++;
            } catch (\Exception $e) {
                Log::error('Error al registrar el detalle del documento', [
                    'documentoId' => $documentoId,
                    'orden' => $i,
                    'producto' => $producto,
                    'exception' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                throw $e; // Para que el método principal pueda capturar y manejar este error
            }
        }
        
       
    }


    public function registrarMovimientoCaja(int $documentoId, array $data)
    {

        try {
            if ($data['origen'] == 'ingreso' || $data['origen'] == 'editar ingreso' || $data['origen'] == 'cxc' || $data['origen'] == 'editar cxc'){
                $libro = '1';
                $dh = $data['tipoDocumento'] == '07' ? '2' : '1';
            } else{
                $libro = '2';
                $dh = $data['tipoDocumento'] == '07' ? '1' : '2';
            }
            
            $cuentaId = $data['cuenta'];
    
            // Conversión de precio
            $precioConvertido = $this->convertirPrecio($data['monedaId'], $data['precio'], $data['fechaEmi']);
    
            // Obtener último número de movimiento
            $ultimoMovimiento = MovimientoDeCaja::where('id_libro', $libro)
                ->lockForUpdate()
                ->orderByRaw('CAST(mov AS UNSIGNED) DESC')
                ->first();
            if ($data['origen'] == 'ingreso' || $data['origen'] == 'egreso' || $data['origen'] == 'cxc' || $data['origen'] == 'cxp'){
                $nuevoMov = $ultimoMovimiento ? intval($ultimoMovimiento->mov) + 1 : 1;
            } else{
                $nuevoMov = $data['movimientosEditables']['movlibro'];
            }
            // Crear nuevo libro de ingresos

            if(!empty($data['montoDetraccion'])){

                if($data['origen'] == 'cxc' || $data['origen'] == 'editar cxc'){
                    $cuentasDetraccion = 2;
                }else{
                    $cuentasDetraccion = 4;
                }

                MovimientoDeCaja::create([
                    'id_libro' => $libro,
                    'mov' => $nuevoMov,
                    'fec' => $data['fechaEmi'],
                    'id_documentos' => $documentoId,
                    'id_cuentas' => $cuentaId,
                    'id_dh' => $dh,
                    'monto' => $data['montoNeto'],
                    'montodo' => null,
                    'glosa' => $data['observaciones'],
                ]);
                MovimientoDeCaja::create([
                    'id_libro' => $libro,
                    'mov' => $nuevoMov,
                    'fec' => $data['fechaEmi'],
                    'id_documentos' => $documentoId,
                    'id_cuentas' => $cuentasDetraccion,
                    'id_dh' => $dh,
                    'monto' => $data['montoDetraccion'],
                    'montodo' => null,
                    'glosa' => $data['observaciones'],
                ]);
            }else{
                MovimientoDeCaja::create([
                    'id_libro' => $libro,
                    'mov' => $nuevoMov,
                    'fec' => $data['fechaEmi'],
                    'id_documentos' => $documentoId,
                    'id_cuentas' => $cuentaId,
                    'id_dh' => $dh,
                    'monto' => $precioConvertido,
                    'montodo' => null,
                    'glosa' => $data['observaciones'],
                ]);
            }
            
    
            // Registrar movimientos relacionados con la apertura
            if ($data['origen'] == 'ingreso' || $data['origen'] == 'editar ingreso' || $data['origen'] == 'egreso' || $data['origen'] == 'editar egreso'){
                $this->registrarAperturaRelacionada($documentoId, $nuevoMov, $precioConvertido, $data);
            }
    
        } catch (\Exception $e) {
            Log::error('Error al registrar libro de ingresos', [
                'documentoId' => $documentoId,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e; // Re-lanza la excepción para que el método principal pueda manejar el error
        }
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
            if ($data['origen'] == 'ingreso' || $data['origen'] == 'egreso'){
                $nuevoMovApertura = $this->obtenerNuevoMovApertura($apertura->id);
            } else{
                $nuevoMovApertura = $data['movimientosEditables']['movcaja'];
            }
            
            $cuenta = $this->obtenerCuentaDesdeTipoCaja($data['apertura']['id_tipo']);

            if ($data['origen'] == 'ingreso' || $data['origen'] == 'editar ingreso'){
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
                    'numero_de_operacion' => $data['cod_operacion'],
                ]);

                // Pago de documento
                MovimientoDeCaja::create([
                    'id_libro' => 3,
                    'id_apertura' => $apertura->id,
                    'mov' => $nuevoMovApertura,
                    'fec' => $apertura->fecha,
                    'id_documentos' => $documentoId,
                    'id_cuentas' => $data['cuenta'],
                    'id_dh' => 2,
                    'monto' => $precioConvertido,
                    'montodo' => null,
                    'glosa' => $data['observaciones'],
                    'numero_de_operacion' => $data['cod_operacion'],
                ]);    
            } else{
               
                // Pago de documento
                MovimientoDeCaja::create([
                    'id_libro' => 3,
                    'id_apertura' => $apertura->id,
                    'mov' => $nuevoMovApertura,
                    'fec' => $apertura->fecha,
                    'id_documentos' => $documentoId,
                    'id_cuentas' => $data['cuenta'],
                    'id_dh' => 1,
                    'monto' => $precioConvertido,
                    'montodo' => null,
                    'glosa' => $data['observaciones'],
                    'numero_de_operacion' => $data['cod_operacion'],
                ]); 
                // Registro de transacción en caja
                MovimientoDeCaja::create([
                    'id_libro' => 3,
                    'id_apertura' => $apertura->id,
                    'mov' => $nuevoMovApertura,
                    'fec' => $apertura->fecha,
                    'id_documentos' => $documentoId,
                    'id_cuentas' => $cuenta->id,
                    'id_dh' => 2,
                    'monto' => $precioConvertido,
                    'montodo' => null,
                    'glosa' => $data['observaciones'],
                    'numero_de_operacion' => $data['cod_operacion'],
                ]);

            }
            
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
