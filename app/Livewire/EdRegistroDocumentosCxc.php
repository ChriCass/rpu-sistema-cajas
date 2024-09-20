<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use App\Models\Documento;
use Illuminate\Support\Facades\Log;

class EdRegistroDocumentosCxc extends Component
{
    public $visible = false;
    public $idcxc;
    public $documentoCxc;

    #[On('showEdCxc')]
    public function showEdcxc($idcxc)
    {
        $this->idcxc = $idcxc;
        $this->consultaBD();

        // Verificar si el tipoDocumento es alguno de los no permitidos
        if ($this->documentoCxc && 
            ($this->documentoCxc->tipoDocumento === "Vaucher de Transferencia" ||
            $this->documentoCxc->tipoDocumento === "Comprobante de Anticipo" ||
            $this->documentoCxc->tipoDocumento === "Vaucher de Rendicion")) {
            $this->dispatch('mostrarAlerta');
            $this->visible = false; // No hacer visible el componente
        } else {
            $this->visible = true; // Hacer visible si el documento es válido
        }
    }

    public function consultaBD()
    {
        if ($this->idcxc) {
            $this->documentoCxc = Documento::select(
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
                ->where('documentos.id_tipmov', 1)
                ->where('documentos.id', $this->idcxc)
                ->first();

            Log::info('Resultado de la consulta de documentoCxc:', ['documentoCxc' => $this->documentoCxc]);
        }
    }
    public function render()
    {
        return view('livewire.ed-registro-documentos-cxc');
    }
}
