<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Views;

use Illuminate\Database\Eloquent\Builder;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\DateRangeFilter;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\TestCaseAdvanced;

class DateRangeFilterTest extends TestCaseAdvanced
{
    /** @test */
    public function can_get_filter_name(): void
    {
        $filter = DateRangeFilter::make('Active');
        // Matches
        $this->assertSame('Active', $filter->getName());
    }

    /** @test */
    public function can_get_filter_key(): void
    {
        $filter = DateRangeFilter::make('Active');

        $this->assertSame('active', $filter->getKey());
    }

    /** @test */
    public function can_get_filter_configs(): void
    {
        $filter = DateRangeFilter::make('Active');

        $this->assertSame(config('livewiretablesadvancedfilters.dateRange'), $filter->getConfigs());

        $filter->config(['foo' => 'bar']);

        $this->assertSame(array_merge(config('livewiretablesadvancedfilters.dateRange'), ['foo' => 'bar']), $filter->getConfigs());
    }

    /** @test */
    public function get_a_single_filter_config(): void
    {
        $filter = DateRangeFilter::make('Active')
            ->config(['foo' => 'bar']);

        $this->assertSame('bar', $filter->getConfig('foo'));
    }

    /** @test */
    public function can_get_filter_options(): void
    {
        $filter = DateRangeFilter::make('Active');

        $this->assertSame(config('livewiretablesadvancedfilters.dateRange.defaults'), $filter->getOptions());

        $filter->options(['foo' => 'bar']);

        $this->assertSame(['foo' => 'bar'], $filter->getOptions());
    }

    /** @test */
    public function can_get_if_empty(): void
    {
        $filter = DateRangeFilter::make('Active');
        $this->assertTrue($filter->isEmpty(''));
        $this->assertTrue($filter->isEmpty('test'));
    }

    /** @test */
    public function can_check_validation_accepts_valid_values_array(): void
    {
        $filter = DateRangeFilter::make('Active');
        $this->assertSame(['minDate' => '2020-01-01', 'maxDate' => '2020-02-02'], $filter->validate(['2020-01-01', '2020-02-02']));
    }

    /** @test */
    public function can_check_validation_accepts_valid_values_string(): void
    {
        $filter = DateRangeFilter::make('Active');
        $this->assertFalse($filter->validate('2020-01-01 to 2020-02-02'));
    }

    /** @test */
    public function can_check_validation_rejects_invalid_values(): void
    {
        $filter = DateRangeFilter::make('Active');
        $this->assertFalse($filter->validate(['2020-01-01', 'invaliddate']));
    }

    /** @test */
    public function can_get_filter_keys(): void
    {
        $filter = DateRangeFilter::make('Active');

        $this->assertSame(['minDate' => '', 'maxDate' => ''], $filter->getKeys());
    }

    /** @test */
    public function can_get_filter_default_value(): void
    {
        $filter = DateRangeFilter::make('Active');

        $this->assertSame(['minDate' => null, 'maxDate' => null], $filter->getDefaultValue());
    }

    /** @test */
    public function can_get_filter_callback(): void
    {
        $filter = DateRangeFilter::make('Active');

        $this->assertFalse($filter->hasFilterCallback());

        $filter = DateRangeFilter::make('Active')
            ->filter(function (Builder $builder, array $values) {
                return $builder->where('last_visit', '>=', $values['minDate'])
                ->where('last_visit', '<=', $values['maxDate']);
            });

        $this->assertTrue($filter->hasFilterCallback());
        $this->assertIsCallable($filter->getFilterCallback());
    }

    /** @test */
    public function can_get_filter_pill_title(): void
    {
        $filter = DateRangeFilter::make('Active');

        $this->assertSame('Active', $filter->getFilterPillTitle());

        $filter = DateRangeFilter::make('Active')
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
        $filter = DateRangeFilter::make('Active');

        $this->assertTrue($filter->hasConfigs());
    }

    /** @test */
    public function can_check_filter_config_by_name(): void
    {
        $filter = DateRangeFilter::make('Active')
            ->config(['foo' => 'bar']);

        $this->assertTrue($filter->hasConfig('foo'));
        $this->assertFalse($filter->hasConfig('bar'));
    }

    /** @test */
    public function can_check_if_filter_is_hidden_from_menus(): void
    {
        $filter = DateRangeFilter::make('Active');

        $this->assertFalse($filter->isHiddenFromMenus());
        $this->assertTrue($filter->isVisibleInMenus());

        $filter->hiddenFromMenus();

        $this->assertTrue($filter->isHiddenFromMenus());
        $this->assertFalse($filter->isVisibleInMenus());
    }

    /** @test */
    public function can_check_if_filter_is_hidden_from_pills(): void
    {
        $filter = DateRangeFilter::make('Active');

        $this->assertFalse($filter->isHiddenFromPills());
        $this->assertTrue($filter->isVisibleInPills());

        $filter->hiddenFromPills();

        $this->assertTrue($filter->isHiddenFromPills());
        $this->assertFalse($filter->isVisibleInPills());
    }

    /** @test */
    public function can_check_if_filter_is_hidden_from_count(): void
    {
        $filter = DateRangeFilter::make('Active');

        $this->assertFalse($filter->isHiddenFromFilterCount());
        $this->assertTrue($filter->isVisibleInFilterCount());

        $filter->hiddenFromFilterCount();

        $this->assertTrue($filter->isHiddenFromFilterCount());
        $this->assertFalse($filter->isVisibleInFilterCount());
    }

    /** @test */
    public function can_check_if_filter_is_reset_by_clear_button(): void
    {
        $filter = DateRangeFilter::make('Active');

        $this->assertTrue($filter->isResetByClearButton());

        $filter->notResetByClearButton();

        $this->assertFalse($filter->isResetByClearButton());
    }
}
