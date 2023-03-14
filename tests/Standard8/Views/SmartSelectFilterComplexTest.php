<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Standard8\Views;

use Illuminate\Database\Eloquent\Builder;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\SmartSelectFilter;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Standard8\Models\Breed;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Standard8\TestCaseAdvanced;

/**
 * @covers \LowerRockLabs\LaravelLivewireTablesAdvancedFilters\SmartSelectFilter
 */
class SmartSelectFilterComplexTest extends TestCaseAdvanced
{
    public $breedQuery;

    /** @test */
    public function can_get_filter_name_complex(): void
    {
        $filter = SmartSelectFilter::make('Active')->config(['optionsMethod' => 'complex', 'foo' => 'bar']);
        // Should match
        $this->assertSame('Active', $filter->getName());
    }

    /** @test */
    public function can_get_filter_key_complex(): void
    {
        $filter = SmartSelectFilter::make('Active')->config(['optionsMethod' => 'complex', 'foo' => 'bar']);

        $this->assertSame('active', $filter->getKey());
    }

    /** @test */
    public function can_get_filter_configs_complex(): void
    {
        $filter = SmartSelectFilter::make('Active');

        $defaultConfig = array_merge(config('livewiretablesadvancedfilters.smartSelect'), ['customFilterMenuWidth' => 'w-80']);

        $this->assertSame($defaultConfig, $filter->getConfigs());

        $filter->config(['popoverMethod' => 'complex']);

        $this->assertSame('complex', $filter->getConfigs()['popoverMethod']);

        $filter->config(['popoverMethod' => 'see']);
    }

    /** @test */
    public function can_set_individual_filter_config_values(): void
    {
        $filter = SmartSelectFilter::make('Active');

        $defaultConfig = array_merge(config('livewiretablesadvancedfilters.smartSelect'), ['customFilterMenuWidth' => 'w-80']);

        $this->assertSame($defaultConfig, $filter->getConfigs());

        $filter->config(['iconStyling' => ['add' => ['classes' => 'test']]]);

        $this->assertSame('', $filter->getConfigs()['iconStyling']['delete']['classes']);
        $this->assertSame('test', $filter->getConfigs()['iconStyling']['add']['classes']);
    }

    /** @test */
    public function get_a_single_filter_config_complex(): void
    {
        $filter = SmartSelectFilter::make('Active')->config(['popoverMethod' => 'test123']);

        $this->assertSame('test123', $filter->getConfig('popoverMethod'));
    }

    /** @test */
    public function can_get_if_empty_complex(): void
    {
        $filter = SmartSelectFilter::make('Active')->config(['optionsMethod' => 'complex']);
        $this->assertTrue($filter->isEmpty(''));
        $this->assertFalse($filter->isEmpty(['test']));
    }

    /** @test */
    public function can_check_validation_accepts_valid_values_complex(): void
    {
        $this->breedQuery = Breed::query()->select('id', 'name')->orderBy('name')->get()->map(function ($breed) {
            $breedValue['id'] = $breed->id;
            $breedValue['name'] = $breed->name;

            return $breedValue;
        })->keyBy('id')->toArray();

        $filter = SmartSelectFilter::make('Active')->config(['optionsMethod' => 'complex'])->options(
            $this->breedQuery
        );
        $this->assertSame(['1', '2'], $filter->validate(['1', '2']));
    }

    /** @test */
    public function can_check_validation_rejects_empty_values_complex(): void
    {
        $filter = SmartSelectFilter::make('Active')->config(['optionsMethod' => 'complex']);
        $this->assertFalse($filter->validate([]));
    }

    /** @test */
    public function can_check_validation_rejects_string_value_complex(): void
    {
        $filter = SmartSelectFilter::make('Active')->config(['optionsMethod' => 'complex']);
        $this->assertFalse($filter->validate(''));
    }

