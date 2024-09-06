<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class AperturaEditParent extends Component
{
    public $aperturaId;
    public $mostrarIngreso = false;
    public $mostrarSalida = false;
    public $mostrarCXP = false;
    public $mostrarCXC = false;
    public $mostrarAplicaciones = false;

    // Nuevas variables para los componentes secundarios
    public $mostrarRegistroCXP = false;
    public $mostrarIngresoComponente = false;
    public $mostrarGastoComponente = false;
    public $mostrarRegistroCXC = false;

    #[On('mostrarComponente')]
    public function mostrarComponente($componente)
    {
        // Reseteamos los componentes secundarios antes de mostrar uno nuevo
        $this->resetSecundarios();

        // Mostrar el componente correspondiente
        switch ($componente) {
            case 'ingreso':
                $this->mostrarIngreso = true;
                break;
            case 'salida':
                $this->mostrarSalida = true;
                break;
            case 'cxp':
                $this->mostrarCXP = true;
                break;
            case 'cxc':
                $this->mostrarCXC = true;
                break;
            case 'aplicaciones':
                $this->mostrarAplicaciones = true;
                break;

            // Nuevos componentes (secundarios)
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
                break;
        }
    }

    public function cancelarComponente()
    {
        // Reseteamos todos los componentes principales y secundarios
        $this->resetComponentes();
    }

    // Reiniciar solo los componentes secundarios
    public function resetSecundarios()
    {
        $this->mostrarRegistroCXP = false;
        $this->mostrarIngresoComponente = false;
        $this->mostrarGastoComponente = false;
        $this->mostrarRegistroCXC = false;
    }

    // Reiniciar todos los componentes (principales y secundarios)
    public function resetComponentes()
    {
        // Reseteamos todos los componentes principales
        $this->mostrarIngreso = false;
        $this->mostrarSalida = false;
        $this->mostrarCXP = false;
        $this->mostrarCXC = false;
        $this->mostrarAplicaciones = false;

        // Reseteamos también los componentes secundarios
        $this->resetSecundarios();
    }

    public function render()
    {
        return view('livewire.apertura-edit-parent', [
            'aperturaId' => $this->aperturaId,
            'mostrarIngreso' => $this->mostrarIngreso,
            'mostrarSalida' => $this->mostrarSalida,
            'mostrarCXP' => $this->mostrarCXP,
            'mostrarCXC' => $this->mostrarCXC,
            'mostrarAplicaciones' => $this->mostrarAplicaciones,

            // Pasamos las nuevas variables al renderizado
            'mostrarRegistroCXP' => $this->mostrarRegistroCXP,
            'mostrarRegistroCXC' => $this->mostrarRegistroCXC,
            'mostrarIngresoComponente' => $this->mostrarIngresoComponente,
            'mostrarGastoComponente' => $this->mostrarGastoComponente,
        ])->layout('layouts.app');
    }
}
