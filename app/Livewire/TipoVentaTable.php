<?php

namespace App\Livewire;

use App\Models\TipoVenta;
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

final class TipoVentaTable extends PowerGridComponent
{
    use WithExport;

    public $descripcion = '';
    public $estado = 1;
    public $tipo_venta_id;
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
        return TipoVenta::query()
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

            Column::make('Descripción', 'descripcion')
                ->searchable()
                ->sortable(),

            Column::make('Estado', 'estado_texto')
                ->sortable(),

            Column::action('Acciones')
        ];
    }

    public function actions(TipoVenta $row): array
    {
        return [
            Button::add('edit')
                ->slot('Editar')
                ->id()
                ->class('bg-indigo-500 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm')
                ->dispatch('openModal', ['component' => 'edit-tipo-venta-modal', 'arguments' => ['tipoVentaId' => $row->id]]),

            Button::add('delete')
                ->slot('Eliminar')
                ->id()
                ->class('bg-red-500 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm')
                ->dispatch('openModal', ['component' => 'delete-tipo-venta-modal', 'arguments' => ['tipoVentaId' => $row->id]])
        ];
    }

    public function header(): array
    {
        return [
            Button::add('new')
                ->slot('Nuevo Tipo de Venta')
                ->class('bg-indigo-500 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm')
                ->dispatch('openModal', ['component' => 'tipo-venta-modal', 'arguments' => []])
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
        $this->reset(['descripcion', 'estado', 'tipo_venta_id']);
    }

    public function editTipoVenta($data): void
    {
        $this->tipo_venta_id = $data['tipoVenta']['id'];
        $this->descripcion = $data['tipoVenta']['descripcion'];
        $this->estado = $data['tipoVenta']['estado'];
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'descripcion' => 'required|min:3',
            'estado' => 'required|boolean'
        ]);

        if ($this->tipo_venta_id) {
            $tipoVenta = TipoVenta::find($this->tipo_venta_id);
            $tipoVenta->update([
                'descripcion' => $this->descripcion,
                'estado' => $this->estado
            ]);
        } else {
            TipoVenta::create([
                'descripcion' => $this->descripcion,
                'estado' => $this->estado
            ]);
        }

        $this->closeModal();
        $this->dispatch('pg:eventRefresh-default');
    }

    public function deleteTipoVenta($data): void
    {
        $tipoVenta = TipoVenta::find($data['tipoVenta']['id']);
        $tipoVenta->delete();
        $this->dispatch('pg:eventRefresh-default');
    }

    #[On('tipoVentaCreated')]
    #[On('tipoVentaUpdated')]
    #[On('tipoVentaDeleted')]
    public function refreshTable()
    {
        $this->fillData();
    }
}
