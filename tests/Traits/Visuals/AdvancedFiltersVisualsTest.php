<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Traits\Visuals;

use Livewire\Livewire;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Http\Livewire\PetsTableAdvanced;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\TestCaseAdvanced;

class AdvancedFiltersVisualsTest extends TestCaseAdvanced
{
    /** @test */
    public function empty_message_does_not_show_with_results(): void
    {
        Livewire::test(PetsTableAdvanced::class)
            ->assertDontSee('No items found. Try to broaden your search.');
    }

    /** @test */
    public function can_see_html_for_number_range_filter(): void
    {
        Livewire::test(PetsTableAdvanced::class)
            ->assertSeeHtml('div class="range-slider flat"');
    }

    /** @test */
    public function can_see_html_for_date_range_filter(): void
    {
        Livewire::test(PetsTableAdvanced::class)
            ->assertSeeHtml('mode:"range"');
    }

    /** @test */
    public function can_see_html_for_date_picker_filter(): void
    {
        Livewire::test(PetsTableAdvanced::class)
            ->assertSeeHtml('mode:"single"');
    }

    public function can_see_html_for_smart_select_filter(): void
    {
        Livewire::test(PetsTableAdvanced::class)
            ->assertSeeHtml('<input x-on:keydown="open = true" type="search" x-model="search" placeholder="Search Here..."');
    }

    /** @test */
    public function empty_message_shows_with_no_results(): void
    {
        Livewire::test(PetsTableAdvanced::class)
            ->set('table.search', 'sdfsdfsdf')
            ->assertSee('No items found. Try to broaden your search.');
    }

    /** @test */
    public function debugging_shows_when_enabled(): void
    {
        Livewire::test(PetsTableAdvanced::class)
            ->assertDontSee('Debugging Values')
            ->call('setDebugEnabled')
            ->assertSee('Debugging Values');
    }

    /** @test */
    public function offline_message_is_available_when_needed(): void
    {
        Livewire::test(PetsTableAdvanced::class)
            ->assertSeeHtml('<div wire:offline.class.remove="hidden" class="hidden">');
    }
}
