<?php

namespace App\Livewire;
use Livewire\Attributes\On;
use App\Models\Apertura;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
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
use App\Models\Mes;
 use App\Models\TipoDeCaja;
final class AperturaTable extends PowerGridComponent
{
    use WithExport;

    public function setUp(): array
    {
      

        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
         
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Apertura::query()
            ->join('tipodecaja', 'tipodecaja.id', '=', 'aperturas.id_tipo')
            ->join('meses', 'meses.id', '=', 'aperturas.id_mes')
            ->select([
                'aperturas.id',
                'tipodecaja.descripcion as tipo_de_caja_descripcion',
                'aperturas.numero',
                'aperturas.año',
                'meses.descripcion as mes_descripcion',
                'aperturas.id_mes',
                'aperturas.id_tipo',
                DB::raw("DATE_FORMAT(aperturas.fecha, '%d/%m/%Y') as fecha_formatted")
            ])
            ->orderBy('aperturas.id', 'ASC');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('tipo_de_caja_descripcion')
            ->add('numero')
            ->add('año')
            ->add('mes_descripcion')
            ->add('id_mes', function($apertura) {
                return Mes::find($apertura->id_mes)->descripcion;
            })
            ->add('id_tipo', function($apertura) {
                return TipoDeCaja::find($apertura->id_tipo)->descripcion;
            })
            ->add('fecha_formatted');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->searchable(),

           
                Column::make('Tipo de Caja ', 'id_tipo')
                ->searchable(),

            Column::make('Numero', 'numero')
                ->searchable(),

            Column::make('Año', 'año')
                ->searchable(),

            Column::make('Mes', 'id_mes')
                ->searchable(),

         
            Column::make('Fecha', 'fecha_formatted', 'fecha'),
            Column::action('Acciones')
        ];
    }

    #[On('apertura-created')]
    public function refreshTable(): void
    {
        $this->fillData();
    }


    public function filters(): array
    {
        return [
            Filter::select('id_mes')
                ->dataSource(Mes::all())
                ->optionValue('id')
                ->optionLabel('descripcion'),
            
            Filter::select('año')
                ->dataSource(Apertura::select('año')->distinct()->get())
                ->optionValue('año')
                ->optionLabel('año'),
            
            Filter::select('id_tipo')
                ->dataSource(TipoDeCaja::all())
                ->optionValue('id')
                ->optionLabel('descripcion')
        ];
    }

    public function actions(Apertura $row): array
    {
        return [
            Button::add('edit')
                ->slot('Editar')
                ->id()
                ->class('bg-teal-500 hover:bg-teal-700 text-white py-2 px-4 rounded')
                ->openModal('edit-movimientos-modal', ['aperturaId' => $row->id])
        ];
    }
}
