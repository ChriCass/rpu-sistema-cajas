<?php

namespace App\Livewire;

use App\Models\TipoDeCaja;
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

final class CajaTable extends PowerGridComponent
{
    public function setUp(): array
    {

        return [
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return TipoDeCaja::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('descripcion')
            ->add('t04_tipodemoneda')
            ;
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->searchable(),


            Column::make('Descripcion', 'descripcion')
                ->searchable(),

            
            Column::make('Tipo de moneda', 't04_tipodemoneda')
                ->searchable(),

            Column::action('Accion')
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
    
    public function actions(TipoDeCaja $row): array
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
