<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Views;

use Illuminate\Database\Eloquent\Builder;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\SmartSelectFilter;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Models\Breed;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\TestCaseAdvanced;

class SmartSelectFilterStandardTest extends TestCaseAdvanced
{
    /** @test */
    public function can_get_filter_name(): void
    {
        $filter = SmartSelectFilter::make('Active');
        // Should match
        $this->assertSame('Active', $filter->getName());
    }

    /** @test */
    public function can_get_filter_key(): void
    {
        $filter = SmartSelectFilter::make('Active');

        $this->assertSame('active', $filter->getKey());
    }

    /** @test */
    public function can_set_icon_styling(): void
    {
        $filter = SmartSelectFilter::make('Active');
        $filter->setIconStyling(true, '#000000', '25', 'both');

        $this->assertTrue($filter->getConfig('iconStyling')['add']['svgEnabled']);
        $this->assertSame('#000000', $filter->getConfig('iconStyling')['add']['svgFill']);
        $this->assertSame('25', $filter->getConfig('iconStyling')['add']['svgSize']);
        $this->assertTrue($filter->getConfig('iconStyling')['delete']['svgEnabled']);
        $this->assertSame('#000000', $filter->getConfig('iconStyling')['delete']['svgFill']);
        $this->assertSame('25', $filter->getConfig('iconStyling')['delete']['svgSize']);

        $filter->setIconStyling(false, '#FF0000', '50', 'add');
        $this->assertFalse($filter->getConfig('iconStyling')['add']['svgEnabled']);
        $this->assertSame('#FF0000', $filter->getConfig('iconStyling')['add']['svgFill']);
        $this->assertSame('50', $filter->getConfig('iconStyling')['add']['svgSize']);
        $this->assertTrue($filter->getConfig('iconStyling')['delete']['svgEnabled']);
        $this->assertSame('#000000', $filter->getConfig('iconStyling')['delete']['svgFill']);
        $this->assertSame('25', $filter->getConfig('iconStyling')['delete']['svgSize']);

        $filter->setIconStyling(false, '#FF00FF', '55', 'delete');
        $this->assertFalse($filter->getConfig('iconStyling')['add']['svgEnabled']);
        $this->assertSame('#FF0000', $filter->getConfig('iconStyling')['add']['svgFill']);
        $this->assertSame('50', $filter->getConfig('iconStyling')['add']['svgSize']);
        $this->assertFalse($filter->getConfig('iconStyling')['delete']['svgEnabled']);
        $this->assertSame('#FF00FF', $filter->getConfig('iconStyling')['delete']['svgFill']);
        $this->assertSame('55', $filter->getConfig('iconStyling')['delete']['svgSize']);
    }

    /** @test */
    public function can_set_icon_styling_config(): void
    {
        $filter = SmartSelectFilter::make('Active')->config([
            'iconStyling' => [
                'add' => [
                    'classes' => '',        // Base classes for the "add" icon
                    'defaults' => true,     // Determines whether to merge (true) or replace (false) the default class (inline-block)
                    'svgEnabled' => true,  // Enable or Disable the use of the default SVG icon
                    'svgFill' => '#000000', // Fill for the SVG Icon
                    'svgSize' => '1.5em',   // Size for the SVG Icon
                ],
                'delete' => [
                    'classes' => '',        // Base classes for the "delete" icon
                    'defaults' => true,     // Determines whether to merge (true) or replace (false) the default class (inline-block)
                    'svgEnabled' => false,   // Enable or Disable the use of the default SVG icon
                    'svgFill' => '#000000', // Fill for the SVG Icon
                    'svgSize' => '1.5em',   // Size for the SVG Icon
                ],
            ]]);
        $this->assertTrue($filter->getConfig('iconStyling')['add']['svgEnabled']);
        $this->assertFalse($filter->getConfig('iconStyling')['delete']['svgEnabled']);
    }

    /** @test */
    public function can_get_filter_configs(): void
    {
        $filter = SmartSelectFilter::make('Active');

        $this->assertSame(config('livewiretablesadvancedfilters.smartSelect'), $filter->getConfigs());

        $filter->config(['foo' => 'bar']);

        $this->assertSame(array_merge(config('livewiretablesadvancedfilters.smartSelect'), ['foo' => 'bar']), $filter->getConfigs());
    }

    /** @test */
    public function get_a_single_filter_config(): void
    {
        $filter = SmartSelectFilter::make('Active')
            ->config(['foo' => 'bar']);

        $this->assertSame('bar', $filter->getConfig('foo'));
    }

    /** @test */
    public function can_get_if_empty(): void
    {
        $filter = SmartSelectFilter::make('Active');
        $this->assertTrue($filter->isEmpty(''));
        $this->assertFalse($filter->isEmpty(['test']));
    }

    /** @test */
    public function can_check_validation_accepts_valid_values(): void
    {
        $filter = SmartSelectFilter::make('Active')->options(
            Breed::select(['id', 'name'])->orderBy('name', 'asc')->pluck('name', 'id')->toArray()
        );
        $this->assertSame(['1', '2'], $filter->validate(['1', '2']));
    }

