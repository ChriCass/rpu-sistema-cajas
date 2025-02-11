<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InconsistenciasService {

    public function InconsistenciasReporte(){
        $query = "select documentos.id,id_entidades,entidades.descripcion as rz,tabla10_tipodecomprobantedepagoodocumento.descripcion as tdoc,serie,numero from documentos 
                left join movimientosdecaja on documentos.id = movimientosdecaja.id_documentos 
                left join entidades on documentos.id_entidades = entidades.id
                left join tabla10_tipodecomprobantedepagoodocumento on tabla10_tipodecomprobantedepagoodocumento.id = documentos.id_t10tdoc
                where id_documentos is null";
        
        return DB::select($query);
            
    }

}