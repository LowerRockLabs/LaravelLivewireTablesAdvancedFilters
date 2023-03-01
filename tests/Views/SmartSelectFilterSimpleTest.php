<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Views;

use Illuminate\Database\Eloquent\Builder;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\SmartSelectFilter;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Models\Breed;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\TestCaseAdvanced;

class SmartSelectFilterSimpleTest extends TestCaseAdvanced
{
    /** @test */
    public function can_get_filter_name_simple(): void
    {
        $filter = SmartSelectFilter::make('Active');
        // Should match
        $this->assertSame('Active', $filter->getName());
    }

    /** @test */
    public function can_get_filter_key_simple(): void
    {
        $filter = SmartSelectFilter::make('Active');

        $this->assertSame('active', $filter->getKey());
    }

    /** @test */
    public function can_get_filter_configs_simple(): void
    {
        $filter = SmartSelectFilter::make('Active');

        $this->assertSame(config('livewiretablesadvancedfilters.smartSelect'), $filter->getConfigs());

        $filter->config(['foo' => 'bar']);

        $this->assertSame(array_merge(config('livewiretablesadvancedfilters.smartSelect'), ['foo' => 'bar']), $filter->getConfigs());
    }

    /** @test */
    public function get_a_single_filter_config_simple(): void
    {
        $filter = SmartSelectFilter::make('Active')
            ->config(['foo' => 'bar']);

        $this->assertSame('bar', $filter->getConfig('foo'));
    }

    /** @test */
    public function can_get_if_empty_simple(): void
    {
        $filter = SmartSelectFilter::make('Active');
        $this->assertTrue($filter->isEmpty(''));
        $this->assertFalse($filter->isEmpty(['test']));
    }

    /** @test */
    public function can_check_validation_accepts_valid_values_simple(): void
    {
        $filter = SmartSelectFilter::make('Active')->config(['optionsMethod' => 'simple'])->options(
            Breed::select(['id', 'name'])->orderBy('name', 'asc')->pluck('name', 'id')->toArray()
        );
        $this->assertSame(['1', '2'], $filter->validate(['1', '2']));
    }

    /** @test */
    public function can_check_validation_rejects_empty_values_simple(): void
    {
        $filter = SmartSelectFilter::make('Active');
        $this->assertFalse($filter->validate([]));
    }

    /** @test */
    public function can_check_validation_rejects_string_value_simple(): void
    {
        $filter = SmartSelectFilter::make('Active');
        $this->assertFalse($filter->validate(''));
    }

    /** @test */
    public function can_check_validation_rejects_invalid_values_simple(): void
    {
        $filter = SmartSelectFilter::make('Active')->config(['optionsMethod' => 'simple'])->options(
            Breed::select(['id', 'name'])->orderBy('name', 'asc')->pluck('name', 'id')->toArray()
        );
        $this->assertFalse($filter->validate('test'));
    }

    /** @test */
    public function can_check_validation_accepts_string_value_in_simple(): void
    {
        $filter = SmartSelectFilter::make('Active')->config(['optionsMethod' => 'simple'])->options(
            Breed::select(['id', 'name'])->orderBy('name', 'asc')->pluck('name', 'id')->toArray()
        );
        $this->assertSame([0 => '1'], $filter->validate('1'));
    }

    /** @test */
    public function can_check_validation_rejects_invalid_string_value_in_simple(): void
    {
        $filter = SmartSelectFilter::make('Active')->config(['optionsMethod' => 'simple'])->options(
            Breed::select(['id', 'name'])->orderBy('name', 'asc')->pluck('name', 'id')->toArray()
        );
        $this->assertFalse($filter->validate('888'));
    }

    /** @test */
    public function can_get_filter_options_simple(): void
    {
        $filter = SmartSelectFilter::make('Active')->config(['optionsMethod' => 'simple'])->options(
            Breed::select(['id', 'name'])->orderBy('name', 'asc')->pluck('name', 'id')->toArray()
        );
        $this->assertSame(Breed::select(['id', 'name'])->orderBy('name', 'asc')->get()->toArray(), $filter->getOptions());
    }

    /** @test */
    public function can_get_filter_keys_simple(): void
    {
        $filter = SmartSelectFilter::make('Active');

        $this->assertSame([], $filter->getKeys());
    }

    /** @test */
    public function can_get_filter_default_value_simple(): void
    {
        $filter = SmartSelectFilter::make('Active');

        $this->assertSame([], $filter->getDefaultValue());
    }

    /** @test */
    public function can_get_filter_callback_simple(): void
    {
        $filter = SmartSelectFilter::make('Active');

        $this->assertFalse($filter->hasFilterCallback());

        $filter = SmartSelectFilter::make('Active')
        ->options(
            Breed::select(['id', 'name'])->orderBy('name', 'asc')->pluck('name', 'id')->unique()->toArray()
        )->filter(function (Builder $builder, array $values) {
            return $builder->whereIn('breed_id', $values);
        });

        $this->assertTrue($filter->hasFilterCallback());
        $this->assertIsCallable($filter->getFilterCallback());
    }

    /** @test */
    public function can_get_filter_pill_title_simple(): void
    {
        $filter = SmartSelectFilter::make('Active');

        $this->assertSame('Active', $filter->getFilterPillTitle());

        $filter = SmartSelectFilter::make('Active')
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
    public function can_check_if_filter_has_configs_simple(): void
    {
        $filter = SmartSelectFilter::make('Active')->config(['foo' => 'bar']);
        $this->assertTrue($filter->hasConfigs());
    }

    /** @test */
    public function can_check_filter_config_by_name_simple(): void
    {
        $filter = SmartSelectFilter::make('Active')
            ->config(['foo' => 'bar']);

        $this->assertTrue($filter->hasConfig('foo'));
        $this->assertFalse($filter->hasConfig('bar'));
    }

    /** @test */
    public function can_check_if_filter_is_hidden_from_menus_simple(): void
    {
        $filter = SmartSelectFilter::make('Active');

        $this->assertFalse($filter->isHiddenFromMenus());
        $this->assertTrue($filter->isVisibleInMenus());

        $filter->hiddenFromMenus();

        $this->assertTrue($filter->isHiddenFromMenus());
        $this->assertFalse($filter->isVisibleInMenus());
    }

    /** @test */
    public function can_check_if_filter_is_hidden_from_pills_simple(): void
    {
        $filter = SmartSelectFilter::make('Active');

        $this->assertFalse($filter->isHiddenFromPills());
        $this->assertTrue($filter->isVisibleInPills());

        $filter->hiddenFromPills();

        $this->assertTrue($filter->isHiddenFromPills());
        $this->assertFalse($filter->isVisibleInPills());
    }

    /** @test */
    public function can_check_if_filter_is_hidden_from_count_simple(): void
    {
        $filter = SmartSelectFilter::make('Active');

        $this->assertFalse($filter->isHiddenFromFilterCount());
        $this->assertTrue($filter->isVisibleInFilterCount());

        $filter->hiddenFromFilterCount();

        $this->assertTrue($filter->isHiddenFromFilterCount());
        $this->assertFalse($filter->isVisibleInFilterCount());
    }

    /** @test */
    public function can_check_if_filter_is_reset_by_clear_button_simple(): void
    {
        $filter = SmartSelectFilter::make('Active');

        $this->assertTrue($filter->isResetByClearButton());

        $filter->notResetByClearButton();

        $this->assertFalse($filter->isResetByClearButton());
    }
}
