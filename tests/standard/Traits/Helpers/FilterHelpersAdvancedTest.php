<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Standard\Traits\Helpers;

use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\DatePickerFilter;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\DateRangeFilter;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\NumberRangeFilter;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\SlimSelectFilter;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\SmartSelectFilter;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Standard\TestCaseAdvanced;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectDropdownFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectFilter;

class FilterHelpersAdvancedTest extends TestCaseAdvanced
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
        $this->assertInstanceOf(SlimSelectFilter::class, $this->advancedTable->getFilters()[6]);
    }

    /** @test */
    public function can_get_component_filter_count(): void
    {
        $this->assertEquals(7, $this->advancedTable->getFiltersCount());
    }

    /** @test */
    public function can_get_component_filter_by_key(): void
    {
        $this->assertNotInstanceOf(MultiSelectFilter::class, $this->advancedTable->getFilterByKey('test'));

        $this->assertInstanceOf(MultiSelectFilter::class, $this->advancedTable->getFilterByKey('breed'));
        $this->assertInstanceOf(SmartSelectFilter::class, $this->advancedTable->getFilterByKey('smart'));
        $this->assertInstanceOf(NumberRangeFilter::class, $this->advancedTable->getFilterByKey('range'));
        $this->assertInstanceOf(DateRangeFilter::class, $this->advancedTable->getFilterByKey('daterange'));
        $this->assertInstanceOf(DatePickerFilter::class, $this->advancedTable->getFilterByKey('datepicker'));
        $this->assertInstanceOf(MultiSelectDropdownFilter::class, $this->advancedTable->getFilterByKey('species'));
        $this->assertInstanceOf(SlimSelectFilter::class, $this->advancedTable->getFilterByKey('slim'));

        $this->assertNotInstanceOf(SmartSelectFilter::class, $this->advancedTable->getFilterByKey('species'));
        $this->assertNotInstanceOf(NumberRangeFilter::class, $this->advancedTable->getFilterByKey('species'));
        $this->assertNotInstanceOf(DateRangeFilter::class, $this->advancedTable->getFilterByKey('species'));
        $this->assertNotInstanceOf(DatePickerFilter::class, $this->advancedTable->getFilterByKey('species'));
        $this->assertNotInstanceOf(SlimSelectFilter::class, $this->advancedTable->getFilterByKey('species'));

        $this->assertNotInstanceOf(MultiSelectFilter::class, $this->advancedTable->getFilterByKey('smart'));
        $this->assertNotInstanceOf(MultiSelectFilter::class, $this->advancedTable->getFilterByKey('range'));
        $this->assertNotInstanceOf(MultiSelectFilter::class, $this->advancedTable->getFilterByKey('daterange'));
        $this->assertNotInstanceOf(MultiSelectFilter::class, $this->advancedTable->getFilterByKey('datepicker'));
        $this->assertNotInstanceOf(MultiSelectFilter::class, $this->advancedTable->getFilterByKey('slim'));
    }

    /** @test */
    public function can_set_filter_value(): void
    {
        $this->advancedTable->setFilter('breed', ['1']);

        $this->assertSame(['1'], $this->advancedTable->getAppliedFilterWithValue('breed'));

        $this->advancedTable->setFilter('breed', ['0']);

        $this->advancedTable->setFilter('species', ['1']);

        $this->assertSame(['1'], $this->advancedTable->getAppliedFilterWithValue('species'));

        $this->advancedTable->setFilter('smart', ['1']);

        $this->assertSame(['1'], $this->advancedTable->getAppliedFilterWithValue('smart'));

        $this->advancedTable->setFilter('datepicker', ['2021-01-01']);

        $this->assertSame(['2021-01-01'], $this->advancedTable->getAppliedFilterWithValue('datepicker'));

        $this->advancedTable->setFilter('range', ['min' => '50', 'max' => '50']);

        $this->assertSame(['min' => '50', 'max' => '50'], $this->advancedTable->getAppliedFilterWithValue('range'));
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

        $this->assertSame(['breed' => [], 'smart' => [], 'range' => ['min' => null, 'max' => null], 'daterange' => ['minDate' => null, 'maxDate' => null], 'datepicker' => null, 'species' => [], 'slim' => []], $this->advancedTable->getAppliedFilters());
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
        $this->assertFalse($this->advancedTable->hasAppliedFiltersWithValues());

        $this->advancedTable->setFilter('breed', ['1']);

        $this->assertTrue($this->advancedTable->hasAppliedFiltersWithValues());

        $this->advancedTable->setFilter('breed', []);

        $this->assertFalse($this->advancedTable->hasAppliedFiltersWithValues());

        $this->advancedTable->setFilter('species', ['1']);

        $this->assertTrue($this->advancedTable->hasAppliedFiltersWithValues());

        $this->advancedTable->setFilter('species', []);

        $this->assertFalse($this->advancedTable->hasAppliedFiltersWithValues());

        $this->advancedTable->setFilter('range', ['min' => '50', 'max' => '50']);

        $this->assertTrue($this->advancedTable->hasAppliedFiltersWithValues());

        $this->advancedTable->setFilter('range', ['min' => null, 'max' => null]);

        $this->assertFalse($this->advancedTable->hasAppliedFiltersWithValues());

        $this->advancedTable->setFilter('range', ['min' => '50', 'max' => '50']);

        $this->assertTrue($this->advancedTable->hasAppliedFiltersWithValues());

        $this->advancedTable->setFilter('range', []);

        $this->assertFalse($this->advancedTable->hasAppliedFiltersWithValues());

        $this->advancedTable->setFilter('datepicker', '2020-10-12');

        $this->assertTrue($this->advancedTable->hasAppliedFiltersWithValues());

        $this->advancedTable->setFilter('datepicker', '');

        $this->assertFalse($this->advancedTable->hasAppliedFiltersWithValues());
    }

    /** @test */
    public function can_get_all_applied_filters_with_values(): void
    {
        $this->advancedTable->setFilter('breed', ['1']);

        $this->advancedTable->setFilter('species', ['0']);

        $this->advancedTable->setFilter('datepicker', '2020-10-10');

        $this->assertSame(['breed' => ['1'], 'datepicker' => '2020-10-10', 'species' => ['0']], $this->advancedTable->getAppliedFiltersWithValues());
    }

    /** @test */
    public function can_get_all_applied_filters_with_values_count(): void
    {
        $this->assertSame(0, $this->advancedTable->getAppliedFiltersWithValuesCount());

        $this->advancedTable->setFilter('breed', ['1']);

        $this->assertSame(1, $this->advancedTable->getAppliedFiltersWithValuesCount());

        $this->advancedTable->setFilter('species', ['1']);

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
        $this->assertFalse($this->advancedTable->getFilterSlideDownDefaultStatus());

        $this->advancedTable->setFilterSlideDownDefaultStatusEnabled();

        $this->assertTrue($this->advancedTable->getFilterSlideDownDefaultStatus());
    }*/

    /** @test */
    /*
    public function can_check_if_filter_layout_slidedown_is_hidden(): void
    {
        $this->assertFalse($this->advancedTable->getFilterSlideDownDefaultStatus());

        $this->advancedTable->setFilterSlideDownDefaultStatusDisabled();

        $this->assertFalse($this->advancedTable->getFilterSlideDownDefaultStatus());
    }*/
}
