<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Standard\Views;

use Illuminate\Database\Eloquent\Builder;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\SlimSelectFilter;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Standard\Models\Breed;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Standard\TestCaseAdvanced;

class SlimSelectFilterTest extends TestCaseAdvanced
{
    /** @test */
    public function can_get_filter_name(): void
    {
        $filter = SlimSelectFilter::make('Active');
        // Should match
        $this->assertSame('Active', $filter->getName());
    }

    /** @test */
    public function can_get_filter_key(): void
    {
        $filter = SlimSelectFilter::make('Active');

        $this->assertSame('active', $filter->getKey());
    }

    /** @test */
    public function can_get_filter_configs(): void
    {
        $filter = SlimSelectFilter::make('Active');

        $defaultConfig = array_merge(config('livewiretablesadvancedfilters.slimSelect'), ['customFilterMenuWidth' => 'md:w-80']);

        $this->assertSame($defaultConfig, $filter->getConfigs());

        $filter->config(['foo' => 'bar']);

        $this->assertSame(array_merge($defaultConfig, ['foo' => 'bar']), $filter->getConfigs());
    }

    /** @test */
    public function get_a_single_filter_config(): void
    {
        $filter = SlimSelectFilter::make('Active')
            ->config(['foo' => 'bar']);

        $this->assertSame('bar', $filter->getConfig('foo'));
    }

    /** @test */
    public function can_get_if_empty(): void
    {
        $filter = SlimSelectFilter::make('Active');
        $this->assertTrue($filter->isEmpty(''));
        $this->assertFalse($filter->isEmpty(['test']));
    }

    /** @test */
    public function can_check_validation_accepts_valid_values(): void
    {
        $filter = SlimSelectFilter::make('Active')->options(
            Breed::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get()
            ->map(function ($breed) {
                return [
                    'id' => $breed->id,
                    'name' => $breed->name,
                    'text' => $breed->name,
                    'value' => $breed->id,
                    'html' => $breed->name,
                ];
            })->toArray()
        );
        $this->assertSame(['1', '2'], $filter->validate(['1', '2']));
    }

    /** @test */
    public function can_check_validation_rejects_empty_values(): void
    {
        $filter = SlimSelectFilter::make('Active');
        $this->assertSame([], $filter->validate([]));
    }

    /** @test */
    public function can_check_validation_rejects_string_value(): void
    {
        $filter = SlimSelectFilter::make('Active');
        $this->assertSame('', $filter->validate(''));
    }

    /** @test */
    public function can_check_validation_rejects_invalid_values(): void
    {
        $filter = SlimSelectFilter::make('Test')->options(
            Breed::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get()
            ->map(function ($breed) {
                return [
                    'id' => $breed->id,
                    'name' => $breed->name,
                    'text' => $breed->name,
                    'value' => $breed->id,
                    'html' => $breed->name,
                ];
            })->toArray()
        );
        $this->assertSame('test', $filter->validate('test'));
    }

    /** @test */
    public function can_check_validation_accepts_string_value_in(): void
    {
        $filter = SlimSelectFilter::make('Active')->options(
            Breed::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get()
            ->map(function ($breed) {
                return [
                    'id' => $breed->id,
                    'name' => $breed->name,
                    'text' => $breed->name,
                    'value' => $breed->id,
                    'html' => $breed->name,
                ];
            })->toArray()
        );
        $this->assertSame('1', $filter->validate('1'));
    }

    /** @test */
    public function can_check_validation_rejects_invalid_string_value_in(): void
    {
        $filter = SlimSelectFilter::make('Active')->options(
            Breed::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get()
            ->map(function ($breed) {
                return [
                    'id' => $breed->id,
                    'name' => $breed->name,
                    'text' => $breed->name,
                    'value' => $breed->id,
                    'html' => $breed->name,
                ];
            })->toArray()
        );

        $this->assertSame(Breed::query()
        ->select('id', 'name')
        ->orderBy('name')
        ->get()
        ->map(function ($breed) {
            return [
                'id' => $breed->id,
                'name' => $breed->name,
                'text' => $breed->name,
                'value' => $breed->id,
                'html' => $breed->name,
            ];
        })->toArray(), $filter->getOptions());
    }

