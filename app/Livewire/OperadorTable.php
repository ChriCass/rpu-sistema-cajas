<?php

namespace App\Livewire;

use App\Models\Operador;
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
use Livewire\Attributes\On;

final class OperadorTable extends PowerGridComponent
{
    use WithExport;

    public $nombre = '';
    public $estado = 1;
    public $operador_id;
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
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Operador::query()
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

            Column::make('Nombre', 'nombre')
                ->searchable()
                ->sortable(),

            Column::make('Estado', 'estado_texto')
                ->sortable(),

            Column::action('Acciones')
        ];
    }

    public function actions(Operador $row): array
    {
        return [
            Button::add('edit')
                ->slot('Editar')
                ->id()
                ->class('bg-indigo-500 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm')
                ->dispatch('openModal', ['component' => 'edit-operador-modal', 'arguments' => ['operadorId' => $row->id]]),

            Button::add('delete')
                ->slot('Eliminar')
                ->id()
                ->class('bg-red-500 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm')
                ->dispatch('openModal', ['component' => 'delete-operador-modal', 'arguments' => ['operadorId' => $row->id]])
        ];
    }

    public function header(): array
    {
        return [
            Button::add('new')
                ->slot('Nuevo Operador')
                ->class('bg-indigo-500 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm')
                ->dispatch('openModal', ['component' => 'operador-modal', 'arguments' => []])
        ];
    }

    #[On('operadorCreated')]
    #[On('operadorUpdated')]
    #[On('operadorDeleted')]
    public function refreshTable()
    {
        $this->fillData();
    }

    // Modal Functions
    public function showModal(): void
    {
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['nombre', 'estado', 'operador_id']);
    }

    public function editOperador($data): void
    {
        $this->operador_id = $data['operador']['id'];
        $this->nombre = $data['operador']['nombre'];
        $this->estado = $data['operador']['estado'];
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'nombre' => 'required|min:3',
            'estado' => 'required|boolean'
        ]);

        if ($this->operador_id) {
            $operador = Operador::find($this->operador_id);
            $operador->update([
                'nombre' => $this->nombre,
                'estado' => $this->estado
            ]);
        } else {
            Operador::create([
                'nombre' => $this->nombre,
                'estado' => $this->estado
            ]);
        }

        $this->closeModal();
        $this->dispatch('pg:eventRefresh-default');
    }

    public function deleteOperador($data): void
    {
        $operador = Operador::find($data['operador']['id']);
        $operador->delete();
        $this->dispatch('pg:eventRefresh-default');
    }
}
