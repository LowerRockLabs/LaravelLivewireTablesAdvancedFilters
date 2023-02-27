<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Traits\Helpers;

use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\DatePickerFilter;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\DateRangeFilter;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\NumberRangeFilter;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\SmartSelectFilter;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\TestCaseAdvanced;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectDropdownFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectFilter;

class AdvancedFilterHelpersTest extends TestCaseAdvanced
{
    /** @test */
    public function can_get_filters_status(): void
    {
        $this->assertTrue($this->advancedTable->filtersAreEnabled());

        $this->advancedTable->setFiltersDisabled();

        $this->assertTrue($this->advancedTable->filtersAreDisabled());

        $this->advancedTable->setFiltersEnabled();

        $this->assertTrue($this->advancedTable->filtersAreEnabled());
    }

    /** @test */
    public function can_get_filters_visibility_status(): void
    {
        $this->assertTrue($this->advancedTable->filtersVisibilityIsEnabled());

        $this->advancedTable->setFiltersVisibilityDisabled();

        $this->assertTrue($this->advancedTable->filtersVisibilityIsDisabled());

        $this->advancedTable->setFiltersVisibilityEnabled();

        $this->assertTrue($this->advancedTable->filtersVisibilityIsEnabled());
    }

    /** @test */
    public function can_get_filter_pills_status(): void
    {
        $this->assertTrue($this->advancedTable->filterPillsAreEnabled());

        $this->advancedTable->setFilterPillsDisabled();

        $this->assertTrue($this->advancedTable->filterPillsAreDisabled());

        $this->advancedTable->setFilterPillsEnabled();

        $this->assertTrue($this->advancedTable->filterPillsAreEnabled());
    }

    /** @test */
    public function can_check_if_component_has_filters(): void
    {
        $this->assertTrue($this->advancedTable->hasFilters());
    }

    /** @test */
    public function can_get_component_filters(): void
    {
        $this->assertInstanceOf(MultiSelectFilter::class, $this->advancedTable->getFilters()[0]);
        $this->assertInstanceOf(SmartSelectFilter::class, $this->advancedTable->getFilters()[1]);
        $this->assertInstanceOf(NumberRangeFilter::class, $this->advancedTable->getFilters()[2]);
        $this->assertInstanceOf(DateRangeFilter::class, $this->advancedTable->getFilters()[3]);
        $this->assertInstanceOf(DatePickerFilter::class, $this->advancedTable->getFilters()[4]);
        $this->assertInstanceOf(MultiSelectDropdownFilter::class, $this->advancedTable->getFilters()[5]);
    }

    /** @test */
    public function can_get_component_filter_count(): void
    {
        $this->assertEquals(6, $this->advancedTable->getFiltersCount());
    }

    /** @test */
    public function can_get_component_filter_by_key(): void
    {
        $this->assertNotInstanceOf(MultiSelectFilter::class, $this->advancedTable->getFilterByKey('test'));

        $this->assertInstanceOf(MultiSelectFilter::class, $this->advancedTable->getFilterByKey('breed'));

        $this->assertNotInstanceOf(MultiSelectDropdownFilter::class, $this->advancedTable->getFilterByKey('test'));

        $this->assertInstanceOf(MultiSelectDropdownFilter::class, $this->advancedTable->getFilterByKey('species'));

        $this->assertInstanceOf(SmartSelectFilter::class, $this->advancedTable->getFilterByKey('smart'));

        $this->assertNotInstanceOf(MultiSelectDropdownFilter::class, $this->advancedTable->getFilterByKey('smart'));

        $this->assertInstanceOf(NumberRangeFilter::class, $this->advancedTable->getFilterByKey('range'));

        $this->assertNotInstanceOf(MultiSelectDropdownFilter::class, $this->advancedTable->getFilterByKey('range'));

        $this->assertInstanceOf(DateRangeFilter::class, $this->advancedTable->getFilterByKey('daterange'));

        $this->assertNotInstanceOf(MultiSelectDropdownFilter::class, $this->advancedTable->getFilterByKey('daterange'));

        $this->assertInstanceOf(DatePickerFilter::class, $this->advancedTable->getFilterByKey('datepicker'));

        $this->assertNotInstanceOf(MultiSelectDropdownFilter::class, $this->advancedTable->getFilterByKey('datepicker'));
    }

    /** @test */
    public function can_set_filter_value(): void
    {
        $this->advancedTable->setFilter('breed', ['1']);

        $this->assertSame(['1'], $this->advancedTable->getAppliedFilterWithValue('breed'));

        $this->advancedTable->setFilter('breed', ['0']);

        $this->advancedTable->setFilter('species', ['1']);

        $this->assertSame(['1'], $this->advancedTable->getAppliedFilterWithValue('species'));
    }

    /** @test */
    public function advanced_can_set_filter_value(): void
    {
        $this->advancedTable->setFilter('datepicker', '2021-01-01');
        // Check Helpers
        $this->assertSame('2021-01-01', $this->advancedTable->getAppliedFilterWithValue('datepicker'));

        $this->advancedTable->setFilter('species', ['1']);

        $this->assertSame(['1'], $this->advancedTable->getAppliedFilterWithValue('species'));

        $this->assertSame('2021-01-01', $this->advancedTable->getAppliedFilterWithValue('datepicker'));
    }

