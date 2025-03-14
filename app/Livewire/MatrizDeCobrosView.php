<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\MatrizDeCobrosServices;
use Illuminate\Support\Facades\Log;  // Asegúrate de importar Log

class MatrizDeCobrosView extends Component
{
    public $movimientos = [];
    public $status = 'pendiente';
    public $filters = [];
    public $hasFiltered = false;

    protected $matrizDeCobrosService;

    public function updated($property)
    {
        Log::info("Filtro actualizado: {$property}");

        if (str_starts_with($property, 'filters.') || $property === 'status') {
            $this->hasFiltered = true;
            Log::info("Aplicando filtro para el campo: {$property}");
            $this->procesar();
        }
    }

    public function procesar()
    {
        try {
            Log::info("Iniciando procesamiento con el estado: {$this->status}");

            switch ($this->status) {
                case 'pendiente':
                    $movimientos = collect($this->matrizDeCobrosService->obtenerPagosPendientes());
                    break;
                case 'pagado':
                    $movimientos = collect($this->matrizDeCobrosService->obtenerPagosPagados());
                    break;
                default:
                    $movimientos = collect($this->matrizDeCobrosService->obtenerTodosLosPagos());
                    break;
            }

            Log::info("Total de movimientos antes de aplicar filtros: " . count($movimientos));

            foreach ($this->filters as $key => $value) {
                if (!empty($value)) {
                    // Eliminar espacios al principio y al final
                    $value = trim($value);
                    Log::info("Aplicando filtro para el campo: {$key} con valor: '{$value}'");

                    // Si el valor contiene espacios, lo tratamos como múltiples palabras
                    if (strpos($value, ' ') !== false) {
                        // Separar el filtro en palabras
                        $words = explode(' ', $value);
                        Log::info("Filtro con múltiples palabras: " . implode(', ', $words));

                        // Filtrar los movimientos para que contengan todas las palabras
                        foreach ($words as $word) {
                            $movimientos = $movimientos->filter(fn($item) => str_contains(strtolower($item->$key ?? ''), strtolower(trim($word))));
                            Log::info("Filtro aplicado a la palabra: {$word}");
                        }
                    } else {
                        // Si no contiene espacios, se usa el filtro como está
                        $movimientos = $movimientos->filter(fn($item) => str_contains(strtolower($item->$key ?? ''), strtolower($value)));
                        Log::info("Filtro aplicado a: '{$value}' en el campo: {$key}");
                    }
                }
            }

            Log::info("Total de movimientos después de aplicar filtros: " . count($movimientos));

            $this->movimientos = $movimientos->toArray();
        } catch (\Exception $e) {
            Log::error("Error al procesar los cobros: " . $e->getMessage());
            session()->flash('error', 'Ocurrió un error al procesar los cobros: ' . $e->getMessage());
        }
    }

    public function hydrate(MatrizDeCobrosServices $matrizDeCobrosService)
    {
        $this->matrizDeCobrosService = $matrizDeCobrosService;
    }

    public function render()
    {
        return view('livewire.matriz-de-cobros-view')->layout('layouts.app');
    }
}
