<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use App\Models\Documento;
use Illuminate\Support\Facades\Log;
class EdRegistroDocumentosCxp extends Component
{   public $visible = false;
    public $idcxp;
    public $documentoCxp;

    #[On('showEdCxc')]
    public function showEdcxc($idcxp)
    {
        $this->idcxp = $idcxp;
        $this->consultaBD();

        // Verificar si el tipoDocumento es alguno de los no permitidos
        if ($this->documentoCxp && 
            ($this->documentoCxp->tipoDocumento === "Vaucher de Transferencia" ||
            $this->documentoCxp->tipoDocumento === "Comprobante de Anticipo" ||
            $this->documentoCxp->tipoDocumento === "Vaucher de Rendicion")) {
            $this->dispatch('mostrarAlerta');
            $this->visible = false; // No hacer visible el componente
        } else {
            $this->visible = true; // Hacer visible si el documento es válido
        }
    }

    public function consultaBD()
    {
        if ($this->idcxp) {
            $this->documentoCxp = Documento::select(
                'documentos.id',
                DB::raw("DATE_FORMAT(fechaEmi, '%d/%m/%Y') AS fechaEmi"),
                'tabla10_tipodecomprobantedepagoodocumento.descripcion AS tipoDocumento',
                'documentos.id_entidades as id_entidades',
                'entidades.descripcion AS entidadDescripcion',
                'documentos.serie',
                'documentos.numero',
                'documentos.id_t04tipmon',
                'tasas_igv.tasa',
                'documentos.precio',
                'users.name AS usuario'
            )
                ->leftJoin('entidades', 'documentos.id_entidades', '=', 'entidades.id')
                ->leftJoin('users', 'documentos.id_user', '=', 'users.id')
                ->leftJoin('tabla10_tipodecomprobantedepagoodocumento', 'documentos.id_t10tdoc', '=', 'tabla10_tipodecomprobantedepagoodocumento.id')
                ->leftJoin('tasas_igv', 'documentos.id_tasasIgv', '=', 'tasas_igv.id')
                ->where('documentos.id_tipmov', 2)
                ->where('documentos.id', $this->idcxp)
                ->first();

            Log::info('Resultado de la consulta de documentoCxc:', ['documentoCxc' => $this->documentoCxp]);
        }
    }
    public function render()
    {
        return view('livewire.ed-registro-documentos-cxp');
    }
}
