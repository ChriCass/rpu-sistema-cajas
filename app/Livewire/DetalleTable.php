<?php

namespace App\Livewire;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Detalle;
use Illuminate\Support\Facades\DB; // Importación de DB
use Illuminate\Support\Facades\Log; // Importación de Log
class DetalleTable extends DataTableComponent
{
    protected $model = Detalle::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setSearchDisabled(); // Desactivar búsqueda global
        $this->setColumnSelectDisabled(); // Desactivar selección de columnas
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")->sortable(),
            Column::make("Descripcion", "descripcion")->sortable(),
           //   Column::make("Familia", "familia.descripcion")->sortable(),
          //  Column::make("Subfamilia", "subfamilia.desripcion")->sortable(),
        ];
    }

    public function builder(): Builder
    {
        Log::info('Iniciando consulta en builder');

        $query = Detalle::query()
            ->leftJoin('Logistica.familias as familias', 'detalle.id_familias', '=', 'familias.id')
            ->leftJoin('Logistica.subfamilias as subfamilias', function($join) {
                $join->on(DB::raw("detalle.id_familias || detalle.id_subfamilia"), '=', DB::raw("subfamilias.id_familias || subfamilias.id"));
            })
            ->select(
                'detalle.id',
                'detalle.descripcion',
                'familias.descripcion as familia_descripcion',
                'subfamilias.desripcion as subfamilia_descripcion' // Corrección aquí
            )
            ->whereRaw('LEFT(detalle.id_familias, 1) <> ?', ['0']);

        // Ejecutar la consulta para obtener los resultados
        $results = $query->get();

        // Registrar los resultados en formato JSON
        Log::info('Resultados de la consulta: ' . $results->toJson());

        return $query;
    }
    
}
