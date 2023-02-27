<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Traits\Visuals;

use Livewire\Livewire;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\NumberRangeFilter;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Http\Livewire\PetsTableAdvanced;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\TestCaseAdvanced;

class FilterVisualsTestAdvanced extends TestCaseAdvanced
{
    /** @test */
    public function the_number_range_filter_component_can_render()
    {
        Livewire::test(NumberRangeFilter::class)->assertStatus(200);
        Livewire::test(NumberRangeFilter::class)->assertSeeHtml("<div class='range-slider__progress'></div>");
    }

    /** @test */
    public function filters_button_shows_when_enabled(): void
    {
        Livewire::test(PetsTableAdvanced::class)
            ->assertSee('Filters');
    }

    /** @test */
    public function filters_button_shows_when_visible(): void
    {
        Livewire::test(PetsTableAdvanced::class)
            ->call('setFiltersVisibilityEnabled')
            ->assertSee('Filters');
    }

    /** @test */
    public function filters_button_doesnt_show_when_disabled(): void
    {
        Livewire::test(PetsTableAdvanced::class)
            ->call('setFiltersDisabled')
            ->assertDontSee('Filters');
    }

    /** @test */
    public function filters_button_doesnt_show_when_hidden(): void
    {
        Livewire::test(PetsTableAdvanced::class)
            ->call('setFiltersVisibilityDisabled')
            ->assertDontSee('Filters');
    }

    /** @test */
    public function filter_pills_show_when_enabled(): void
    {
        Livewire::test(PetsTableAdvanced::class)
            ->set('table.filters.breed', [1])
            ->assertSee('Applied Filters');
    }

    /** @test */
    public function filter_pills_show_when_visible(): void
    {
        Livewire::test(PetsTableAdvanced::class)
            ->set('table.filters.breed', [1])
            ->call('setFiltersVisibilityEnabled')
            ->assertSee('Applied Filters');
    }

    /** @test */
    public function filter_pills_dont_show_when_disabled(): void
    {
        Livewire::test(PetsTableAdvanced::class)
            ->set('table.filters.breed', [1])
            ->call('setFilterPillsDisabled')
            ->assertDontSee('Applied Filters');
    }

    /** @test */
    public function filter_pills_dont_show_when_hidden(): void
    {
        Livewire::test(PetsTableAdvanced::class)
            ->set('table.filters.breed', [1])
            ->call('setFilterPillsDisabled')
            ->assertDontSee('Applied Filters');
    }

    /** @test */
    public function filter_pills_dont_show_when_no_filters_are_applied(): void
    {
        Livewire::test(PetsTableAdvanced::class)
            ->assertDontSee('Applied Filters');
    }

    /** @test */
    public function filters_with_invalid_key_dont_error(): void
    {
        Livewire::test(PetsTableAdvanced::class)
            ->set('table.filters.invalid-filter', [1])
            ->assertHasNoErrors()
            ->assertDontSee('Applied Filters');
    }

    /** @test */
    public function filters_datepicker_can_be_seen(): void
    {
        Livewire::test(PetsTableAdvanced::class)
            ->assertSeeHtml('<a x-on:click="flatpickr($refs.input).toggle" class="inline-block input-button -ml-8 w-6 h-6">');
    }

    /** @test */
    public function filters_numberrange_can_be_seen(): void
    {
        Livewire::test(PetsTableAdvanced::class)
            ->assertSeeHtml("<div class='range-slider__progress'></div>");
    }

    /** @test */
    public function filters_smartselect_can_be_seen(): void
    {
        Livewire::test(PetsTableAdvanced::class)
            ->assertSeeHtml('smartSelectOpen: false');
    }

    /** @test */
    public function filters_daterange_can_be_seen(): void
    {
        Livewire::test(PetsTableAdvanced::class)
            ->assertSeeHtml('mode:"range"');
    }
}
