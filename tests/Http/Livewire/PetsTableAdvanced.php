<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Http\Livewire;

use Illuminate\Database\Eloquent\Builder;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\DateRangeFilter;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\NumberRangeFilter;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Models\Breed;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Models\Pet;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Models\Species;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectDropdownFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectFilter;

class PetsTableAdvanced extends DataTableComponent
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
            NumberRangeFilter::make('Breed ID Range')
            ->options(
                [
                    'min' => 0,
                    'max' => 20,
                ]
            )
            ->filter(function (Builder $builder, array $values) {
                return $builder->where('species_id', '>', $values['min'])
                ->where('species_id', '<', $values['max']);
            }),

            DateRangeFilter::make('Created Date')
            ->config([
                'ariaDateFormat' => 'F j, Y',
                'dateFormat' => 'Y-m-d',
                'defaultStartDate' => date('Y-m-d'),
                'defaultEndDate' => date('Y-m-d'),
                'minDate' => '2022-01-01',
                'maxDate' => date('Y-m-d'),
            ])
            ->filter(function (Builder $builder, array $dateRange) {
                $builder->where('created_at', '>=', $dateRange['min'])->where('created_at', '<=', $dateRange['max']);
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
