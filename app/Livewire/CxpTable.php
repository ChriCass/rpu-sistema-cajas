<?php

namespace App\Livewire;

use App\Models\Documento;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
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
use App\Models\TipoDeComprobanteDePagoODocumento;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Mes;
use App\Models\Entidad;
use App\Models\TasaIgv;
use App\Models\TipoDeMoneda;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use Livewire\Attributes\On;

final class CxpTable extends PowerGridComponent
{
    use WithExport;

    public function setUp(): array
    {
        return [
            Footer::make()
                ->showPerPage()
                ->showRecordCount(mode: 'full'),
                Exportable::make(fileName: 'Tabla Cuentas por Pagar') 
                ->type(Exportable::TYPE_XLS), 
        ];
    }

    public function datasource(): Builder
    {
        $currentYear = now()->year; // Obtiene el año actual
    
        return Documento::select(
                'documentos.id',
                DB::raw("DATE_FORMAT(fechaEmi, '%d/%m/%Y') AS fechaEmi"),
                'tabla10_tipodecomprobantedepagoodocumento.descripcion AS tipoDocumento',
                'documentos.id_entidades as id_entidades',
                'entidades.descripcion AS entidadDescripcion',
                'documentos.serie',
                'documentos.numero',
                'documentos.id_t04tipmon',
                'tasas_igv.tasa',
                'documentos.precio',
                'users.name AS usuario',
                'id_tip_form'
            )
            ->leftJoin('entidades', 'documentos.id_entidades', '=', 'entidades.id')
            ->leftJoin('users', 'documentos.id_user', '=', 'users.id')
            ->leftJoin('tabla10_tipodecomprobantedepagoodocumento', 'documentos.id_t10tdoc', '=', 'tabla10_tipodecomprobantedepagoodocumento.id')
            ->leftJoin('tasas_igv', 'documentos.id_tasasIgv', '=', 'tasas_igv.id')
            ->where('documentos.id_tipmov', 2)
            ->orderByRaw("CASE WHEN YEAR(fechaEmi) = ? THEN 0 ELSE 1 END", [$currentYear]) // Ordena primero el año actual
            ->orderBy('fechaEmi', 'desc') // Orden descendente por fecha de emisión
            ->orderBy('documentos.id', 'desc'); // Orden descendente por ID (opcional)
    }
    public function relationSearch(): array
    {
        return [];
    }


    #[On('cxp-updated')]
    #[On('cxp-created')]
    public function refreshTable(): void
    {
        $this->fillData();
    }
    

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('fechaEmi')
            ->add('tipoDocumento')
            ->add('id_entidades')
            ->add('entidadDescripcion')
            ->add('serie')
            ->add('numero')
            ->add('id_t04tipmon')
            ->add('tasa')
            ->add('precio')
            ->add('usuario');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->searchable(),
            Column::make('Fecha Emisión', 'fechaEmi')->searchable(),

