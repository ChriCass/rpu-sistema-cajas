<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;


class AperturaEditParent extends Component
{
    public $aperturaId;
    public $mostrarIngreso = false;
    public $mostrarEDIngreso = false;
    public $mostrarSalida = false;
    public $mostrarEDSalida = false;
    public $mostrarCXP = false;
    public $mostrarCXC = false;
    public $mostrarAplicaciones = false;

    public $mostrarEDvaucherCompras = false;
    public $mostrarEDvaucherVentas = false;

    public $numMov;
    // Nuevas variables para los componentes secundarios
    public $mostrarRegistroCXP = false;
    public $mostrarIngresoComponente = false;
    public $mostrarGastoComponente = false;
    public $mostrarRegistroCXC = false;

    #[On('mostrarComponente')]
    public function mostrarComponente($componente, $numeroMovimiento = null)
    {
        Log::info("Evento recibido: mostrarComponente = {$componente}");
        

        // Mostrar el componente correspondiente
        switch ($componente) {
            case 'ingreso':
                $this->mostrarIngreso = true;
                $this->dispatch('scroll-down');
                break;
            case 'salida':
                $this->mostrarSalida = true;
                $this->dispatch('scroll-down');
                break;
         case 'EditarIngreso':
              $this->mostrarEDIngreso = true; // Para mostrar el formulario de edición de ingreso
               $this->numMov = $numeroMovimiento;
               $this->dispatch('scroll-down');
              break;
          case 'EditarSalida':
              $this->mostrarEDSalida = true; // Para mostrar el formulario de edición de salida
              $this->numMov = $numeroMovimiento;
              $this->dispatch('scroll-down');
              break;
              case 'EditVaucherDePagoCompras':
                $this->mostrarEDvaucherCompras = true; // Mostrar el componente de editar voucher de pago de compras
                $this->numMov = $numeroMovimiento;
                $this->dispatch('scroll-down');
                break;
            case 'EditVaucherDePagoVentas':
                $this->mostrarEDvaucherVentas = true; // Mostrar el componente de editar voucher de pago de ventas
                $this->numMov = $numeroMovimiento;
                $this->dispatch('scroll-down');
                break;


            case 'cxp':
                $this->mostrarCXP = true;
                $this->dispatch('scroll-down');
                break;
            case 'cxc':
                $this->mostrarCXC = true;
                $this->dispatch('scroll-down');
                break;
            case 'aplicaciones':
                $this->mostrarAplicaciones = true;
                $this->dispatch('scroll-down');
                break;

       /*         // Nuevos componentes (secundarios)
            case 'registroCXP':
                $this->mostrarRegistroCXP = true;
                break;
            case 'ingresoComponente':
                $this->mostrarIngresoComponente = true;
                break;
            case 'gastoComponente':
                $this->mostrarGastoComponente = true;
                break;
            case 'registroCXC':
                $this->mostrarRegistroCXC = true;
                break; */
        }
           // Después de mostrar el componente, emitimos un evento para hacer scroll hacia abajo
    
    }

    // Hook que se ejecuta cuando se actualiza la visibilidad de los componentes
 

    public function cancelarComponente()
    {
        // Reseteamos todos los componentes principales y secundarios
        $this->resetComponentes();
    }

    // Reiniciar solo los componentes secundarios
  /*  public function resetSecundarios()
    {
        $this->mostrarRegistroCXP = false;
        $this->mostrarIngresoComponente = false;
        $this->mostrarGastoComponente = false;
        $this->mostrarRegistroCXC = false;
    } */

    // Reiniciar todos los componentes (principales y secundarios)
    public function resetComponentes()
    {
        // Reseteamos todos los componentes principales
        $this->mostrarIngreso = false;
        $this->mostrarSalida = false;
     $this->mostrarEDSalida = false;
       $this->mostrarEDIngreso = false;
        $this->mostrarCXP = false;
        $this->mostrarCXC = false;
        $this->mostrarAplicaciones = false;
        $this->numMov = null;
        // Reseteamos también los componentes secundarios
      ///  $this->resetSecundarios();
    }

    public function render()
    {
        return view('livewire.apertura-edit-parent', [
            'aperturaId' => $this->aperturaId,
            'mostrarIngreso' => $this->mostrarIngreso,
            'mostrarSalida' => $this->mostrarSalida,
            'mostrarCXP' => $this->mostrarCXP,
            'mostrarCXC' => $this->mostrarCXC,
            'numMov' => $this->numMov,
            'mostrarAplicaciones' => $this->mostrarAplicaciones,
            'mostrarEDIngreso' => $this->mostrarEDIngreso,
            'mostrarEDSalida' => $this->mostrarEDSalida,
            // Pasamos las nuevas variables al renderizado
            'mostrarRegistroCXP' => $this->mostrarRegistroCXP,
            'mostrarRegistroCXC' => $this->mostrarRegistroCXC,
            'mostrarIngresoComponente' => $this->mostrarIngresoComponente,
            'mostrarGastoComponente' => $this->mostrarGastoComponente,
            'mostrarEDvaucherCompras' => $this->mostrarEDvaucherCompras,
            'mostrarEDvaucherVentas' => $this->mostrarEDvaucherVentas,
        ])->layout('layouts.app');
    }
}
