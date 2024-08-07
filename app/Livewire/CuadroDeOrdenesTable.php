<?php

namespace App\Livewire;

use App\Models\Detalle;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use App\Models\Familia;
use App\Models\SubFamilia;
final class CuadroDeOrdenesTable extends PowerGridComponent
{
    use WithExport;

    public function setUp(): array
    {
        return [
            
            Footer::make()
                ->showPerPage()
                ->showRecordCount(mode: 'full'), 
        ];
    }

    public function datasource(): Builder
    {
        return Detalle::query()->with(['familia', 'subfamilia'])
            ->leftJoin('familias as familias', 'detalle.id_familias', '=', 'familias.id')
            ->leftJoin('subfamilias as subfamilias', function($join) {
                $join->on('detalle.id_familias', '=', 'subfamilias.id_familias')
                     ->on('detalle.id_subfamilia', '=', 'subfamilias.id');
            })
            ->select(
                'detalle.id',
                'detalle.descripcion',
                'familias.descripcion as familia_descripcion',
                'subfamilias.desripcion as subfamilia_descripcion'
            )
            ->whereRaw('LEFT(detalle.id_familias, 1) <> ?', ['0']);
    }
    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('descripcion')
            ->add('familia_descripcion')
            ->add('subfamilia_descripcion');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->searchable(),
            Column::make('DESCRIPCION', 'descripcion')->searchable(),
            Column::make('FAMILIA', 'familia_descripcion')->searchable(),
            Column::make('SUBFAMILIA', 'subfamilia_descripcion')->searchable(),
            
        ];
    }

    public function filters(): array
    {   
        // Fetch distinct subfamilias related to the current familias in the datasource query
        $familiaIds = Detalle::select('id_familias')->distinct()->pluck('id_familias');
        
        $subfamilias = Subfamilia::whereIn('id_familias', $familiaIds)
            ->select('id', 'desripcion')
            ->distinct()
            ->get()
            ->map(function($subfamilia) {
                return [
                    'id' => $subfamilia->id,
                    'descripcion' => $subfamilia->desripcion
                ];
            });

        return [
            Filter::select('familia_descripcion', 'detalle.id_familias')
                ->dataSource(Familia::all()->map(function($familia) {
                    return [
                        'id' => $familia->id,
                        'descripcion' => $familia->descripcion
                    ];
                }))
                ->optionValue('id')
                ->optionLabel('descripcion'),

            Filter::select('subfamilia_descripcion', 'detalle.id_subfamilia')
                ->dataSource($subfamilias)
                ->optionValue('id')
                ->optionLabel('descripcion'),
                Filter::inputText('descripcion')
                ->operators(['contains'])
                ->placeholder('Buscar descripción')
                ->builder(function (Builder $builder, $value) {
                    if (is_array($value) && isset($value['value'])) {
                        $searchValue = $value['value'];
                        $builder->where('detalle.descripcion', 'like', "%{$searchValue}%");
                    }
                }),
            
                Filter::inputText('id')
                ->operators(['contains'])
                ->builder(function (Builder $builder, $value) {
                    if (is_array($value) && isset($value['value'])) {
                        $searchValue = $value['value'];
                        $builder->where('detalle.id', 'like', "%{$searchValue}%");
                    }
                })
        ];
    }

 

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
