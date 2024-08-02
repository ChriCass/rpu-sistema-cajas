<?php

namespace App\Livewire;

use App\Models\Familia;
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
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
final class FamiliaTable extends PowerGridComponent
{
    use WithExport;

    public function setUp(): array
    {
        return [
            Footer::make()
                ->showPerPage()
                ->showRecordCount(mode: 'full'), 
        ];
    }

    public function datasource(): Builder
    {
        $query = Familia::query()->where('id', 'not like', '0%')->orderBy('id', 'asc');

        $count = $query->count();
        Log::info("Número de registros después de aplicar el filtro: " . $count);

        return $query;
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
            ->add('id_tipofamilias');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->searchable(),

            Column::make('Descripcion', 'descripcion')
                ->searchable(),

            Column::make('Id tipofamilias', 'id_tipofamilias')
                ->searchable()->hidden(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('descripcion')
                ->operators(['contains'])
                ->placeholder('Buscar descripción'),

            Filter::select('id')
                ->dataSource(Familia::query()->where('id', 'not like', '0%')->orderBy('id', 'asc')->get(['id']))
                ->optionLabel('id')
                ->optionValue('id')
        ];
    }

    public function actions(Familia $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit')
                ->class('bg-teal-500 hover:bg-teal-700 text-white py-2 px-4 rounded')
                ->openModal('edit-familia-modal', ['familiaId' => $row->id])
        ];
    }
}
