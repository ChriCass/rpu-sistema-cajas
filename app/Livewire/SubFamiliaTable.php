<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\SubFamilia;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use App\Models\Familia;
use Rappasoft\LaravelLivewireTables\Views\Filters\TextFilter;
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

    public function filters(): array
    {
        return [
            SelectFilter::make('Familia')
                ->options(
                    Familia::query()
                        ->orderBy('id')
                        ->pluck('descripcion', 'id')
                        ->toArray()
                )
                ->filter(function (Builder $builder, string $value) {
                    $builder->where('subfamilias.id_familias', $value);
                }),
            TextFilter::make('Id')
                ->config([
                    'placeholder' => 'Buscar Id',
                    'maxlength' => '255',
                ])
                ->filter(function (Builder $builder, string $value) {
                    $builder->where('subfamilias.id', 'like', '%' . $value . '%');
                }),
            TextFilter::make('Subfamilia')
                ->config([
                    'placeholder' => 'Buscar Descripcion',
                    'maxlength' => '255',
                ])
                ->filter(function (Builder $builder, string $value) {
                    $builder->whereRaw('LOWER(subfamilias.desripcion) like ?', ['%' . strtolower($value) . '%']);
                }),
        ];
    }

    public function builder(): Builder
    {
        // Crear la consulta con el join y el filtro
        return SubFamilia::query()
            ->join('familias', 'subfamilias.id_familias', '=', 'familias.id')
            ->where('subfamilias.id_familias', 'NOT LIKE', '0%')
            ->select(
                'subfamilias.id', 
                'subfamilias.desripcion', 
                'familias.descripcion as familia_descripcion'
            )
            ->orderBy('familias.id', 'ASC');
    }
}
