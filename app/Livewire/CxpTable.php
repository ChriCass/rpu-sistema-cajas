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
use App\Models\Entidad;

final class CxpTable extends PowerGridComponent
{
    public function setUp(): array
    {
        return [
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Documento::select(
                'documentos.id',
                DB::raw("DATE_FORMAT(fechaEmi, '%d/%m/%Y') AS fechaEmi"),
                'tabla10_tipodecomprobantedepagoodocumento.descripcion AS tipoDocumento',
                'documentos.id_entidades',
                'entidades.descripcion AS entidadDescripcion',
                'documentos.serie',
                'documentos.numero',
                'documentos.id_t04tipmon',
                'tasas_igv.tasa',
                'documentos.precio',
                'users.name AS usuario'
            )
            ->leftJoin('entidades', 'documentos.id_entidades', '=', 'entidades.id')
            ->leftJoin('users', 'documentos.id_user', '=', 'users.id')
            ->leftJoin('tabla10_tipodecomprobantedepagoodocumento', 'documentos.id_t10tdoc', '=', 'tabla10_tipodecomprobantedepagoodocumento.id')
            ->leftJoin('tasas_igv', 'documentos.id_tasasIgv', '=', 'tasas_igv.id')
            ->where('documentos.id_tipmov', 2);
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
            Column::make('Entidad', 'entidadDescripcion')->searchable(),
            Column::make('Serie', 'serie')->searchable(),
            Column::make('Número', 'numero')->searchable(),
            Column::make('Moneda', 'id_t04tipmon')->searchable(),
            Column::make('Tasa', 'tasa')->searchable(),
            Column::make('Precio', 'precio')->searchable(),
            Column::make('Usuario', 'usuario')->searchable(),
            Column::action('Acciones')
        ];
    }

    public function filters(): array
    {
        return [
            // Filtro para Tipo de Documento con inputText
            Filter::select('tipoDocumento')
                ->dataSource(TipoDeComprobanteDePagoODocumento::all()->map(function($tipoDoc) {
                    return [
                        'id' => $tipoDoc->id,
                        'descripcion' => $tipoDoc->descripcion
                    ];
                }))
                ->optionValue('id')
                ->optionLabel('descripcion'),

            Filter::inputText('tipoDocumento')
                ->operators(['contains'])
                ->placeholder('Buscar tipo de documento')
                ->builder(function (Builder $builder, $value) {
                    if (is_array($value) && isset($value['value'])) {
                        $builder->where('tabla10_tipodecomprobantedepagoodocumento.descripcion', 'like', "%{$value['value']}%");
                    }
                }),

            // Filtro para Entidad con inputText
            Filter::select('entidadDescripcion', 'documentos.id_entidades')
                ->dataSource(Entidad::all()->map(function($entidad) {
                    return [
                        'id' => $entidad->id,
                        'descripcion' => $entidad->descripcion
                    ];
                }))
                ->optionValue('id')
                ->optionLabel('descripcion'),

            Filter::inputText('entidadDescripcion')
                ->operators(['contains'])
                ->placeholder('Buscar entidad')
                ->builder(function (Builder $builder, $value) {
                    if (is_array($value) && isset($value['value'])) {
                        $builder->where('entidades.descripcion', 'like', "%{$value['value']}%");
                    }
                }),

            // Filtro para Usuario con inputText
            Filter::select('usuario', 'documentos.id_user')
                ->dataSource(User::all()->map(function($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name
                    ];
                }))
                ->optionValue('id')
                ->optionLabel('name'),

            Filter::inputText('usuario')
                ->operators(['contains'])
                ->placeholder('Buscar usuario')
                ->builder(function (Builder $builder, $value) {
                    if (is_array($value) && isset($value['value'])) {
                        $builder->where('users.name', 'like', "%{$value['value']}%");
                    }
                }),

            // Filtro para el campo de descripción (Serie o Número)
            Filter::inputText('serie')
                ->operators(['contains'])
                ->placeholder('Buscar serie')
                ->builder(function (Builder $builder, $value) {
                    if (is_array($value) && isset($value['value'])) {
                        $builder->where('documentos.serie', 'like', "%{$value['value']}%");
                    }
                }),

            Filter::inputText('numero')
                ->operators(['contains'])
                ->placeholder('Buscar número')
                ->builder(function (Builder $builder, $value) {
                    if (is_array($value) && isset($value['value'])) {
                        $builder->where('documentos.numero', 'like', "%{$value['value']}%");
                    }
                })
        ];
    }

    public function actions(Documento $row): array
    {
        return [
            Button::add('edit')
                ->slot('Editar ')
                ->class('bg-teal-500 hover:bg-teal-700 text-white py-2 px-4 rounded')
             //   ->openModal('edit-documento-modal', ['documentoId' => $row->id]),
        ];
    }
}
