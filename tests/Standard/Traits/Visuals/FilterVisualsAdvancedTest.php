<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Standard\Traits\Visuals;

use Livewire\Livewire;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Standard\Http\Livewire\PetsTableAdvanced;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Standard\TestCaseAdvanced;

class FilterVisualsAdvancedTest extends TestCaseAdvanced
{
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

    /*
    public function filters_button_doesnt_show_when_hidden(): void
    {
        Livewire::test(PetsTableAdvanced::class)
            ->call('setFiltersVisibilityDisabled')
            ->assertDontSee('Filters');
    }*/

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
    /*public function filter_pills_dont_show_when_no_filters_are_applied(): void
    {
        Livewire::test(PetsTableAdvanced::class)
            ->assertDontSee('Applied Filters');
    }*/

    /** @test */
    public function filters_with_invalid_key_dont_error(): void
    {
        Livewire::test(PetsTableAdvanced::class)
            ->set('table.filters.invalid-filter', [1])
            ->assertHasNoErrors()
            ->assertDontSee('Applied Filters');
    }

    /** @test */
    public function filters_numberrange_can_be_seen(): void
    {
        Livewire::test(PetsTableAdvanced::class)
            ->assertSeeHtml('range-slider');
    }

    /** @test */
    public function filters_smartselect_can_be_seen(): void
    {
        Livewire::test(PetsTableAdvanced::class)
            ->assertSeeHtml('updateCurrentFilteredList');
    }

    /** @test */
    public function filters_daterange_can_be_seen(): void
    {
        Livewire::test(PetsTableAdvanced::class)
            ->assertSeeHtml("mode: 'range'");
    }

    /** @test */
    public function filters_datepicker_can_be_seen(): void
    {
        Livewire::test(PetsTableAdvanced::class)
            ->assertSeeHtml("mode: 'single'");
    }

        /** @test */
        public function filters_slimselect_can_be_seen(): void
        {
            Livewire::test(PetsTableAdvanced::class)
                ->assertSeeHtml('slimSelect');
        }
}
