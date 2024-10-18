<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Entidad;

class ApiService
{
    public function apiRuc($ruc) { // Abelardo = Conexion a API para los RUCs
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://api.migo.pe/api/v1/ruc', [
            'token' => 'oxzdu4ZBlghIaetvqYux8CocEVJABQAkptMBcpUyQVhXr5sF3vb0ABZxJF40',
            'ruc' => $ruc,
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

    public function apiDNI($dni) { // // Abelardo = Conexion a API para los DNIs
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://api.migo.pe/api/v1/dni', [
            'token' => 'oxzdu4ZBlghIaetvqYux8CocEVJABQAkptMBcpUyQVhXr5sF3vb0ABZxJF40',
            'dni' => $dni,
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

    public function validarDNI($docIdenId,$dni){ // Abelardo = Validacion de DNI se conecta con APIdni para extraer la info
        if (strlen($dni) != 8 || !ctype_digit($dni)) {
            $data['success'] = '2';
            $data['desc'] = 'DNI no válido';
            return $data;
        }
        $datos = $this -> validacionConDB ($dni);
        if ($datos <> 0){
            $data['success'] = '1';
            $data['desc'] = $datos;
            return $data;
        }else{
            $datos = $this -> apiDNI($dni);
            if ($datos <> '404') {
                $datosAPasar['id'] = $datos['dni'];
                $datosAPasar['descripcion'] = $datos['nombre'];
                $datosAPasar['Estado_contribuyente'] = '-';
                $datosAPasar['Estado_domicilio'] = '-';
                $datosAPasar['provincia'] = '-';
                $datosAPasar['distrito'] = '-';
                $datosAPasar['direccion'] = '-';
                $datosAPasar['idt02doc'] = $docIdenId;
                $datosDNI = $this -> Insertar($datosAPasar);
                $data['success'] = '1';
                $data['desc'] = $datosDNI;
                return $data;
            } else {
                $data['success'] = '2';
                $data['desc'] = 'Error al conectarse a la API';
                return $data;
            }
        }
        
    }

    function validarRUC($docIdenId,$ruc) { // Abelardo = Algoritmo que validad Rucs
        // Verificar si el RUC tiene 11 dígitos
        if (strlen($ruc) != 11 || !ctype_digit($ruc)) {
            $data['success'] = '2';
            $data['desc'] = 'Ruc no Valido';

            return $data;
        }
        // Obtener los primeros 10 dígitos del RUC
        $ruc_base = substr($ruc, 0, 10);
        
        // Definir los pesos
        $pesos = [5, 4, 3, 2, 7, 6, 5, 4, 3, 2];
        
        // Calcular la suma ponderada
        $suma = 0;
        for ($i = 0; $i < 10; $i++) {
            $suma += $ruc_base[$i] * $pesos[$i];
        }
        
        // Obtener el residuo de la división entre 11
        $residuo = $suma % 11;
        Log::info('Paso Aqui:'.$residuo);

        // Calcular el dígito verificador
        $digito_verificador = (11 - $residuo) % 10;
        
        // Comparar el dígito verificador calculado con el dígito final del RUC
        if($digito_verificador == $ruc[10]){
            $datos = $this -> validacionConDB ($ruc);
            if ($datos <> 0){
                $data['success'] = '1';
                $data['desc'] = $datos;
                return $data;
            }else{
                $datos = $this -> apiRuc($ruc);
                if ($datos <> '404') {
                    $datosAPasar['id'] = $datos['ruc'];
                    $datosAPasar['descripcion'] = $datos['nombre_o_razon_social'];
                    $datosAPasar['Estado_contribuyente'] = $datos['estado_del_contribuyente'];
                    $datosAPasar['Estado_domicilio'] =  $datos['condicion_de_domicilio'];
                    $datosAPasar['provincia'] = $datos['provincia'];
                    $datosAPasar['distrito'] =  $datos['distrito'];
                    $datosAPasar['direccion'] = $datos['direccion'];
                    $datosAPasar['idt02doc'] = $docIdenId;
                    $datosRuc = $this -> Insertar($datosAPasar);
                    $data['success'] = '1';
                    $data['desc'] = $datosRuc;
                    return $data;
                } else {   
                    $data['success'] = '2';
                    $data['desc'] = 'Error al conectarse a la API';
                    return $data ;
                }
            }
        }else{
            $data['success'] = '2';
            $data['desc'] = 'Ruc no valido';
            return $data ;
        }
    }

    public function REntidad($docIdenId,$numEnt){ // Abelardo = Funcion que recoje los DNIs y RUC y los envia a sus diferentes
        if($docIdenId == '1'){
            return $this -> validarDNI($docIdenId,$numEnt);           
        }elseif($docIdenId == '6'){
            return $this -> validarRUC($docIdenId,$numEnt);
        }
    }

    public function validacionConDB ($NDoc){ // Abelardo = Funcion que valida el id con la DB
        $entidad = Entidad::where('id',$NDoc)
                    -> get()
                    -> toarray();
        if (count($entidad) <> 0) {
            return $entidad[0]['descripcion'];
        } else{
            return 0;
        }
    }

    public function Insertar($datosAPasar){  // Abelardo = Funcion que inserta data con la DB
        Entidad::insert(
            ['id' => $datosAPasar['id'],'descripcion' => $datosAPasar['descripcion'],
            'estado_contribuyente' => $datosAPasar['Estado_contribuyente'],'estado_domiclio' => $datosAPasar['Estado_domicilio'],
            'provincia' => $datosAPasar['provincia'],'distrito' => $datosAPasar['distrito'],
            'direccion' => $datosAPasar['direccion'],'idt02doc' => $datosAPasar['idt02doc']]
        );

        $data = $this -> validacionConDB($datosAPasar['id']);
        return $data;
    }
}