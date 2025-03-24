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
use Illuminate\Support\Facades\DB;
use App\Models\Familia;
use App\Models\SubFamilia;
use Livewire\Attributes\On;
final class DetalleTable extends PowerGridComponent
{
    use WithExport;

    public function setUp(): array
    {
         

        return [
            
            Footer::make()
                ->showPerPage()
                ->showRecordCount(mode: 'full'), 
                Exportable::make(fileName: 'Tabla Detalle') 
            ->type(Exportable::TYPE_XLS), 
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
            );
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
            Column::action('Acciones')->visibleInExport(visible: false)
        ];
    }
    #[On('detalleUpdated')]
    #[On('producto-created')]
    public function refreshTable(): void
    {
        $this->fillData();
    }

    public function filters(): array
    {   
        // Fetch distinct subfamilias related to the current familias in the datasource query
        $familiaIds = Detalle::select('id_familias')->distinct()->pluck('id_familias');
        
        $subfamilias = Subfamilia::whereIn('id_familias', $familiaIds)
            ->join('familias', 'subfamilias.id_familias', '=', 'familias.id')
            ->select('subfamilias.id', 'subfamilias.desripcion', 'subfamilias.id_familias')
            ->distinct()
            ->get()
            ->map(function($subfamilia) {
                return [
                    'id' => $subfamilia->id_familias . $subfamilia->id, // Concatenamos familia y subfamilia
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
                ->optionLabel('descripcion')
                ->builder(function (Builder $builder, $value) {
                    if (!empty($value['value'])) {
                        $builder->whereRaw("CONCAT(detalle.id_familias, detalle.id_subfamilia) = ?", [$value['value']]);
                    }
                }),

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

    public function actions(Detalle $row): array
    {
        return [
            Button::add('edit')
                ->slot('Editar')
                ->id()
                ->class('bg-teal-500 hover:bg-teal-700 text-white py-2 px-4 rounded')
                ->openModal('edit-detalle-modal', ['detalleId' => $row->id]),
                Button::add('edit')
                ->slot('Borrar')
                ->id()
                ->class('bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded')
                ->openModal('delete-detalle-modal', ['detalleId' => $row->id])
        ];
    }

 
 
}
