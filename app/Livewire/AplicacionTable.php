<?php

namespace App\Livewire;

use App\Models\MovimientoDeCaja;
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

final class AplicacionTable extends PowerGridComponent
{
    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return MovimientoDeCaja::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('id_libro')
            ->add('id_apertura')
            ->add('mov')
            ->add('fec_formatted', fn (MovimientoDeCaja $model) => Carbon::parse($model->fec)->format('d/m/Y'))
            ->add('id_documentos')
            ->add('id_cuentas')
            ->add('id_dh')
            ->add('monto')
            ->add('montodo')
            ->add('fecha_registro_formatted', fn (MovimientoDeCaja $model) => Carbon::parse($model->fecha_registro)->format('d/m/Y H:i:s'))
            ->add('glosa');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->sortable()
                ->searchable(),

            Column::make('Id libro', 'id_libro')
                ->sortable()
                ->searchable(),

            Column::make('Id apertura', 'id_apertura')
                ->sortable()
                ->searchable(),

            Column::make('Mov', 'mov')
                ->sortable()
                ->searchable(),

            Column::make('Fec', 'fec_formatted', 'fec')
                ->sortable(),

            Column::make('Id documentos', 'id_documentos')
                ->sortable()
                ->searchable(),

            Column::make('Id cuentas', 'id_cuentas')
                ->sortable()
                ->searchable(),

            Column::make('Id dh', 'id_dh')
                ->sortable()
                ->searchable(),

            Column::make('Monto', 'monto')
                ->sortable()
                ->searchable(),

            Column::make('Montodo', 'montodo')
                ->sortable()
                ->searchable(),

            Column::make('Fecha registro', 'fecha_registro_formatted', 'fecha_registro')
                ->sortable(),

            Column::make('Glosa', 'glosa')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('fec'),
            Filter::datetimepicker('fecha_registro'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(MovimientoDeCaja $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit: '.$row->id)
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
