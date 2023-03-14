<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Standard8\Views\Traits\Configuration;

use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\SmartSelectFilter;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Standard8\TestCaseAdvanced;

class SmartSelectFilterConfigurationTest extends TestCaseAdvanced
{
    /** @test */
    public function filter_config_can_be_set(): void
    {
        $filter = SmartSelectFilter::make('Active');
        // Check Config
        $defaultConfig = array_merge(config('livewiretablesadvancedfilters.smartSelect'), ['customFilterMenuWidth' => 'md:w-80']);
        $this->assertEquals($defaultConfig, $filter->getConfigs());

        $filter->config([
            'test' => 'cfg',
        ]);

        $this->assertCount(count($defaultConfig) + 1, $filter->getConfigs());

        $this->assertEquals(array_merge($defaultConfig, [
            'test' => 'cfg',
        ]), $filter->getConfigs());
    }

    /** @test */
    public function filter_pill_title_can_be_set(): void
    {
        $filter = SmartSelectFilter::make('Active');

        $this->assertEquals('Active', $filter->getFilterPillTitle());

        $filter->setFilterPillTitle('User Status');

        $this->assertEquals('User Status', $filter->getFilterPillTitle());
    }

    /** @test */
    /*public function filter_pill_values_can_be_set_for_select(): void
    {
        $filter = NumberRangeFilter::make('Range')
        ->options(
            [
                'min' => 0,
                'max' => 20,
            ]
        );

        $this->assertEquals(0, $filter->getFilterPillValue('min'));
        $this->assertEquals(20, $filter->getFilterPillValue('max'));

        $filter->setFilterPillValues([
            'min' => 'Minimum',
            'max' => 'Maximum',
        ]);

        $this->assertEquals('Minimum', $filter->getFilterPillValue('min'));

       // $this->assertEquals('Inactive', $filter->getFilterPillValue('0'));

      //  $filter->setFilterPillValues([
      //      '0' => 'Inactive',
      //  ]);

      //  $this->assertEquals('Yes', $filter->getFilterPillValue('1'));
      //  $this->assertEquals('Inactive', $filter->getFilterPillValue('0'));
    }*/

    /** @test */
    public function can_hide_filter_from_menus(): void
    {
        $filter = SmartSelectFilter::make('Active');

        $this->assertFalse($filter->isHiddenFromMenus());

        $filter->hiddenFromMenus();

        $this->assertTrue($filter->isHiddenFromMenus());
    }

    /** @test */
    public function can_hide_filter_from_pills(): void
    {
        $filter = SmartSelectFilter::make('Active');

        $this->assertFalse($filter->isHiddenFromPills());

        $filter->hiddenFromPills();

        $this->assertTrue($filter->isHiddenFromPills());
    }

    /** @test */
    public function can_hide_filter_from_filter_count(): void
    {
        $filter = SmartSelectFilter::make('Active');

        $this->assertFalse($filter->isHiddenFromFilterCount());

        $filter->hiddenFromFilterCount();

        $this->assertTrue($filter->isHiddenFromFilterCount());
    }

    /** @test */
    public function filter_is_not_reset_by_clear_button(): void
    {
        $filter = SmartSelectFilter::make('Active');

        $this->assertTrue($filter->isResetByClearButton());

        $filter->notResetByClearButton();

        $this->assertFalse($filter->isResetByClearButton());
    }

    /** @test */
    public function can_be_hidden_from_all(): void
    {
        $filter = SmartSelectFilter::make('Active');

        $this->assertFalse($filter->isHiddenFromMenus());
        $this->assertFalse($filter->isHiddenFromPills());
        $this->assertFalse($filter->isHiddenFromFilterCount());
        $this->assertTrue($filter->isResetByClearButton());

        $filter->hiddenFromAll();

        $this->assertTrue($filter->isHiddenFromMenus());
        $this->assertTrue($filter->isHiddenFromPills());
        $this->assertTrue($filter->isHiddenFromFilterCount());
        $this->assertTrue($filter->isResetByClearButton());
    }
}