    /** @test */
    public function can_check_validation_rejects_invalid_values_complex(): void
    {
        $this->breedQuery = Breed::query()->select('id', 'name')->orderBy('name')->get()->map(function ($breed) {
            $breedValue['id'] = $breed->id;
            $breedValue['name'] = $breed->name;

            return $breedValue;
        })->keyBy('id')->toArray();

        $filter = SmartSelectFilter::make('Active')->config(['optionsMethod' => 'complex'])->options(
            $this->breedQuery
        );
        $this->assertFalse($filter->validate('test'));
    }

    /** @test */
    public function can_check_validation_clears_values_not_in_keys(): void
    {
        $this->breedQuery = Breed::query()->select('id', 'name')->orderBy('name')->get()->map(function ($breed) {
            $breedValue['id'] = $breed->id;
            $breedValue['name'] = $breed->name;

            return $breedValue;
        })->keyBy('id')->toArray();

        $filter = SmartSelectFilter::make('Active')->config(['optionsMethod' => 'complex'])->options(
            $this->breedQuery
        );
        $this->assertSame([1 => '2'], $filter->validate(['153', '2']));
    }

    /** @test */
    public function can_check_validation_accepts_string_value_in_complex(): void
    {
        $filter = SmartSelectFilter::make('Active')->config(['optionsMethod' => 'complex'])->options(
            Breed::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get()
            ->map(function ($breed) {
                $breedValue['id'] = $breed->id;
                $breedValue['name'] = $breed->name;

                return $breedValue;
            })->keyBy('id')->toArray()
        );
        $this->assertSame(['1'], $filter->validate('1'));
    }

    /** @test */
    public function can_check_validation_rejects_invalid_string_value_in_complex(): void
    {
        $this->breedQuery = Breed::query()->select('id', 'name')->orderBy('name')->get()->map(function ($breed) {
            $breedValue['id'] = $breed->id;
            $breedValue['name'] = $breed->name;

            return $breedValue;
        })->keyBy('id')->toArray();

        $filter = SmartSelectFilter::make('Active')->config(['optionsMethod' => 'complex'])->options(
            $this->breedQuery
        );
        $this->assertFalse($filter->validate('888'));
    }

    /** @test */
    public function can_get_filter_options_complex(): void
    {
        $this->breedQuery = Breed::query()->select('id', 'name')->orderBy('name')->get()->map(function ($breed) {
            $breedValue['id'] = $breed->id;
            $breedValue['name'] = $breed->name;

            return $breedValue;
        })->keyBy('id')->toArray();

        $filter = SmartSelectFilter::make('Active')->config(['optionsMethod' => 'complex'])->options(
            $this->breedQuery
        );
        $this->assertSame($this->breedQuery, $filter->getOptions());
    }

    /** @test */
    public function can_get_filter_keys_complex(): void
    {
        $filter = SmartSelectFilter::make('Active')->config(['optionsMethod' => 'complex']);

        $this->assertSame([], $filter->getKeys());
    }

    /** @test */
    public function can_get_filter_default_value_complex(): void
    {
        $filter = SmartSelectFilter::make('Active')->config(['optionsMethod' => 'complex']);

        $this->assertSame([], $filter->getDefaultValue());
    }

    /** @test */
    public function can_get_filter_callback_complex(): void
    {
        $this->breedQuery = Breed::query()->select('id', 'name')->orderBy('name')->get()->map(function ($breed) {
            $breedValue['id'] = $breed->id;
            $breedValue['name'] = $breed->name;

            return $breedValue;
        })->keyBy('id')->toArray();

        $filter = SmartSelectFilter::make('Active')->config(['optionsMethod' => 'complex']);

        $this->assertFalse($filter->hasFilterCallback());

        $filter = SmartSelectFilter::make('Active')->config(['optionsMethod' => 'complex'])
        ->options(
            $this->breedQuery
        )->filter(function (Builder $builder, array $values) {
            return $builder->whereIn('breed_id', $values);
        });

        $this->assertTrue($filter->hasFilterCallback());
        $this->assertIsCallable($filter->getFilterCallback());
    }

