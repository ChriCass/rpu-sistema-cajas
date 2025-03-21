<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Apertura;
use DateTime;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use WireUi\Actions;

class EditCuadroDePendientesVentasModal extends Component
{
    public $openModal = false;
    public $aperturaId;
    public $fechaApertura;
    public $moneda = "PEN"; // Moneda por defecto, puede ser dinámica
    public $pendientes = [];
    public $contenedor;
    public $numMov;
     
    public function mount($aperturaId,$numMov, $contenedor)
    {
        $this->aperturaId = $aperturaId;

        // Recuperar la fecha directamente usando el aperturaId
        $apertura = Apertura::findOrFail($aperturaId);
        $this->fechaApertura = (new DateTime($apertura->fecha))->format('Y-m-d');
        $this->numMov = $numMov;

        $this->contenedor = $contenedor; // Pasar el contenedor de EditVaucherDePagoVentas
       
        $Movimiento = '3'.$aperturaId.$numMov;
         // Ejecutar la consulta dinámica con la nueva estructura SQL
         $this->pendientes = DB::select("
         SELECT 
             id_documentos , 
             tdoc, 
             id_entidades, 
             RZ, 
             Num, 
             Mon, 
             Descripcion, 
             IF(Mon = 'PEN', monto, montodo) AS monto
         FROM (
             SELECT 
                 id_documentos,
                 fechaEmi,
                 tabla10_tipodecomprobantedepagoodocumento.descripcion AS tdoc,
                 id_entidades,
                 entidades.descripcion AS RZ,
                 Num,
                 IF(CON3.Descripcion = 'DETRACCIONES POR COBRAR', 'PEN', id_t04tipmon) AS Mon,
                 CON3.Descripcion,
                 monto,
                 montodo
             FROM (
                 SELECT 
                     id_documentos,
                     documentos.fechaEmi,
                     documentos.id_t10tdoc,
                     documentos.id_entidades,
                     CONCAT(documentos.serie, '-', documentos.numero) AS Num,
                     documentos.id_t04tipmon,
                     cuentas.Descripcion,
                     monto,
                     montodo
                 FROM (
                     SELECT 
                         id_documentos,
                         id_cuentas,
                         ROUND(SUM(monto),2) AS monto,
                         ROUND(SUM(montodo),2) AS montodo
                     FROM (
                         SELECT 
						CONCAT(m.id_libro, m.id_apertura, m.mov) AS identificador,
						m.id_documentos,
						m.id_cuentas,
						IF(m.id_dh = '1', m.monto, m.monto * -1) AS monto,
						IF(m.id_dh = '1', IF(m.montodo IS NULL, 0, m.montodo), IF(m.montodo IS NULL, 0, m.montodo) * -1) AS montodo
					FROM movimientosdecaja m
					LEFT JOIN (
						SELECT DISTINCT 
							c.id AS id_cuenta, 
							t.id AS idTcuenta 
						FROM cuentas c
						LEFT JOIN tipodecuenta t ON c.id_tCuenta = t.id
					) AS INN1 ON m.id_cuentas = INN1.id_cuenta
					WHERE 
						(INN1.idTcuenta IS NULL OR INN1.idTcuenta <> '1') 
						AND COALESCE(CONCAT(m.id_libro, m.id_apertura, m.mov), '') <> :numMov
                     ) CON1
                     GROUP BY id_documentos, id_cuentas
                     HAVING ROUND(SUM(monto),10) <> 0
                 ) CON2
                 LEFT JOIN documentos ON CON2.id_documentos = documentos.id
                 LEFT JOIN cuentas ON CON2.id_cuentas = cuentas.id
             ) CON3
             LEFT JOIN entidades ON CON3.id_entidades = entidades.id
             LEFT JOIN tabla10_tipodecomprobantedepagoodocumento ON CON3.id_t10tdoc = tabla10_tipodecomprobantedepagoodocumento.id
             WHERE monto > 0
         ) CON4
         WHERE fechaEmi <= :fechaApertura
         AND Mon = :moneda
         AND IF(Mon = 'PEN', monto, montodo) <> 0
         AND tdoc <> 'Vaucher de Transferencia'
         ORDER BY CAST(id_documentos AS UNSIGNED) ASC
     ", [
         'fechaApertura' => $this->fechaApertura,
         'moneda' => $this->moneda,
         'numMov' => $Movimiento
     ]);

     foreach ($this->pendientes as &$pendiente) {
        if (collect($this->contenedor)->contains(function ($item) use ($pendiente) {
            return $item['id_documentos'] === $pendiente->id_documentos &&
                   $item['Num'] === $pendiente->Num &&
                   $item['Descripcion'] === $pendiente->Descripcion;
        })) {
            $pendiente->selected = true;
        } else {
            $pendiente->selected = false;
        }
    }


        Log::info('Consulta de pendientes ejecutada', [
            'aperturaId' => $this->aperturaId,
            'fechaApertura' => $this->fechaApertura,
            'moneda' => $this->moneda,
            'pendientes' => $this->pendientes,
        ]);
    }

    
    public function toggleSelection($idDocumento)
    {
        // Inicializa el contenedor si es null
        Log::info($idDocumento);
        $this->contenedor = $this->contenedor ?? [];
    
        // Buscar el documento en la lista de pendientes
        $pendiente = collect($this->pendientes)->firstWhere('id_documentos', $idDocumento);
    
        if ($pendiente) {
            // Verificamos si el documento ya está en el contenedor
            if (collect($this->contenedor)->contains(function ($item) use ($pendiente) {
                return $item['id_documentos'] === $pendiente->id_documentos; // Acceder con '->'
            })) {
                // Si está, lo eliminamos
                $this->contenedor = array_filter($this->contenedor, function ($item) use ($pendiente) {
                    return $item['id_documentos'] !== $pendiente->id_documentos; // Acceder con '->'
                });
                Log::info('Documento eliminado del contenedor', ['documento' => $pendiente]);
            } else {
                // Si no está, lo añadimos
                $this->contenedor[] = (array) $pendiente; // Convertir a array si es necesario
                Log::info('Documento añadido al contenedor', ['documento' => $pendiente]);
            }
        }
    
        Log::info('Estado actual del contenedor', ['contenedor' => $this->contenedor]);
    }

        /* 
        public function resetSelection()
        {
            $this->contenedor = [];   // Limpia el contenedor
            $this->openModal = false; // Cierra el modal
            Log::info('El contenedor ha sido reiniciado y el modal ha sido cerrado');
        }
    */
    public function sendingData()
    {
        $this->dispatch('sendingContenedor', $this->contenedor);
        
        if (!empty($this->contenedor)) {
            // Almacenar mensaje de éxito en la sesión
            session()->flash('message', 'Datos enviados correctamente.');
            Log::info("El array se envió", ["contenedor" => $this->contenedor]);
        } else {
            // Almacenar mensaje de advertencia en la sesión
            session()->flash('warning', 'No se envió nada a la tabla.');
            Log::info("No se envió nada a la tabla", ["contenedor" => $this->contenedor]);
        }
    }

    public function render()
    {
        return view('livewire.edit-cuadro-de-pendientes-ventas-modal');
    }
}
