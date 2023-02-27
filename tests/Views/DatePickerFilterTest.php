<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Views;

use Illuminate\Database\Eloquent\Builder;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\DatePickerFilter;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\TestCaseAdvanced;

class DatePickerFilterTest extends TestCaseAdvanced
{
    /** @test */
    public function can_get_filter_name(): void
    {
        $filter = DatePickerFilter::make('Active');

        $this->assertSame('Active', $filter->getName());
    }

    /** @test */
    public function can_get_filter_key(): void
    {
        $filter = DatePickerFilter::make('Active');

        $this->assertSame('active', $filter->getKey());
    }

    /** @test */
    public function can_get_filter_configs(): void
    {
        $filter = DatePickerFilter::make('Active');

        $this->assertSame(config('livewiretablesadvancedfilters.datePicker'), $filter->getConfigs());

        $filter->config(['foo' => 'bar']);

        $this->assertSame(array_merge(config('livewiretablesadvancedfilters.datePicker'), ['foo' => 'bar']), $filter->getConfigs());
    }

    /** @test */
    public function can_get_filter_options(): void
    {
        $filter = DatePickerFilter::make('Active');

        $this->assertSame(config('livewiretablesadvancedfilters.datePicker.defaults'), $filter->getOptions());

        $filter->options(['foo' => 'bar']);

        $this->assertSame(['foo' => 'bar'], $filter->getOptions());
    }

    /** @test */
    public function can_get_if_empty(): void
    {
        $filter = DatePickerFilter::make('Active');
        $this->assertTrue($filter->isEmpty(''));
        $this->assertFalse($filter->isEmpty('test'));
    }

    /** @test */
    public function can_check_validation_accepts_valid_values(): void
    {
        $filter = DatePickerFilter::make('Active');
        $this->assertSame('2020-01-01', $filter->validate('2020-01-01'));
    }

    /** @test */
    public function can_check_validation_rejects_invalid_values(): void
    {
        $filter = DatePickerFilter::make('Active');
        $this->assertFalse($filter->validate('test'));
    }

    /** @test */
    public function get_a_single_filter_config(): void
    {
        $filter = DatePickerFilter::make('Active')
            ->config(['foo' => 'bar']);

        $this->assertSame('bar', $filter->getConfig('foo'));
    }

    /** @test */
    public function can_get_filter_keys(): void
    {
        $filter = DatePickerFilter::make('Active');

        $this->assertSame([], $filter->getKeys());
    }

    /** @test */
    public function can_get_filter_default_value(): void
    {
        $filter = DatePickerFilter::make('Active');
        // Should be array
        $this->assertSame([], $filter->getDefaultValue());
    }

    /** @test */
    public function can_get_filter_callback(): void
    {
        $filter = DatePickerFilter::make('Active');

        $this->assertFalse($filter->hasFilterCallback());

        $filter = DatePickerFilter::make('Active')
            ->filter(function (Builder $builder, string $value) {
                return $builder->where('created_at', '>', $value);
            });

        $this->assertTrue($filter->hasFilterCallback());
        $this->assertIsCallable($filter->getFilterCallback());
    }

    /** @test */
    public function can_get_filter_pill_title(): void
    {
        $filter = DatePickerFilter::make('Active');

        $this->assertSame('Active', $filter->getFilterPillTitle());

        $filter = DatePickerFilter::make('Active')
            ->setFilterPillTitle('User Status');

        $this->assertSame('User Status', $filter->getFilterPillTitle());
    }

    /** @test */
    /*
    public function can_get_filter_pill_value(): void
    {
        $filter = NumberRangeFilter::make('Active')
            ->options(['foo' => 'bar']);

        $this->assertSame('bar', $filter->getFilterPillValue('foo'));

        $filter = NumberRangeFilter::make('Active')
            ->options(['foo' => 'bar'])
            ->setFilterPillValues(['foo' => 'baz']);

        $this->assertSame('baz', $filter->getFilterPillValue('foo'));
    }*/

    /** @test */
    /*
    public function can_get_nested_filter_pill_value(): void
    {
        $filter = NumberRangeFilter::make('Active')
            ->options(['foo' => ['bar' => 'baz']]);

        $this->assertSame('baz', $filter->getFilterPillValue('bar'));

        $filter = NumberRangeFilter::make('Active')
            ->options(['foo' => ['bar' => 'baz']])
            ->setFilterPillValues(['bar' => 'etc']);

        $this->assertSame('etc', $filter->getFilterPillValue('bar'));
    }*/

    /** @test */
    public function can_check_if_filter_has_configs(): void
    {
        $filter = DatePickerFilter::make('Active');

        $this->assertTrue($filter->hasConfigs());
    }

    /** @test */
    public function can_check_filter_config_by_name(): void
    {
        $filter = DatePickerFilter::make('Active')
            ->config(['foo' => 'bar']);

        $this->assertTrue($filter->hasConfig('foo'));
        $this->assertFalse($filter->hasConfig('bar'));
    }

    /** @test */
    public function can_check_if_filter_is_hidden_from_menus(): void
    {
        $filter = DatePickerFilter::make('Active');

        $this->assertFalse($filter->isHiddenFromMenus());
        $this->assertTrue($filter->isVisibleInMenus());

        $filter->hiddenFromMenus();

        $this->assertTrue($filter->isHiddenFromMenus());
        $this->assertFalse($filter->isVisibleInMenus());
    }

    /** @test */
    public function can_check_if_filter_is_hidden_from_pills(): void
    {
        $filter = DatePickerFilter::make('Active');

        $this->assertFalse($filter->isHiddenFromPills());
        $this->assertTrue($filter->isVisibleInPills());

        $filter->hiddenFromPills();

        $this->assertTrue($filter->isHiddenFromPills());
        $this->assertFalse($filter->isVisibleInPills());
    }

    /** @test */
    public function can_check_if_filter_is_hidden_from_count(): void
    {
        $filter = DatePickerFilter::make('Active');

        $this->assertFalse($filter->isHiddenFromFilterCount());
        $this->assertTrue($filter->isVisibleInFilterCount());

        $filter->hiddenFromFilterCount();

        $this->assertTrue($filter->isHiddenFromFilterCount());
        $this->assertFalse($filter->isVisibleInFilterCount());
    }

    /** @test */
    public function can_check_if_filter_is_reset_by_clear_button(): void
    {
        $filter = DatePickerFilter::make('Active');

        $this->assertTrue($filter->isResetByClearButton());

        $filter->notResetByClearButton();

        $this->assertFalse($filter->isResetByClearButton());
    }
}
