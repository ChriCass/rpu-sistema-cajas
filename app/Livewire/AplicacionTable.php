<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\MovimientoDeCajaCustom;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use App\Models\Mes;
class AplicacionTable extends DataTableComponent
{
    protected $model = MovimientoDeCajaCustom::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id_representativo');
        $this->setSearchDisabled(); // Desactivar búsqueda global
        $this->setColumnSelectDisabled(); // Desactivar selección de columnas
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Mes')
                ->options(
                    Mes::pluck('descripcion', 'id')->toArray()
                )
                ->filter(function (Builder $builder, string $value) {
                    if ($value !== '') {
                        $builder->whereRaw('MONTH(STR_TO_DATE(fec, "%d/%m/%Y")) = ?', [$value]);
                    }
                }),
                SelectFilter::make('Año')
                ->options([
                    '' => 'Todos',
                    '2024' => '2024',
                    '2025' => '2025',
                    '2026' => '2026',
                ])
                ->filter(function (Builder $builder, string $value) {
                    if ($value !== '') {
                        $builder->whereRaw('YEAR(STR_TO_DATE(fec, "%d/%m/%Y")) = ?', [$value]);
                    }
                }),
        ];
    }
    public function query(): Builder
    {
        return MovimientoDeCajaCustom::query();
    }

    public function columns(): array
    {
        return [
            Column::make("Id Representativo", "id_representativo")
                ->sortable(),
            Column::make("Aplicaciones", "apl")
                ->sortable(),
            Column::make("Fecha", "fec")
                ->sortable(),
            Column::make("Movimiento", "mov")
                ->sortable(),
            Column::make("Promedio", "promedio")
                ->sortable(),
       
        ];
    }
}
