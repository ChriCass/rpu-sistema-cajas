<?php

namespace App\Livewire;

use App\Models\Entidad;
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
use App\Models\TipoDocumentoIdentidad;


final class EntidadTable extends PowerGridComponent
{
    use WithExport;

    public function setUp(): array
    {


        return [
            Footer::make()
                ->showPerPage()
                ->showRecordCount(mode: 'full'),
            Exportable::make(fileName: 'Tabla Entidades')
                ->type(Exportable::TYPE_XLS),
        ];
    }

    public function datasource(): Builder
    {
        return Entidad::select(
            'entidades.id',
            'entidades.descripcion',
            'tabla02_tipodedocumentodeidentidad.abreviado'
        )
            ->join(
                'tabla02_tipodedocumentodeidentidad',
                'tabla02_tipodedocumentodeidentidad.id',
                '=',
                'entidades.idt02doc'
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

            ->add('abreviado')
        ;
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')

                ->searchable(),



            Column::make('Descripcion', 'descripcion')

                ->searchable(),



            Column::make('Tipo de documento', 'abreviado')

                ->searchable(),


        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('id')
                ->operators(['contains'])
                ->placeholder('Buscar...')
                ->builder(function (Builder $builder, $value) {
                    if (!empty($value['value'])) {
                        $builder->where('id', 'like', "%{$value['value']}%");
                    }
                }),
            Filter::inputText('descripcion')
                ->operators(['contains'])
                ->placeholder('Buscar...')
                ->builder(function (Builder $builder, $value) {
                    if (!empty($value['value'])) {
                        $builder->where('descripcion', 'like', "%{$value['value']}%");
                    }
                }),

            // Filtro para Tipo de Documento con select
            Filter::select('abreviado', 'abreviado')
                ->dataSource(TipoDocumentoIdentidad::all()) // Asegúrate de tener el modelo TipoDocumento correctamente definido
                ->optionValue('id')                // El valor que se guardará en la base de datos
                ->optionLabel('abreviado')       // La etiqueta que se mostrará en el select

                ->builder(function (Builder $builder, $value) {
                    if (!empty($value)) {
                        $builder->where('idt02doc', $value);
                    }
                }),
        ];
    }


    /*  public function actions(Entidad $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit: '.$row->id)
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('edit', ['rowId' => $row->id])
        ];
    } */

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
