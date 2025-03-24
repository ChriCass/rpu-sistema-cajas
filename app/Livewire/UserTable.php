<?php

namespace App\Livewire;

use App\Models\User;
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
use Livewire\Attributes\On;

final class UserTable extends PowerGridComponent
{
    use WithExport;

    #[On('userCreated')]
    #[On('userUpdated')]
    #[On('userDeleted')]
    public function refreshTable()
    {
        $this->fillData();
    }

    public function setUp(): array
    {
        return [
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
            Exportable::make('users_export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
        ];
    }

    public function datasource(): Builder
    {
        return User::query()
            ->select([
                'users.id',
                'users.name',
                'users.email',
                DB::raw('GROUP_CONCAT(roles.name SEPARATOR ", ") as roles_names')
            ])
            ->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->leftJoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_type', User::class)
            ->groupBy('users.id', 'users.name', 'users.email');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('email')
            ->add('roles_names', function ($row) {
                return $row->roles_names ?? 'Sin roles';
            });
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable()
                ->searchable(),

            Column::make('Nombre', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),

            Column::make('Roles', 'roles_names')
                ->searchable(),

            Column::action('Acciones'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name')
                ->operators(['contains']),
            Filter::inputText('email')
                ->operators(['contains']),
        ];
    }

    public function actions(\App\Models\User $row): array
    {
        return [
            Button::add('edit')
                ->slot('Editar')
                ->class('bg-teal-500 hover:bg-teal-700 text-white py-2 px-4 rounded')
                ->openModal('edit-user-modal', ['userId' => $row->id]),
                
            Button::add('delete')
                ->slot('Borrar')
                ->class('bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded ml-2')
                ->openModal('delete-user-modal', ['userId' => $row->id]),
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
