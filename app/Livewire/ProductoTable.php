<?php

namespace App\Livewire;

use App\Models\Producto;
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

final class ProductoTable extends PowerGridComponent
{
    use WithExport;

    public function setUp(): array
    {
        return [
            Header::make()
                ->showSearchInput(),

            Exportable::make(fileName: 'Tabla Producto') 
            ->type(Exportable::TYPE_XLS), 
        
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Producto::query()
            ->leftJoin('detalle', 'l_productos.id_detalle', '=', 'detalle.id')
            ->select(
                'l_productos.id',
                'l_productos.id_detalle',
                'l_productos.descripcion as producto_descripcion',
                'detalle.descripcion as detalle_descripcion'
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
            ->add('id_detalle')
            ->add('producto_descripcion')
            ->add('detalle_descripcion');
    }

    public function columns(): array
    {
        return [
            Column::make('ID Detalle', 'id_detalle')
                ->sortable()
                ->searchable(),

            Column::make('Descripción Detalle', 'detalle_descripcion')
                ->sortable()
                ->searchable(),

            Column::make('ID', 'id')
                ->sortable()
                ->searchable(),

            Column::make('Descripción Producto', 'producto_descripcion')
                ->sortable()
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('id_detalle')
                ->operators(['contains'])
                ->placeholder('Buscar por ID Detalle...')
                ->builder(function (Builder $builder, $value) {
                    if (!empty($value['value'])) {
                        $builder->where('l_productos.id_detalle', 'like', "%{$value['value']}%");
                    }
                }),

            Filter::inputText('detalle_descripcion')
                ->operators(['contains'])
                ->placeholder('Buscar por Descripción Detalle...')
                ->builder(function (Builder $builder, $value) {
                    if (!empty($value['value'])) {
                        $builder->where('detalle.descripcion', 'like', "%{$value['value']}%");
                    }
                }),

            Filter::inputText('id')
                ->operators(['contains'])
                ->placeholder('Buscar por ID...')
                ->builder(function (Builder $builder, $value) {
                    if (!empty($value['value'])) {
                        $builder->where('l_productos.id', 'like', "%{$value['value']}%");
                    }
                }),

            Filter::inputText('producto_descripcion')
                ->operators(['contains'])
                ->placeholder('Buscar por Descripción Producto...')
                ->builder(function (Builder $builder, $value) {
                    if (!empty($value['value'])) {
                        $builder->where('l_productos.descripcion', 'like', "%{$value['value']}%");
                    }
                }),
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