    /** @test */
    public function can_get_filter_pill_title_complex(): void
    {
        $filter = SmartSelectFilter::make('Active')->config(['optionsMethod' => 'complex']);

        $this->assertSame('Active', $filter->getFilterPillTitle());

        $filter = SmartSelectFilter::make('Active')->config(['optionsMethod' => 'complex'])
            ->setFilterPillTitle('User Status');

        $this->assertSame('User Status', $filter->getFilterPillTitle());
    }

    /** @test */
    public function can_get_filter_pill_value(): void
    {
        $this->breedQuery = Breed::query()->select('id', 'name')->orderBy('name')->get()->map(function ($breed) {
            $breedValue['id'] = $breed->id;
            $breedValue['name'] = $breed->name;

            return $breedValue;
        })->keyBy('id')->toArray();

        $filter = SmartSelectFilter::make('Active')
            ->options($this->breedQuery);

        $this->assertSame('American Shorthair', $filter->getFilterPillValue(['1']));

        $this->assertSame('American Shorthair', $filter->getFilterPillValue('1'));

        $this->assertSame('', $filter->getFilterPillValue(['1457']));
        $this->assertSame('1457 - test', $filter->getFilterPillValue([1457 => ['id' => '1457', 'name' => 'test']]));

        $filter->setFilterPillValues(['1' => 'Test']);

        $this->assertSame('Test', $filter->getFilterPillValue(['1']));
    }

    /** @test */
    public function can_get_filter_pill_value_deep_array(): void
    {
        $this->breedQuery = Breed::query()->select('id', 'name')->orderBy('name')->get()->map(function ($breed) {
            $breedValue['id'] = $breed->id;
            $breedValue['name'] = $breed->name;

            return $breedValue;
        })->keyBy('id')->toArray();

        $filter = SmartSelectFilter::make('Active')
            ->options($this->breedQuery);
        $this->assertSame('', $filter->getFilterPillValue([1457 => ['id' => '1457', 'name' => 'test', 'first' => 'test']]));
    }