    /** @test */
    public function can_check_validation_rejects_empty_values(): void
    {
        $filter = SmartSelectFilter::make('Active');
        $this->assertFalse($filter->validate([]));
    }

    /** @test */
    public function can_check_validation_rejects_string_value(): void
    {
        $filter = SmartSelectFilter::make('Active');
        $this->assertFalse($filter->validate(''));
    }

    /** @test */
    public function can_check_validation_rejects_invalid_values(): void
    {
        $filter = SmartSelectFilter::make('Active')->options(
            Breed::select(['id', 'name'])->orderBy('name', 'asc')->pluck('name', 'id')->toArray()
        );
        $this->assertFalse($filter->validate('test'));
    }

    /** @test */
    public function can_check_validation_accepts_string_value_in(): void
    {
        $filter = SmartSelectFilter::make('Active')->options(
            Breed::select(['id', 'name'])->orderBy('name', 'asc')->pluck('name', 'id')->toArray()
        );
        $this->assertSame([0 => '1'], $filter->validate('1'));
    }

    /** @test */
    public function can_check_validation_rejects_invalid_string_value_in(): void
    {
        $filter = SmartSelectFilter::make('Active')->options(
            Breed::select(['id', 'name'])->orderBy('name', 'asc')->pluck('name', 'id')->toArray()
        );
        $this->assertFalse($filter->validate('888'));
    }

    /** @test */
    public function can_get_filter_options(): void
    {
        $filter = SmartSelectFilter::make('Active')->options(
            Breed::select(['id', 'name'])->orderBy('name', 'asc')->pluck('name', 'id')->toArray()
        );
        $this->assertSame(Breed::select(['id', 'name'])->orderBy('name', 'asc')->get()->toArray(), $filter->getOptions());
    }

    /** @test */
    public function can_get_filter_keys(): void
    {
        $filter = SmartSelectFilter::make('Active');

        $this->assertSame([], $filter->getKeys());
    }

    /** @test */
    public function can_get_filter_default_value(): void
    {
        $filter = SmartSelectFilter::make('Active');

        $this->assertSame([], $filter->getDefaultValue());
    }

    /** @test */
    public function can_get_filter_callback(): void
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
    public function can_get_filter_pill_title(): void
    {
        $filter = SmartSelectFilter::make('Active');

        $this->assertSame('Active', $filter->getFilterPillTitle());

        $filter = SmartSelectFilter::make('Active')
            ->setFilterPillTitle('User Status');

        $this->assertSame('User Status', $filter->getFilterPillTitle());
    }

    /** @test */
    public function can_get_filter_pill_value(): void
    {
        $filter = SmartSelectFilter::make('Active')->options(
            Breed::select(['id', 'name'])->orderBy('name', 'asc')->pluck('name', 'id')->toArray()
        );

        $this->assertSame('Arabian', $filter->getFilterPillValue(['1']));

        $this->assertSame('Arabian, Clydesdale', $filter->getFilterPillValue(['1', '3']));
    }

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
        $filter = SmartSelectFilter::make('Active')->config(['foo' => 'bar']);
        $this->assertTrue($filter->hasConfigs());
    }

    /** @test */
    public function can_check_filter_config_by_name(): void
    {
        $filter = SmartSelectFilter::make('Active')
            ->config(['foo' => 'bar']);

        $this->assertTrue($filter->hasConfig('foo'));
        $this->assertFalse($filter->hasConfig('bar'));
    }

    /** @test */
    public function can_check_if_filter_is_hidden_from_menus(): void
    {
        $filter = SmartSelectFilter::make('Active');

        $this->assertFalse($filter->isHiddenFromMenus());
        $this->assertTrue($filter->isVisibleInMenus());

        $filter->hiddenFromMenus();

        $this->assertTrue($filter->isHiddenFromMenus());
        $this->assertFalse($filter->isVisibleInMenus());
    }

    /** @test */
    public function can_check_if_filter_is_hidden_from_pills(): void
    {
        $filter = SmartSelectFilter::make('Active');

        $this->assertFalse($filter->isHiddenFromPills());
        $this->assertTrue($filter->isVisibleInPills());

        $filter->hiddenFromPills();

        $this->assertTrue($filter->isHiddenFromPills());
        $this->assertFalse($filter->isVisibleInPills());
    }

    /** @test */
    public function can_check_if_filter_is_hidden_from_count(): void
    {
        $filter = SmartSelectFilter::make('Active');

        $this->assertFalse($filter->isHiddenFromFilterCount());
        $this->assertTrue($filter->isVisibleInFilterCount());

        $filter->hiddenFromFilterCount();

        $this->assertTrue($filter->isHiddenFromFilterCount());
        $this->assertFalse($filter->isVisibleInFilterCount());
    }

    /** @test */
    public function can_check_if_filter_is_reset_by_clear_button(): void
    {
        $filter = SmartSelectFilter::make('Active');

        $this->assertTrue($filter->isResetByClearButton());

        $filter->notResetByClearButton();

        $this->assertFalse($filter->isResetByClearButton());
    }
}
