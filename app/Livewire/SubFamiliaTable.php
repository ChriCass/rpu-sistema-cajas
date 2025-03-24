<?php

namespace App\Livewire;
use App\Models\Familia;
use App\Models\SubFamilia;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
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
use Livewire\Attributes\On;

final class SubFamiliaTable extends PowerGridComponent
{
    use WithExport;

    public function setUp(): array
    {
        return [
            Footer::make()
                ->showPerPage()
                ->showRecordCount(mode: 'full'),
                Exportable::make(fileName: 'Tabla SubFamilia') 
                ->type(Exportable::TYPE_XLS), 
        ];
    }
     
    public function datasource(): Builder
    {
        return SubFamilia::query()
            ->join('familias', 'subfamilias.id_familias', '=', 'familias.id')
            ->select(
                'subfamilias.id',
                'subfamilias.id as il',  
                'subfamilias.id_familias',
                'subfamilias.desripcion',
                'familias.id as familias_id',
                'familias.descripcion as familia_descripcion'
            )
            ->orderBy('familias.id', 'ASC');
    }
    
    
    public function relationSearch(): array
    {
        return [];
    }
    

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('il')
            ->add('descripcion')
            ->add('id_familias')
            ->add('familia_descripcion');
    }
    
    public function columns(): array
    {
        return [
            Column::make('Id', 'il')
                ->searchable(),
            Column::make('Descripcion', 'desripcion')
                ->searchable(),
            Column::make('Familia Descripcion', 'familia_descripcion', 'id_familias') // Mostrar la descripción de la familia pero buscar por id_familias
                ->searchable(),
            Column::action('Acciones')->visibleInExport(visible: false)
        ];
    }
    public function filters(): array
    {
     

        return [
            Filter::inputText('desripcion')
                ->operators(['contains']),
            Filter::select('id_familias')
                ->dataSource(Familia::all())
                ->optionValue('id')
                ->optionLabel('descripcion'),
                Filter::inputText('il')
                ->operators(['contains'])
                ->builder(function (Builder $builder, $value) {
                    // Log the value to see what is received
                    

                    if (is_array($value) && isset($value['value'])) {
                        // Extract the actual search value
                        $searchValue = $value['value'];
                       
                        // Ensure the query filters by the correct table column
                        $builder->where('subfamilias.id', 'like', "%{$searchValue}%");
                    }
                })
        ];
    }

    #[On('subfamiliaUpdated')]
    #[On('subfamilia-created')]
    public function refreshTable(): void
    {
        $this->fillData();
    }
    
    

    public function actions(SubFamilia $row): array
    {
        return [
            Button::add('edit')
                ->slot('Editar')
                ->id()
                ->class('bg-teal-500 hover:bg-teal-700 text-white py-2 px-4 rounded')
                ->openModal('edit-sub-familia-modal', ['subfamiliaId' => $row->il,'familiasId' => $row->familias_id]),
                Button::add('edit')
                ->slot('Borrar')
                ->id()
                ->class('bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded')
                ->openModal('delete-sub-familia-modal', ['subfamiliaId' => $row->il])
        ];
    }
 
}