            Column::make('Tipo de Documento', 'tipoDocumento')->searchable(),
            Column::make('id entidades', 'id_entidades')->searchable(),
            Column::make('Entidad', 'entidadDescripcion')->searchable(),
            Column::make('Serie', 'serie')->searchable(),
            Column::make('Número', 'numero')->searchable(),
            Column::make('Moneda', 'id_t04tipmon')->searchable(),
            Column::make('Tasa', 'tasa')->searchable(),
            Column::make('Precio', 'precio')->searchable(),
            Column::make('Usuario', 'usuario')->searchable(),
            Column::action('Acciones')->visibleInExport(visible: false)
        ];
    }

    public function filters(): array
    {
        return [
             // Filtro para 'id entidades'
        Filter::inputText('id_entidades')
        ->operators(['contains'])
        ->placeholder('Buscar por ID de entidad...')
        ->builder(function (Builder $builder, $value) {
            if (!empty($value['value'])) {
                $builder->where('documentos.id_entidades', 'like', "%{$value['value']}%");
            }
        }),

    // Filtro para 'Serie'
    Filter::inputText('serie')
        ->operators(['contains'])
        ->placeholder('Buscar por serie...')
        ->builder(function (Builder $builder, $value) {
            if (!empty($value['value'])) {
                $builder->where('documentos.serie', 'like', "%{$value['value']}%");
            }
        }),

    // Filtro para 'Número'
    Filter::inputText('numero')
        ->operators(['contains'])
        ->placeholder('Buscar por número...')
        ->builder(function (Builder $builder, $value) {
            if (!empty($value['value'])) {
                $builder->where('documentos.numero', 'like', "%{$value['value']}%");
            }
        }),
            // Filtro para Tipo de Documento con inputText
            Filter::inputText('tipoDocumento')
                ->operators(['contains'])
                ->placeholder('Buscar tipo de documento')
                ->builder(function (Builder $builder, $value) {
                    if (!empty($value['value'])) {
                        $builder->where('tabla10_tipodecomprobantedepagoodocumento.descripcion', 'like', "%{$value['value']}%");
                    }
                }),

            // Filtro para Entidad con inputText
            Filter::inputText('entidadDescripcion')
                ->operators(['contains'])
                ->placeholder('Buscar entidad')
                ->builder(function (Builder $builder, $value) {
                    if (!empty($value['value'])) {
                        $builder->where('entidades.descripcion', 'like', "%{$value['value']}%");
                    }
                }),

            // Filtro para Usuario con inputText
            Filter::select('usuario', 'documentos.id_user')
                ->dataSource(User::all()) // Obtén los usuarios desde el modelo User
                ->optionValue('id')
                ->optionLabel('name') // Ajusta al campo que contiene el nombre del usuario

                ->builder(function (Builder $builder, $value) {
                    if (!empty($value)) {
                        $builder->where('documentos.id_user', $value);
                    }
                }),



            Filter::select('fechaEmi', 'documentos.fechaEmi')
                ->dataSource(Mes::all()) // Obtén los meses desde el modelo Mes
                ->optionValue('id')
                ->optionLabel('descripcion') // Ajusta al campo que contiene el nombre del mes

                ->builder(function (Builder $builder, $value) {
                    if (!empty($value)) {
                        $builder->whereMonth('documentos.fechaEmi', $value);
                    }
                }),

            Filter::select('tasa', 'documentos.id_tasasIgv')
                ->dataSource(TasaIgv::all()) // Obtén las tasas desde el modelo TasaIgv
                ->optionValue('id')
                ->optionLabel('tasa') // Ajusta al campo que contiene el valor de la tasa

                ->builder(function (Builder $builder, $value) {
                    if (!empty($value)) {
                        $builder->where('documentos.id_tasasIgv', $value);
                    }
                }),





            // Filtro para Moneda con select
            Filter::select('id_t04tipmon', 'documentos.id_t04tipmon')
                ->dataSource(TipoDeMoneda::all()) // Obtén los tipos de moneda desde el modelo
                ->optionValue('id')
                ->optionLabel('id') // Ajusta 'descripcion' al campo correspondiente del modelo de tipo de moneda

                ->builder(function (Builder $builder, $value) {
                    if (!empty($value)) {
                        $builder->where('documentos.id_t04tipmon', $value);
                    }
                })
        ];
    }


    public function actions(Documento $row): array
    {
        if($row->id_tip_form == "1"){
            return [
                Button::add('edit')
                    ->slot('Editar ')
                    ->class('bg-teal-500 hover:bg-teal-700 text-white py-2 px-4 rounded')->dispatch('showEdCxp', ['idcxp' => $row->id]),
            ];
        }else{
            return [
                Button::add('edit')
                    ->slot('Editar ')
                    ->class('bg-teal-500 hover:bg-teal-700 text-white py-2 px-4 rounded')->route('cxp.avanzado', ['origen' => 'editar_cxp', 'numeroMovimiento' => $row->id]),
            ];   
        }
    }
}
