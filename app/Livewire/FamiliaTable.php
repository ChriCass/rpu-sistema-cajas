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
        // Consulta con filtro aplicado
        $query = Familia::query()->where('id', 'not like', '0%')->orderBy('id', 'asc');

        // Log para verificar cuántos registros se obtuvieron después de aplicar el filtro
        $count = $query->count();
        Log::info("Número de registros después de aplicar el filtro: " . $count);

        return $query;
    }

    public function relationSearch(): array
    {
        return [];
    }

    #[On('familia-created')]
    public function refreshTable(): void
    {
        $this->fillData();
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
              
                ->searchable(),

            
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
    

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
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