    /** @test */
    public function can_get_filter_options(): void
    {
        $filter = SlimSelectFilter::make('Active')->options(
            Breed::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get()
            ->map(function ($breed) {
                return [
                    'id' => $breed->id,
                    'name' => $breed->name,
                    'text' => $breed->name,
                    'value' => $breed->id,
                    'html' => $breed->name,
                ];
            })->toArray()
        );
        $this->assertSame(Breed::query()
        ->select('id', 'name')
        ->orderBy('name')
        ->get()
        ->map(function ($breed) {
            return [
                'id' => $breed->id,
                'name' => $breed->name,
                'text' => $breed->name,
                'value' => $breed->id,
                'html' => $breed->name,
            ];
        })->toArray(), $filter->getOptions());
    }

    /** @test */
    public function can_get_filter_keys(): void
    {
        $filter = SlimSelectFilter::make('Active');

        $this->assertSame([], $filter->getKeys());
    }

    /** @test */
    public function can_get_filter_default_value(): void
    {
        $filter = SlimSelectFilter::make('Active');

        $this->assertSame([], $filter->getDefaultValue());
    }

    /** @test */
    public function can_get_filter_callback(): void
    {
        $filter = SlimSelectFilter::make('Active');

        $this->assertFalse($filter->hasFilterCallback());

        $filter = SlimSelectFilter::make('Active')
        ->options(
            Breed::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get()
            ->map(function ($breed) {
                return [
                    'id' => $breed->id,
                    'name' => $breed->name,
                    'text' => $breed->name,
                    'value' => $breed->id,
                    'html' => $breed->name,
                ];
            })->toArray()
        )->filter(function (Builder $builder, array $values) {
            return $builder->whereIn('breed_id', $values);
        });

        $this->assertTrue($filter->hasFilterCallback());
        $this->assertIsCallable($filter->getFilterCallback());
    }

    /** @test */
    public function can_get_filter_pill_title(): void
    {
        $filter = SlimSelectFilter::make('Active');

        $this->assertSame('Active', $filter->getFilterPillTitle());

        $filter = SlimSelectFilter::make('Active')
            ->setFilterPillTitle('User Status');

        $this->assertSame('User Status', $filter->getFilterPillTitle());
    }

    /** @test */
    public function can_get_filter_pill_value(): void
    {
        $filter = SlimSelectFilter::make('Active')->options(
            Breed::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get()
            ->keyBy('id')
            ->map(function ($breed) {
                return [
                    'id' => $breed->id,
                    'name' => $breed->name,
                    'text' => $breed->name,
                    'value' => $breed->id,
                    'html' => $breed->name,
                ];
            })->toArray()
        );

        $this->assertSame('', $filter->getFilterPillValue(['1']));

        $this->assertSame('', $filter->getFilterPillValue(['1', '3']));
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
        $filter = SlimSelectFilter::make('Active')->config(['foo' => 'bar']);
        $this->assertTrue($filter->hasConfigs());
    }

    /** @test */
    public function can_check_filter_config_by_name(): void
    {
        $filter = SlimSelectFilter::make('Active')
            ->config(['foo' => 'bar']);

        $this->assertTrue($filter->hasConfig('foo'));
        $this->assertFalse($filter->hasConfig('bar'));
    }

    /** @test */
    public function can_check_if_filter_is_hidden_from_menus(): void
    {
        $filter = SlimSelectFilter::make('Active');

        $this->assertFalse($filter->isHiddenFromMenus());
        $this->assertTrue($filter->isVisibleInMenus());

        $filter->hiddenFromMenus();

        $this->assertTrue($filter->isHiddenFromMenus());
        $this->assertFalse($filter->isVisibleInMenus());
    }

    /** @test */
    public function can_check_if_filter_is_hidden_from_pills(): void
    {
        $filter = SlimSelectFilter::make('Active');

        $this->assertFalse($filter->isHiddenFromPills());
        $this->assertTrue($filter->isVisibleInPills());

        $filter->hiddenFromPills();

        $this->assertTrue($filter->isHiddenFromPills());
        $this->assertFalse($filter->isVisibleInPills());
    }

    /** @test */
    public function can_check_if_filter_is_hidden_from_count(): void
    {
        $filter = SlimSelectFilter::make('Active');

        $this->assertFalse($filter->isHiddenFromFilterCount());
        $this->assertTrue($filter->isVisibleInFilterCount());

        $filter->hiddenFromFilterCount();

        $this->assertTrue($filter->isHiddenFromFilterCount());
        $this->assertFalse($filter->isVisibleInFilterCount());
    }

    /** @test */
    public function can_check_if_filter_is_reset_by_clear_button(): void
    {
        $filter = SlimSelectFilter::make('Active');

        $this->assertTrue($filter->isResetByClearButton());

        $filter->notResetByClearButton();

        $this->assertFalse($filter->isResetByClearButton());
    }
}
