<?php

namespace App\Livewire;
use Illuminate\Support\Facades\Log;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Familia;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Filters\TextFilter;
use Livewire\Attributes\On;
class FamiliaTable extends DataTableComponent
{
    protected $model = Familia::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setSearchDisabled(); // Desactivar búsqueda global
        $this->setColumnSelectDisabled(); // Desactivar selección de columnas


       
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable()
                ->searchable(),
            Column::make("Descripcion", "descripcion")
            ->sortable()
            ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            TextFilter::make('Id')
                ->config([
                    'placeholder' => 'Buscar Id',
                    'maxlength' => '255',
                ])
                ->filter(function(Builder $builder, string $value) {
                    $builder->whereRaw('id like ?', ['%' . $value . '%']);
                }),

            TextFilter::make('Descripcion')
                ->config([
                    'placeholder' => 'Buscar Descripcion',
                    'maxlength' => '255',
                ])
                ->filter(function(Builder $builder, string $value) {
                    $builder->whereRaw('LOWER(descripcion) like ?', ['%' . strtolower($value) . '%']);
                }),
        ];
    }

    #[On('familia-created')]
    public function refreshTable()
    {
    }

    public function builder(): Builder
    {
        // Log para indicar que el método builder fue llamado
        Log::info('Método builder llamado en FamiliaTable');

        // Consulta con filtro aplicado
        $query = Familia::query()->where('id', 'not like', '0%');

        // Log para verificar la consulta SQL generada
        Log::info("Consulta SQL generada: " . $query->toSql());

        // Log para verificar cuántos registros se obtuvieron después de aplicar el filtro
        $count = $query->count();
        Log::info("Número de registros después de aplicar el filtro: {$count}");

        return $query;
    }
}
