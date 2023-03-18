<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Dusk\Web;

use Laravel\Dusk\Browser;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Dusk\DuskTestCase;

class TailwindTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     */
    public function testBasicExample(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Livewire Tables Demo');
        });
    }

    /**
     * Check all versions have pages.
     */
    public function testThemesLoads(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/tailwind')
                    ->assertSee('Tailwind 2 Implementation');
        });
    }
    
    /**
     * Check all versions have pages.
     */
    public function testThemesSlidedownLoads(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/tailwind-slidedown')
                    ->assertSee('Tailwind 2 Implementation');
        });
    }

    /**
     * A basic TW2.
     */
    public function testBasicTailwind2(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/tailwind')
                    ->assertSee('Mekhi Schultz');
        });
    }

    /**
     * SmartSelect Loads Label
     */
    public function testFilterMenuOpensSmartSelectFilter(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/tailwind');
            $browser->assertDontSee('SmartSelect');
            $browser->press('@filtBtn');
            $browser->assertSee('SmartSelect');
        });
    }

    /**
     * NumberRange Loads Label
     */
    public function testFilterMenuOpensNumberRangeFilter(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/tailwind');
            $browser->assertDontSee('Success Rate');
            $browser->press('@filtBtn');
            $browser->assertSee('Success Rate');
        });
    }

    /**
     * DatePicker Loads Label
     */
    public function testFilterMenuOpensDatePickerFilter(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/tailwind');
            $browser->assertDontSee('EMail Verified Before DateTime');
            $browser->press('@filtBtn');
            $browser->assertSee('EMail Verified Before DateTime');
        });
    }

    /**
     * DateRange Loads Label
     */
    public function testFilterMenuOpensDateRangeFilter(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/tailwind');
            $browser->assertDontSee('EMail Verified Range');
            $browser->press('@filtBtn');
            $browser->assertSee('EMail Verified Range');
        });
    }

    /**
     * All Filters Load
     */
    public function testFilterMenuOpensAll(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/tailwind');
            $browser->assertDontSee('EMail Verified Range');
            $browser->assertDontSee('EMail Verified Before DateTime');
            $browser->assertDontSee('SmartSelect');
            $browser->assertDontSee('Success Rate');

            $browser->press('@filtBtn');
            $browser->assertSee('EMail Verified Range');
            $browser->assertSee('EMail Verified Before DateTime');
            $browser->assertSee('SmartSelect');
            $browser->assertSee('Success Rate');
        });
    }
}