    /** @test */
    public function can_get_filter_pill_value_array_key(): void
    {
        $this->breedQuery = Breed::query()->select('id', 'name')->orderBy('name')->get()->map(function ($breed) {
            $breedValue['id'] = $breed->id;
            $breedValue['name'] = $breed->name;

            return $breedValue;
        })->keyBy('id')->toArray();

        $filter = SmartSelectFilter::make('Active')
            ->options($this->breedQuery);
        $this->assertSame('', $filter->getFilterPillValue([3 => ['id' => '1457', 'name' => 'test', 'first' => 'test']]));
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
    public function can_check_if_filter_has_configs_complex(): void
    {
        $filter = SmartSelectFilter::make('Active')->config(['foo' => 'bar']);
        $this->assertTrue($filter->hasConfigs());
    }

    /** @test */
    public function can_check_filter_config_by_name_complex(): void
    {
        $filter = SmartSelectFilter::make('Active')->config(['optionsMethod' => 'complex', 'foo' => 'bar']);

        $this->assertTrue($filter->hasConfig('optionsMethod'));

        $this->assertSame('complex', $filter->getConfig('optionsMethod'));
        $this->assertSame('complex', $filter->getConfigs()['optionsMethod']);

        $filter->config(['foo' => 'bar']);

        $filter->config(['optionsMethod' => 'simple']);

        $this->assertSame('simple', $filter->getConfig('optionsMethod'));

        $this->assertSame('simple', $filter->getConfigs()['optionsMethod']);

        $this->assertTrue($filter->hasConfig('optionsMethod'));

        $this->assertTrue($filter->hasConfig('foo'));

        $this->assertFalse($filter->hasConfig('bar'));
    }

    /** @test */
    public function can_check_if_filter_is_hidden_from_menus_complex(): void
    {
        $filter = SmartSelectFilter::make('Active');

        $this->assertFalse($filter->isHiddenFromMenus());
        $this->assertTrue($filter->isVisibleInMenus());

        $filter->hiddenFromMenus();

        $this->assertTrue($filter->isHiddenFromMenus());
        $this->assertFalse($filter->isVisibleInMenus());
    }

    /** @test */
    public function can_check_if_filter_is_hidden_from_pills_complex(): void
    {
        $filter = SmartSelectFilter::make('Active')->config(['optionsMethod' => 'complex']);

        $this->assertFalse($filter->isHiddenFromPills());
        $this->assertTrue($filter->isVisibleInPills());

        $filter->hiddenFromPills();

        $this->assertTrue($filter->isHiddenFromPills());
        $this->assertFalse($filter->isVisibleInPills());
    }

    /** @test */
    public function can_check_if_filter_is_hidden_from_count_complex(): void
    {
        $filter = SmartSelectFilter::make('Active')->config(['optionsMethod' => 'complex']);

        $this->assertFalse($filter->isHiddenFromFilterCount());
        $this->assertTrue($filter->isVisibleInFilterCount());

        $filter->hiddenFromFilterCount();

        $this->assertTrue($filter->isHiddenFromFilterCount());
        $this->assertFalse($filter->isVisibleInFilterCount());
    }

    /** @test */
    public function can_check_if_filter_is_reset_by_clear_button_complex(): void
    {
        $filter = SmartSelectFilter::make('Active')->config(['optionsMethod' => 'complex']);

        $this->assertTrue($filter->isResetByClearButton());

        $filter->notResetByClearButton();

        $this->assertFalse($filter->isResetByClearButton());
    }

        /** @test */
        public function can_setup_icon_styling(): void
        {
            $filter = SmartSelectFilter::make('Active')->config(['optionsMethod' => 'complex']);

            $filter->setIconStyling(true, '#000000', '1em', 'both');

            $this->assertSame(['classes' => '', 'defaults' => true, 'svgEnabled' => true, 'svgFill' => '#000000', 'svgSize' => '1em'], $filter->getConfig('iconStyling')['add']);
            $this->assertSame(['classes' => '', 'defaults' => true, 'svgEnabled' => true, 'svgFill' => '#000000', 'svgSize' => '1em'], $filter->getConfig('iconStyling')['delete']);
            $filter->setIconStyling(true, '#FFFFFF', '1em', 'add');
            $this->assertSame(['classes' => '', 'defaults' => true, 'svgEnabled' => true, 'svgFill' => '#FFFFFF', 'svgSize' => '1em'], $filter->getConfig('iconStyling')['add']);
            $this->assertSame(['classes' => '', 'defaults' => true, 'svgEnabled' => true, 'svgFill' => '#000000', 'svgSize' => '1em'], $filter->getConfig('iconStyling')['delete']);
            $filter->setIconStyling(true, '#FFFFFF', '1em', 'delete');
            $this->assertSame(['classes' => '', 'defaults' => true, 'svgEnabled' => true, 'svgFill' => '#FFFFFF', 'svgSize' => '1em'], $filter->getConfig('iconStyling')['add']);
            $this->assertSame(['classes' => '', 'defaults' => true, 'svgEnabled' => true, 'svgFill' => '#FFFFFF', 'svgSize' => '1em'], $filter->getConfig('iconStyling')['delete']);
        }

    /** @test */
    public function can_check_get_selected_values(): void
    {
        $this->breedQuery = Breed::query()->select('id', 'name')->orderBy('name')->get()->map(function ($breed) {
            $breedValue['id'] = $breed->id;
            $breedValue['name'] = $breed->name;

            return $breedValue;
        })->keyBy('id')->toArray();

        $filter = SmartSelectFilter::make('Active')->config(['optionsMethod' => 'complex'])->options(
            $this->breedQuery
        );
        $this->assertSame(['1', '2'], $filter->validate(['1', '2']));
        $this->assertSame([['id' => '1', 'name' => 'American Shorthair'], ['id' => '2', 'name' => 'Maine Coon']], $filter->getFullSelectedList(['1', '2']));
    }
}
