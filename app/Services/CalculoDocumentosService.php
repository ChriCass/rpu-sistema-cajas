<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\TasaIgv;


class CalculoDocumentosService
{
    public function CalculoBI($calculoBI,$calculoIGV,$calculoOtroTri,$calculoNoGravado,$calculoPrecio,$idTipGrav){ //Abelardo = Funcion que calcula el IGV en base a la BI
        $entidad = TasaIgv::select('numero')
                    -> where('id',$idTipGrav)
                    -> get()
                    -> toarray();
        if($calculoBI <> ''){
            if($calculoOtroTri == ''){
                $calculoOtroTri = 0;
            }
            
            if($calculoNoGravado == ''){
                $calculoNoGravado = 0;
            }
            
    
            $data['BI'] = $calculoBI;
            $data['IGV'] = round($calculoBI * $entidad[0]['numero'],2);
            $data['OtroTributo'] = $calculoOtroTri;
            $data['NoGravado'] = $calculoNoGravado;
            $data['Precio'] = round($calculoBI + (round($calculoBI * $entidad[0]['numero'],2)) + $calculoOtroTri + $calculoNoGravado,2);
                
            return $data;
        } else {
            $data = 'N';
            return $data;
        }    
    }

    public function CalculoGN($calculoBI,$calculoIGV,$calculoOtroTri,$calculoNoGravado,$calculoPrecio,$idTipGrav,$puntopartida){ //Abelardo = Funcion que hace la suma

        Log::info($calculoBI);
        if($calculoIGV <> '' && $puntopartida == 1){
            if($calculoOtroTri == ''){
                $calculoOtroTri = 0;
            }
            
            if($calculoNoGravado == ''){
                $calculoNoGravado = 0;
            }
            
            if($calculoBI == ''){
                $calculoBI = 0;
            }
            
        } else {
            $data = 'N';
            return $data;
        }


        $data['BI'] = $calculoBI;
        $data['IGV'] = $calculoIGV;
        $data['OtroTributo'] = $calculoOtroTri;
        $data['NoGravado'] = $calculoNoGravado;
        $data['Precio'] = round($calculoBI + $calculoIGV + $calculoOtroTri + $calculoNoGravado,2);
            
        return $data;
    }
}