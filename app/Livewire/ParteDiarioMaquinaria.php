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
    
    // Propiedades para valorización
    public $horaPorTrabajo = '';
    public $precioPorHora = '';
    public $importeACobrar = '';
    
    // Descripción y observaciones
    public $descripcionTrabajo = '';
    public $observaciones = '';
    public $pagado = 0; // 0 = pendiente, 1 = pagado
    public $montoPagado = '';
    public $montoPendiente = '0.00';
    
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

    public function updatedHoraInicioManana()
    {
        $this->calcularHorasManana();
    }

    public function updatedHoraFinManana()
    {
        $this->calcularHorasManana();
    }

    public function updatedHoraInicioTarde()
    {
        $this->calcularHorasTarde();
    }

    public function updatedHoraFinTarde()
    {
        $this->calcularHorasTarde();
    }

    public function calcularHorasManana()
    {
        if ($this->horaInicioManana && $this->horaFinManana) {
            try {
                $inicio = Carbon::createFromFormat('H:i', $this->horaInicioManana);
                $fin = Carbon::createFromFormat('H:i', $this->horaFinManana);
                
                // Si la hora de fin es menor que la hora de inicio, asumimos que es del día siguiente
                if ($fin < $inicio) {
                    $fin->addDay();
                }
                
                $diferencia = $fin->diffInMinutes($inicio);
                $this->horasManana = number_format($diferencia / 60, 2);
                $this->calcularTotalHoras();
            } catch (\Exception $e) {
                $this->horasManana = '';
            }
        } else {
            $this->horasManana = '';
            $this->calcularTotalHoras();
        }
    }

    public function calcularHorasTarde()
    {
        if ($this->horaInicioTarde && $this->horaFinTarde) {
            try {
                $inicio = Carbon::createFromFormat('H:i', $this->horaInicioTarde);
                $fin = Carbon::createFromFormat('H:i', $this->horaFinTarde);
                
                // Si la hora de fin es menor que la hora de inicio, asumimos que es del día siguiente
                if ($fin < $inicio) {
                    $fin->addDay();
                }
                
                $diferencia = $fin->diffInMinutes($inicio);
                $this->horasTarde = number_format($diferencia / 60, 2);
                $this->calcularTotalHoras();
            } catch (\Exception $e) {
                $this->horasTarde = '';
            }
        } else {
            $this->horasTarde = '';
            $this->calcularTotalHoras();
        }
    }

    public function calcularTotalHoras()
    {
        $manana = floatval($this->horasManana ?: 0);
        $tarde = floatval($this->horasTarde ?: 0);
        $this->totalHoras = number_format($manana + $tarde, 2);
    }

    public function updatedHorometroInicioManana()
    {
        $this->calcularDiferenciaHorometroManana();
    }

    public function updatedHorometroFinManana()
    {
        $this->calcularDiferenciaHorometroManana();
    }

    public function updatedHorometroInicioTarde()
    {
        $this->calcularDiferenciaHorometroTarde();
    }

    public function updatedHorometroFinTarde()
    {
        $this->calcularDiferenciaHorometroTarde();
    }

    public function calcularDiferenciaHorometroManana()
    {
        if ($this->horometroInicioManana !== '' && $this->horometroFinManana !== '') {
            try {
                $inicio = floatval($this->horometroInicioManana);
                $fin = floatval($this->horometroFinManana);
                
                if ($fin >= $inicio) {
                    $this->diferenciaHorometroManana = number_format($fin - $inicio, 2);
                } else {
                    $this->diferenciaHorometroManana = '0.00';
                }
                
                $this->calcularDiferenciaTotal();
            } catch (\Exception $e) {
                $this->diferenciaHorometroManana = '0.00';
            }
        } else {
            $this->diferenciaHorometroManana = '0.00';
            $this->calcularDiferenciaTotal();
        }
    }
    
    public function calcularDiferenciaHorometroTarde()
    {
        if ($this->horometroInicioTarde !== '' && $this->horometroFinTarde !== '') {
            try {
                $inicio = floatval($this->horometroInicioTarde);
                $fin = floatval($this->horometroFinTarde);
                
                if ($fin >= $inicio) {
                    $this->diferenciaHorometroTarde = number_format($fin - $inicio, 2);
                } else {
                    $this->diferenciaHorometroTarde = '0.00';
                }
                
                $this->calcularDiferenciaTotal();
            } catch (\Exception $e) {
                $this->diferenciaHorometroTarde = '0.00';
            }
        } else {
            $this->diferenciaHorometroTarde = '0.00';
            $this->calcularDiferenciaTotal();
        }
    }
    
    public function calcularDiferenciaTotal()
    {
        $manana = floatval($this->diferenciaHorometroManana ?: 0);
        $tarde = floatval($this->diferenciaHorometroTarde ?: 0);
        $this->diferencia = number_format($manana + $tarde, 2);
    }
    
    public function updatedHoraPorTrabajo()
    {
        $this->calcularImporte();
    }

    public function updatedPrecioPorHora()
    {
        $this->calcularImporte();
    }

    public function calcularImporte()
    {
        if ($this->horaPorTrabajo !== '' && $this->precioPorHora !== '') {
            try {
                $horas = floatval($this->horaPorTrabajo);
                $precio = floatval($this->precioPorHora);
                $this->importeACobrar = number_format($horas * $precio, 2);
            } catch (\Exception $e) {
                $this->importeACobrar = '0.00';
            }
        } else {
            $this->importeACobrar = '0.00';
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

    public function updatedPagado()
    {
        if ($this->pagado != '2') {
            $this->montoPagado = '';
            $this->montoPendiente = '0.00';
        } else {
            $this->calcularMontoPendiente();
        }
    }

    public function updatedMontoPagado()
    {
        $this->calcularMontoPendiente();
    }

    public function updatedImporteACobrar()
    {
        if ($this->pagado == '2') {
            $this->calcularMontoPendiente();
        }
    }

    public function calcularMontoPendiente()
    {
        if ($this->importeACobrar && $this->montoPagado !== '') {
            try {
                $importe = floatval(str_replace(',', '', $this->importeACobrar));
                $pagado = floatval($this->montoPagado);
                
                if ($pagado <= $importe) {
                    $this->montoPendiente = number_format($importe - $pagado, 2);
                } else {
                    $this->montoPagado = $importe;
                    $this->montoPendiente = '0.00';
                }
            } catch (\Exception $e) {
                $this->montoPendiente = '0.00';
            }
        } else {
            $this->montoPendiente = $this->importeACobrar ?: '0.00';
        }
    }

    public function render()
    {
        return view('livewire.parte-diario-maquinaria');
    }
} 