    /** @test */
    public function can_select_all_filter_options(): void
    {
        $this->advancedTable->selectAllFilterOptions('breed');

        $this->assertSame([
            1,
            200,
            100,
            201,
            101,
            2,
            202,
            4,
            3,
            102,
        ], $this->advancedTable->getAppliedFilterWithValue('breed'));
    }

    /** @test */
    public function can_set_filter_defaults(): void
    {
        $this->advancedTable->setFilter('breed', ['1']);

        $this->assertSame(['1'], $this->advancedTable->getAppliedFilterWithValue('breed'));

        $this->advancedTable->setFilterDefaults();

        $this->assertSame(['breed' => [],
            'smart' => [],
            'range' => ['min' => 0, 'max' => 100],
            'daterange' => ['minDate' => '', 'maxDate' => ''],
            'datepicker' => [],
            'species' => [],
        ], $this->advancedTable->getAppliedFilters());
    }

    /** @test */
    public function can_not_set_invalid_filter(): void
    {
        $this->advancedTable->setFilter('invalid-filter', ['1']);

        $this->assertNull($this->advancedTable->getAppliedFilterWithValue('invalid-filter'));

        $this->assertArrayNotHasKey('invalid-filter', $this->advancedTable->getAppliedFilters());
    }

    /** @test */
    public function can_see_if_filters_set_with_values(): void
    {
        $this->advancedTable->setFilterDefaults();

        $this->assertTrue($this->advancedTable->hasAppliedFiltersWithValues());

        $this->advancedTable->setFilter('breed', ['1']);

        $this->assertTrue($this->advancedTable->hasAppliedFiltersWithValues());

        $this->advancedTable->setFilter('breed', []);

        $this->assertTrue($this->advancedTable->hasAppliedFiltersWithValues());

        $this->advancedTable->setFilter('species', ['1']);

        $this->assertTrue($this->advancedTable->hasAppliedFiltersWithValues());

        $this->advancedTable->setFilter('smart', []);
        $this->advancedTable->setFilter('range', []);
        $this->advancedTable->setFilter('daterange', []);
        $this->advancedTable->setFilter('datepicker', []);
        $this->advancedTable->setFilter('species', []);

        $this->assertFalse($this->advancedTable->hasAppliedFiltersWithValues());

        $this->advancedTable->setFilter('range', ['min' => 10, 'max' => 50]);
        $this->assertTrue($this->advancedTable->hasAppliedFiltersWithValues());
        $this->advancedTable->setFilter('range', []);
        $this->assertFalse($this->advancedTable->hasAppliedFiltersWithValues());
    }

    /** @test */
    public function can_get_all_applied_filters_with_values(): void
    {
        $this->advancedTable->setFilter('breed', ['1']);

        $this->advancedTable->setFilter('species', ['0']);

        $this->assertSame(['breed' => ['1'], 'range' => ['min' => 0, 'max' => 100], 'daterange' => ['minDate' => '', 'maxDate' => ''], 'species' => ['0']], $this->advancedTable->getAppliedFiltersWithValues());
    }

    /** @test */
    public function advanced_can_get_all_applied_filters_with_values_count(): void
    {
        $this->advancedTable->setFilterDefaults();

        $this->assertSame(2, $this->advancedTable->getAppliedFiltersWithValuesCount());

        $this->advancedTable->setFilter('breed', ['1']);

        $this->assertSame(3, $this->advancedTable->getAppliedFiltersWithValuesCount());

        $this->advancedTable->setFilter('species', ['1']);

        $this->assertSame(4, $this->advancedTable->getAppliedFiltersWithValuesCount());

        $this->advancedTable->setFilter('datepicker', '2021-01-01');

        $this->assertSame(5, $this->advancedTable->getAppliedFiltersWithValuesCount());

        $this->advancedTable->setFilter('breed', []);

        $this->assertSame(4, $this->advancedTable->getAppliedFiltersWithValuesCount());

        $this->advancedTable->setFilterDefaults();

        $this->assertSame(2, $this->advancedTable->getAppliedFiltersWithValuesCount());
    }

    /** @test */
    public function can_check_if_filter_layout_is_popover(): void
    {
        $this->assertTrue($this->advancedTable->isFilterLayoutPopover());
    }

    /** @test */
    public function can_check_if_filter_layout_is_slidedown(): void
    {
        $this->assertFalse($this->advancedTable->isFilterLayoutSlideDown());

        $this->advancedTable->setFilterLayoutSlideDown();

        $this->assertTrue($this->advancedTable->isFilterLayoutSlideDown());
    }

    /** @test */
    /*
    public function can_check_if_filter_layout_slidedown_is_visible(): void
    {
        $this->assertFalse($this->basicTable->getFilterSlideDownDefaultStatus());

        $this->basicTable->setFilterSlideDownDefaultStatusEnabled();

        $this->assertTrue($this->basicTable->getFilterSlideDownDefaultStatus());
    }*/

    /** @test */
    /*
    public function can_check_if_filter_layout_slidedown_is_hidden(): void
    {
        $this->assertFalse($this->basicTable->getFilterSlideDownDefaultStatus());

        $this->basicTable->setFilterSlideDownDefaultStatusDisabled();

        $this->assertFalse($this->basicTable->getFilterSlideDownDefaultStatus());
    }*/
}
