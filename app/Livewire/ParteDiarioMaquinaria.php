<?php

namespace App\Livewire;

use Illuminate\Support\Carbon;
use Livewire\Component;
use App\Models\Entidad;

class ParteDiarioMaquinaria extends Component
{
    // Datos del formulario
    public $fecha;
    public $fechaInicio;
    public $fechaFin;
    public $diasTotales = 1;
    public $numero = '000315';
    public $operador = '';
    public $operadores = [];
    public $unidad = '';
    public $unidades = [];
    public $cliente = '';
    public $codigoEntidad = '';
    public $lugarTrabajo = '';
    
    // Propiedades para el buscador de clientes
    public $clientes = [];
    public $busquedaCliente = '';
    public $mostrarResultados = false;
    
    // Control de horas
    public $horaInicioManana = '';
    public $horaFinManana = '';
    public $horasManana = '';
    public $horaInicioTarde = '';
    public $horaFinTarde = '';
    public $horasTarde = '';
    public $totalHoras = '';
    
    // Horómetro mañana
    public $horometroInicioManana = '';
    public $horometroFinManana = '';
    public $diferenciaHorometroManana = '';
    
    // Horómetro tarde
    public $horometroInicioTarde = '';
    public $horometroFinTarde = '';
    public $diferenciaHorometroTarde = '';
    public $diferencia = ''; // Total diferencia
    public $interrupciones = '';
    
    // Consumo de combustible
    public $combustible = '';
    public $lubricantes = '';
    public $horometroInicioConsumo = '';
    public $horometroFinConsumo = '';
    public $diferenciaConsumo = '';
    public $horaPorTrabajo = '';
    public $precioPorHora = '';
    public $importeACobrar = '';
    
    // Descripción y observaciones
    public $descripcionTrabajo = '';
    public $observaciones = '';
    public $pagado = 0; // 0 = pendiente, 1 = pagado
    
    public $tiposVenta = [];
    
    public function mount()
    {
        // Inicializar valores predeterminados
        $this->fecha = Carbon::now()->format('Y-m-d');
        $this->fechaInicio = Carbon::now()->format('Y-m-d');
        $this->fechaFin = Carbon::now()->addDay()->format('Y-m-d');
        
        // Lista de tipos de venta
        $this->tiposVenta = [
            ['id' => 1, 'descripcion' => 'VENTA POR HORA'],
            ['id' => 2, 'descripcion' => 'VENTA POR DÍA'],
            ['id' => 3, 'descripcion' => 'VENTA POR MES'],
            ['id' => 4, 'descripcion' => 'VENTA POR OBRA'],
            ['id' => 5, 'descripcion' => 'VENTA POR SERVICIO'],
            ['id' => 6, 'descripcion' => 'VENTA POR PROYECTO'],
            ['id' => 7, 'descripcion' => 'VENTA POR CONTRATO'],
        ];
        
        // Lista de operadores (en el futuro se cargaría desde la base de datos)
        $this->operadores = [
            ['id' => 1, 'nombre' => 'Juan Pérez'],
            ['id' => 2, 'nombre' => 'Carlos Gutiérrez'],
            ['id' => 3, 'nombre' => 'Luis Ramírez'], 
            ['id' => 4, 'nombre' => 'Roberto Sánchez'],
            ['id' => 5, 'nombre' => 'Miguel Ángel Torres'],
            ['id' => 6, 'nombre' => 'José Morales'],
            ['id' => 7, 'nombre' => 'Andrés Rodriguez'],
        ];
        
        // Lista de unidades (en el futuro se cargaría desde la base de datos)
        $this->unidades = [
            ['id' => 1, 'numero' => 'M-001', 'descripcion' => 'Tractor John Deere 6120M'],
            ['id' => 2, 'numero' => 'M-002', 'descripcion' => 'Excavadora CAT 320'],
            ['id' => 3, 'numero' => 'M-003', 'descripcion' => 'Retroexcavadora JCB 3CX'],
            ['id' => 4, 'numero' => 'M-004', 'descripcion' => 'Cargador Frontal Komatsu WA100'],
            ['id' => 5, 'numero' => 'M-005', 'descripcion' => 'Bulldozer CAT D6'],
            ['id' => 6, 'numero' => 'M-006', 'descripcion' => 'Motoniveladora John Deere 670G'],
        ];
        
        $this->calcularDiasTotales();
    }

    public function updatedFechaInicio()
    {
        $this->calcularDiasTotales();
    }

    public function updatedFechaFin()
    {
        $this->calcularDiasTotales();
    }

