<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Standard\Http\Livewire;

use Illuminate\Database\Eloquent\Builder;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Standard\Models\Breed;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Standard\Models\Pet;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Standard\Models\Species;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectDropdownFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectFilter;

class PetsTable extends DataTableComponent
{
    public $model = Pet::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable()
                ->setSortingPillTitle('Key')
                ->setSortingPillDirections('0-9', '9-0'),
            Column::make('Sort')
                ->sortable()
                ->excludeFromColumnSelect(),
            Column::make('Name')
                ->sortable()
                ->searchable(),
            Column::make('Age'),
            Column::make('Breed', 'breed.name')
                ->sortable(),
            Column::make('Other')
                ->label(function ($row, Column $column) {
                    return 'Other';
                }),
        ];
    }

    public function filters(): array
    {
        return [

            MultiSelectFilter::make('Breed')
            ->options(
                Breed::query()
                    ->orderBy('name')
                    ->get()
                    ->keyBy('id')
                    ->map(fn ($breed) => $breed->name)
                    ->toArray()
            )
            ->filter(function (Builder $builder, array $values) {
                return $builder->whereIn('breed_id', $values);
            }),

            MultiSelectDropdownFilter::make('Species')
            ->options(
                Species::query()
                    ->orderBy('name')
                    ->get()
                    ->keyBy('id')
                    ->map(fn ($species) => $species->name)
                    ->toArray()
            )
            ->filter(function (Builder $builder, array $values) {
                return $builder->whereIn('species_id', $values);
            }),
        ];
    }
}
