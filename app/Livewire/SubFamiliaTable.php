<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\SubFamilia;
use Illuminate\Database\Eloquent\Builder;

class SubFamiliaTable extends DataTableComponent
{
    protected $model = SubFamilia::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setSearchDisabled(); // Desactivar búsqueda global
        $this->setColumnSelectDisabled(); // Desactivar selección de columnas
    }

    public function columns(): array
    {
        return [
          
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Desripcion", "desripcion")
                ->sortable(),
            Column::make("Familia Descripcion", "familia.descripcion")
                ->sortable(),
        ];
    }

    public function builder(): Builder
    {
        // Crear la consulta con el join y el filtro
        return SubFamilia::query()
            ->join('Logistica.familias AS familias', 'Logistica.subfamilias.id_familias', '=', 'familias.id')
            ->where('Logistica.subfamilias.id_familias', 'NOT LIKE', '0%')
            ->select(
                'Logistica.subfamilias.id', 
                'Logistica.subfamilias.desripcion', 
                'familias.descripcion as familia_descripcion'
            )
            ->orderByRaw('CAST(familias.id AS INTEGER) ASC');
    }
}
