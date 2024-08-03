<?php

namespace App\Livewire;
use App\Models\Familia;
use App\Models\SubFamilia;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
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

final class SubFamiliaTable extends PowerGridComponent
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
        return SubFamilia::query()
            ->join('familias', 'subfamilias.id_familias', '=', 'familias.id')
            ->where('subfamilias.id_familias', 'NOT LIKE', '0%')
            ->select(
                'subfamilias.id', 
                'subfamilias.id_familias',
                'subfamilias.desripcion', 
                'familias.descripcion as familia_descripcion'
            )
            ->orderBy('familias.id', 'ASC');
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
            ->add('id_familias')
            ->add('familia_descripcion');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->searchable(),
            Column::make('Descripcion', 'desripcion')
                ->searchable(),
            Column::make('Familia Descripcion', 'familia_descripcion', 'id_familias') // Mostrar la descripción de la familia pero buscar por id_familias
                ->searchable(),
            Column::action('Action')
        ];
    }

    public function filters(): array
    {
     

        return [
            Filter::inputText('desripcion')
                ->operators(['contains']),
            Filter::select('id_familias')
                ->dataSource(Familia::all())
                ->optionValue('id')
                ->optionLabel('descripcion'),
                Filter::inputText('id')
                ->operators(['contains'])
                ->builder(function (Builder $builder, $value) {
                    // Log the value to see what is received
                    

                    if (is_array($value) && isset($value['value'])) {
                        // Extract the actual search value
                        $searchValue = $value['value'];
                       
                        // Ensure the query filters by the correct table column
                        $builder->where('subfamilias.id', 'like', "%{$searchValue}%");
                    }
                })
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(SubFamilia $row): array
    {
        return [
            Button::add('edit')
                ->slot('Editar')
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('edit', ['rowId' => $row->id])
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
