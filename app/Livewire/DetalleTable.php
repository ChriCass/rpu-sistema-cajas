<?php

namespace App\Livewire;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Detalle;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use App\Models\Familia;
use App\Models\SubFamilia;

use Rappasoft\LaravelLivewireTables\Views\Filters\TextFilter;
use Illuminate\Support\Facades\DB; // Importación de DB
use Illuminate\Support\Facades\Log; // Importación de Log
class DetalleTable extends DataTableComponent


{   public $selectedSubfamilia = null;
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
             Column::make("Familia", "familia.descripcion")->sortable(),
        // Column::make("Subfamilia", "subfamilia.desripcion")->sortable(),
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
                    $builder->where('detalle.id_familias', $value);
                }),

            SelectFilter::make('Subfamilia')
                ->options(
                    SubFamilia::query()
                        ->orderBy('id')
                        ->pluck('desripcion', 'id')
                        ->toArray()
                )
                ->filter(function (Builder $builder, string $value) {
                    $this->selectedSubfamilia = Subfamilia::find($value)->desripcion ?? 'N/A';
                    $this->dispatch('subfamiliaSelected', $this->selectedSubfamilia);
                    $builder->where('detalle.id_subfamilia', $value);
                }),

            TextFilter::make('Id')
                ->config([
                    'placeholder' => 'Buscar Id',
                    'maxlength' => '255',
                ])
                ->filter(function(Builder $builder, string $value) {
                    $builder->where('detalle.id', 'like', '%' . $value . '%');
                }),

            TextFilter::make('Descripcion')
                ->config([
                    'placeholder' => 'Buscar Descripcion',
                    'maxlength' => '255',
                ])
                ->filter(function(Builder $builder, string $value) {
                    $builder->whereRaw('LOWER(detalle.descripcion) like ?', ['%' . strtolower($value) . '%']);
                }),
        ];
    }


    public function builder(): Builder
    {
        return Detalle::query()
            ->with(['familia', 'subfamilia'])
            ->leftJoin('Logistica.familias as familias', 'detalle.id_familias', '=', 'familias.id')
            ->leftJoin('Logistica.subfamilias as subfamilias', function($join) {
                $join->on(DB::raw("detalle.id_familias || detalle.id_subfamilia"), '=', DB::raw("subfamilias.id_familias || subfamilias.id"));
            })
            ->select(
                'detalle.id',
                'detalle.descripcion',
                'familias.descripcion as familia_descripcion',
                'subfamilias.desripcion as subfamilia_descripcion'
            )
            ->whereRaw('LEFT(detalle.id_familias, 1) <> ?', ['0']);
    }
    
    
}