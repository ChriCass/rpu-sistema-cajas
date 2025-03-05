<?php

namespace App\Livewire;

use App\Models\CentroDeCostos;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class CentroDeCostosTable extends PowerGridComponent
{
    public function setUp(): array
    {


        return [

            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return CentroDeCostos::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('descripcion')
            ->add('abrev');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->searchable(),


            Column::make('descripcion', 'descripcion')
                ->searchable(),


            Column::make('abrev', 'abrev')
            ->searchable(),


            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name'),
            Filter::datepicker('created_at_formatted', 'created_at'),
        ];
    }

    #[On('cajaUpdated')]
    #[On('caja-created')]
    public function refreshTable(): void
    {
        $this->fillData();
    }
    

    public function actions(CentroDeCostos $row): array
    {
        return [
            Button::add('edit')
            ->slot('Editar')
            ->class('bg-teal-500 hover:bg-teal-700 text-white py-2 px-4 rounded')
            ->openModal('edit-caja-modal', ['cajaId' => $row->id]),
            Button::add('edit')
            ->slot('Borrar')
            ->id()
            ->class('bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded')
            ->openModal('delete-caja-modal', ['cajaId' => $row->id])
        ];
    }
 
}
