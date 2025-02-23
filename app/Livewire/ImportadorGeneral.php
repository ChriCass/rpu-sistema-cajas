<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class ImportadorGeneral extends Component
{
    use WithFileUploads; // Aquí se añade el trait necesario
    private $options;
    public $optionsEl;
    public $excelFile;
    protected $ApiService;
    protected $RegistroDocAvanzService;


    ///// RECUERDA SEGUIR LOS PRINCIPIOS DE RESPONSABILIDAD UNICA. ESTO SOLO FUNCIONA COMO FRONT, LO DEMAS UTILIZAMOS MEDIANTE SERVICES
    public function getOptions(){
        return $this->options;
    }

    public function setOptions($value)
    {
        $this->options = $value;
    }

    public function procesar()
    {
      ////// SUBIDA DE EXCEL MEDIANTE UN SERVICE
    }

    public function mount()
    {
        $this->setOptions([
            ['id' => 'cxc', 'name' => 'Cuentas por Cobrar (CXC)'],
            ['id' => 'cxp', 'name' => 'Cuentas por Pagar (CXP)'],
        ]);
    }

    public function downloadExcel()
    {
        /// DESCARGAR PLANTILLA MEDIANTE EXPORT

        // php artisan make:export NombreDelExportadorParaPlantillaExport 
        
    }

    public function render()
    {
        return view('livewire.importador-general', [
            'options' => $this->getOptions(),  
        ]); 
    }
}
