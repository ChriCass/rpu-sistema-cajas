<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\TipoDeCambioSunat;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class DolarService {

    public function ObtenerDolar(){
        //$hoy = Carbon::now('America/Lima')->format('Y-m-d');
        $hoy = "2025-02-23";
        Log::info("Iniciando consulta de tipo de cambio para la fecha: {$hoy}");
    
        $tphoy = TipoDeCambioSunat::where('fecha', $hoy)->get()->toArray();
        
        if(count($tphoy) == 0){
            Log::warning("No se encontró tipo de cambio para la fecha: {$hoy}. Se procederá con el tratamiento de datos.");
            $this->TratamientoDeDatos($hoy);
        } else {
            Log::info("Se encontró tipo de cambio para la fecha: {$hoy}. No se requiere tratamiento de datos.");
        }
    
        Log::info('Proceso Terminado');   
    }
    
    public function TratamientoDeDatos($hoy){
        $fechaIniP = TipoDeCambioSunat::latest('Fecha')->first();
        $fechaIni = date('Y-m-d', strtotime($fechaIniP['fecha'] . ' +1 day'));
        $fechaFin = $hoy;
        $data = $this -> ApiRest($fechaIni,$fechaFin);
        if ($data == '404'){
            Log::info('No existen datos correspondientes a la fecha indicada');
            return;
        }
        $diferenciaDias = Carbon::parse($fechaIni)->diffInDays($fechaFin);
        if ($diferenciaDias <> 0){
            for ($i = 0; $i <= $diferenciaDias; $i++) { 
                $nuevaFecha = Carbon::parse($fechaIni)->addDays($i)->toDateString();
                $resultado = array_filter($data['data'], function ($item) use ($nuevaFecha) {
                    return $item['fecha'] === $nuevaFecha;
                });
                
                $primerElemento = reset($resultado);
                
                
                $this -> Insert($nuevaFecha,$primerElemento);
                
            }
        }elseif($fechaIni == $fechaFin){
            $primerElemento = reset($data['data']);
            Log::info($primerElemento);
            $this -> Insert($fechaIni,$primerElemento);
        }else{
            Log::info('la diferencia fue cero');    
        }
        
    }

    public function Insert($nuevaFecha,$resultado){
        if(empty($resultado)){
            $data = TipoDeCambioSunat::latest('Fecha')->first();
            $resultado['fecha'] = $nuevaFecha;
            $resultado['precio_compra'] = $data['compra'];
            $resultado['precio_venta'] = $data['venta'];
        }
         
        TipoDeCambioSunat::create([
            'fecha' => $resultado['fecha'],
            'compra' => $resultado['precio_compra'],
            'venta' => $resultado['precio_venta']
        ]);

    }

    public function ApiRest($Ini,$Fin){
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://api.migo.pe/api/v1/exchange', [
            'token' => 'oxzdu4ZBlghIaetvqYux8CocEVJABQAkptMBcpUyQVhXr5sF3vb0ABZxJF40',
            'fecha_inicio' => $Ini,
            'fecha_fin' => $Fin
        ]);
        
        // Procesar la respuesta
        if ($response->successful()) {
            $data = $response->json(); // Convierte la respuesta JSON a un array PHP
        } else {
            // Manejo de errores
            Log::error('Error al conectarse a la API: ' . $response->status());
            $data = $response->status();
        }
        return $data;
    }
    
}