    public function calcularDiasTotales()
    {
        if ($this->fechaInicio && $this->fechaFin) {
            try {
                $inicio = Carbon::createFromFormat('Y-m-d', $this->fechaInicio);
                $fin = Carbon::createFromFormat('Y-m-d', $this->fechaFin);
                
                // Si la fecha fin es menor a inicio, la ajustamos
                if ($fin->lt($inicio)) {
                    $this->fechaFin = $this->fechaInicio;
                    $fin = Carbon::createFromFormat('Y-m-d', $this->fechaFin);
                }
                
                $this->diasTotales = $fin->diffInDays($inicio) + 1; // Incluimos ambos días
            } catch (\Exception $e) {
                $this->diasTotales = 1;
            }
        } else {
            $this->diasTotales = 1;
        }
    }

    public function calcularHorasManana()
    {
        if ($this->horaInicioManana && $this->horaFinManana) {
            try {
                $inicio = Carbon::createFromFormat('H:i', $this->horaInicioManana);
                $fin = Carbon::createFromFormat('H:i', $this->horaFinManana);
                $this->horasManana = number_format($fin->diffInMinutes($inicio) / 60, 2);
                $this->calcularTotalHoras();
            } catch (\Exception $e) {
                // En caso de formato incorrecto
                $this->horasManana = '';
            }
        }
    }

    public function calcularHorasTarde()
    {
        if ($this->horaInicioTarde && $this->horaFinTarde) {
            try {
                $inicio = Carbon::createFromFormat('H:i', $this->horaInicioTarde);
                $fin = Carbon::createFromFormat('H:i', $this->horaFinTarde);
                $this->horasTarde = number_format($fin->diffInMinutes($inicio) / 60, 2);
                $this->calcularTotalHoras();
            } catch (\Exception $e) {
                // En caso de formato incorrecto
                $this->horasTarde = '';
            }
        }
    }

    public function calcularTotalHoras()
    {
        $this->totalHoras = number_format(floatval($this->horasManana) + floatval($this->horasTarde), 2);
    }

    public function calcularDiferenciaHorometroManana()
    {
        if ($this->horometroInicioManana && $this->horometroFinManana) {
            $this->diferenciaHorometroManana = number_format(floatval($this->horometroFinManana) - floatval($this->horometroInicioManana), 2);
            $this->calcularDiferenciaTotal();
        }
    }
    
    public function calcularDiferenciaHorometroTarde()
    {
        if ($this->horometroInicioTarde && $this->horometroFinTarde) {
            $this->diferenciaHorometroTarde = number_format(floatval($this->horometroFinTarde) - floatval($this->horometroInicioTarde), 2);
            $this->calcularDiferenciaTotal();
        }
    }
    
    public function calcularDiferenciaTotal()
    {
        $this->diferencia = number_format(floatval($this->diferenciaHorometroManana) + floatval($this->diferenciaHorometroTarde), 2);
    }
    
    public function calcularDiferenciaConsumo()
    {
        if ($this->horometroInicioConsumo && $this->horometroFinConsumo) {
            $this->diferenciaConsumo = number_format(floatval($this->horometroFinConsumo) - floatval($this->horometroInicioConsumo), 2);
        }
    }

    public function calcularImporte()
    {
        if ($this->horaPorTrabajo && $this->precioPorHora) {
            $this->importeACobrar = number_format(floatval($this->horaPorTrabajo) * floatval($this->precioPorHora), 2);
        }
    }

    public function guardar()
    {
        // Aquí implementaremos la lógica para guardar el formulario
        session()->flash('message', 'Parte diario guardado correctamente!');
        
        return redirect()->route('parte-diario');
    }

    public function buscarClientes()
    {
        if (strlen($this->busquedaCliente) >= 2) {
            $this->clientes = Entidad::where('descripcion', 'like', '%' . $this->busquedaCliente . '%')
                ->orWhere('id', 'like', '%' . $this->busquedaCliente . '%')
                ->select('id', 'descripcion')
                ->get()
                ->toArray();
            $this->mostrarResultados = true;
        } else {
            $this->clientes = [];
            $this->mostrarResultados = false;
        }
    }

    public function seleccionarCliente($id)
    {
        $clienteSeleccionado = Entidad::find($id);
        if ($clienteSeleccionado) {
            $this->cliente = $clienteSeleccionado->descripcion;
            $this->codigoEntidad = $clienteSeleccionado->id;
            $this->busquedaCliente = $clienteSeleccionado->descripcion;
            $this->mostrarResultados = false;
        }
    }

    public function limpiarCliente()
    {
        $this->cliente = '';
        $this->codigoEntidad = '';
        $this->busquedaCliente = '';
        $this->mostrarResultados = false;
        $this->clientes = [];
    }

    public function crearCliente()
    {
        $this->dispatch('openModalEntidad', true);
    }

    public function render()
    {
        return view('livewire.parte-diario-maquinaria');
    }
} 