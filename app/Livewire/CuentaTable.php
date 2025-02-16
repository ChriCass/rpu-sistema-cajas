<?php

namespace App\Livewire;

use App\Models\Cuenta;
use App\Models\TipoDeCuenta;
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

final class CuentaTable extends PowerGridComponent
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
        return Cuenta::query()
            ->join('tipodecuenta as t', 'cuentas.id_tcuenta', '=', 't.id')
            ->select('cuentas.id', 'cuentas.descripcion', 't.descripcion as tipo_cuenta_descripcion');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('descripcion')
            ->add('tipo_cuenta_descripcion');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->searchable(),

            Column::make('Descripcion', 'descripcion')
                ->searchable(),


            Column::make('Tipo de cuenta', 'tipo_cuenta_descripcion')
            ->searchable(),
            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('tipo_cuenta_descripcion', 't.descripcion')
            ->dataSource(TipoDeCuenta::all()) 
            ->optionValue('descripcion') 
            ->optionLabel('descripcion'), 
        ];
    }

    #[On('cuentaUpdated')]
    #[On('cuenta-created')]
    public function refreshTable(): void
    {
        $this->fillData();
    }
    
    
    public function actions(Cuenta $row): array
    {
        return [
            Button::add('edit')
            ->slot('Editar')
            ->class('bg-teal-500 hover:bg-teal-700 text-white py-2 px-4 rounded')
            ->openModal('edit-cuenta-modal', ['cuentaId' => $row->id]),
            Button::add('edit')
            ->slot('Borrar')
            ->id()
            ->class('bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded')
            ->openModal('delete-cuenta-modal', ['cuentaId' => $row->id])
        ];
    }

   
   
}
