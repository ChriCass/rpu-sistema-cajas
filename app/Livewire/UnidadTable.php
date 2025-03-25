<?php

namespace App\Livewire;

use App\Models\Unidad;
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
use Livewire\Component;

final class UnidadTable extends PowerGridComponent
{
    use WithExport;

    public $numero = '';
    public $descripcion = '';
    public $estado = 1;
    public $unidad_id;
    public bool $showModal = false;

    public function setUp(): array
    {
        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount()
        ];
    }

    public function datasource(): Builder
    {
        return Unidad::query()
            ->select(['*'])
            ->selectRaw("CASE WHEN estado = 1 THEN 'Activo' ELSE 'Inactivo' END as estado_texto");
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->searchable()
                ->sortable(),

            Column::make('Número', 'numero')
                ->searchable()
                ->sortable(),

            Column::make('Descripción', 'descripcion')
                ->searchable()
                ->sortable(),

            Column::make('Estado', 'estado_texto')
                ->sortable(),

            Column::action('Acciones')
        ];
    }

    public function actions(Unidad $row): array
    {
        return [
            Button::add('edit')
                ->slot('Editar')
                ->id()
                ->class('bg-indigo-500 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm')
                ->dispatch('openModal', ['component' => 'edit-unidad-modal', 'arguments' => ['unidadId' => $row->id]]),

            Button::add('delete')
                ->slot('Eliminar')
                ->id()
                ->class('bg-red-500 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm')
                ->dispatch('openModal', ['component' => 'delete-unidad-modal', 'arguments' => ['unidadId' => $row->id]])
        ];
    }

    public function header(): array
    {
        return [
            Button::add('new')
                ->slot('Nueva Unidad')
                ->class('bg-indigo-500 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm')
                ->dispatch('openModal', ['component' => 'unidad-modal', 'arguments' => []])
        ];
    }

    // Modal Functions
    public function showModal(): void
    {
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['numero', 'descripcion', 'estado', 'unidad_id']);
    }

    public function editUnidad($data): void
    {
        $this->unidad_id = $data['unidad']['id'];
        $this->numero = $data['unidad']['numero'];
        $this->descripcion = $data['unidad']['descripcion'];
        $this->estado = $data['unidad']['estado'];
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'numero' => 'required|min:1',
            'descripcion' => 'required|min:3',
            'estado' => 'required|boolean'
        ]);

        if ($this->unidad_id) {
            $unidad = Unidad::find($this->unidad_id);
            $unidad->update([
                'numero' => $this->numero,
                'descripcion' => $this->descripcion,
                'estado' => $this->estado
            ]);
        } else {
            Unidad::create([
                'numero' => $this->numero,
                'descripcion' => $this->descripcion,
                'estado' => $this->estado
            ]);
        }

        $this->closeModal();
        $this->dispatch('pg:eventRefresh-default');
    }

    public function deleteUnidad($data): void
    {
        $unidad = Unidad::find($data['unidad']['id']);
        $unidad->delete();
        $this->dispatch('pg:eventRefresh-default');
    }
}
