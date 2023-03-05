<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Http\Livewire;

use Illuminate\Database\Eloquent\Builder;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\DatePickerFilter;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\DateRangeFilter;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\NumberRangeFilter;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\SlimSelectFilter;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\SmartSelectFilter;
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

            SmartSelectFilter::make('Smart')
            ->options(
                Breed::query()
                    ->orderBy('name')
                    ->get()
                    ->keyBy('id')
                    ->map(fn ($breed) => $breed->name)
                    ->toArray()
            )->filter(function (Builder $builder, array $values) {
                return $builder->whereIn('breed_id', $values);
            }),

            NumberRangeFilter::make('Range')
            ->options(
                [
                    'min' => 0,
                    'max' => 20,
                ]
            )
            ->filter(function (Builder $builder, array $values) {
                return $builder->where('breed_id', '>', $values['min'])
                ->where('breed_id', '<', $values['max']);
            }),

            DateRangeFilter::make('Daterange')
            ->config([
                'ariaDateFormat' => 'F j, Y',
                'dateFormat' => 'Y-m-d',
                'defaultStartDate' => date('Y-m-d'),
                'defaultEndDate' => date('Y-m-d'),
                'earliestDate' => '2022-01-01',
                'latestDate' => date('Y-m-d'),
            ])
            ->filter(function (Builder $builder, array $dateRange) {
                return $builder->where('last_visit', '>=', $dateRange['minDate'])->where('last_visit', '<=', $dateRange['maxDate']);
            }),

            DatePickerFilter::make('Datepicker')
            ->config([
                'ariaDateFormat' => 'F j, Y',
                'dateFormat' => 'Y-m-d',
                'defaultStartDate' => date('Y-m-d'),
                'defaultEndDate' => date('Y-m-d'),
                'earliestDate' => '2022-01-01',
                'latestDate' => date('Y-m-d'),
            ])
            ->filter(function (Builder $builder, string $datePicker) {
                return $builder->where('last_visit', '=', $datePicker);
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

            SlimSelectFilter::make('Slim')->options(
                Breed::query()
                ->select('id', 'name')
                ->orderBy('name')
                ->get()
                ->map(function ($breed) {
                    return [
                        'id' => $breed->id,
                        'name' => $breed->name,
                        'text' => $breed->name,
                        'value' => $breed->id,
                        'html' => $breed->name,
                    ];
                })->toArray()
            )->filter(function (Builder $builder, array $values) {
                return $builder->whereIn('breed_id', $values);
            }),
        ];
    }
}
