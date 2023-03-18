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
     * All Filters Load
     */
    public function testFilterMenuOpensAll(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/tailwind');

            $browser->assertDontSee('EMail Verified Range');
            $browser->assertDontSee('Verified Before Date');
            $browser->assertDontSee('SmartSelect');
            $browser->assertDontSee('Success Rate');

            $browser->pause(1000);

            $browser->press('@filtBtn');

            $browser->pause(1000);

            $browser->assertSee('EMail Verified Range');
            $browser->assertSee('Verified Before Date');
            $browser->assertSee('SmartSelect');
            $browser->assertSee('Success Rate');
        });
    }

    /**
     * All Filters Load
     */
    public function testDatepickerFilterFocus(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/tailwind');

            $browser->pause(1000);

            $browser->press('@filtBtn');

            $browser->pause(2000);

            $browser->assertDontSee('Wed');

            $browser->pause(1000);

            $browser->click('#users2-filter-verified_before_date');
            
            $browser->pause(3000);

            $browser->assertSee('Sun');
            $browser->assertSee('Mon');
            $browser->assertSee('Tue');
            $browser->assertSee('Wed');
            $browser->assertSee('Thu');
            $browser->assertSee('Fri');
            $browser->assertSee('Sat');

            $browser->assertSee('28');
            $browser->assertSee('15');
            $browser->assertSee('1');
            $browser->assertSee('7');
            $browser->assertSee('25');

            $browser->assertSee(date('F'));

            $browser->assertSee(date('Y'));

            $browser->assertVisible('div.flatpickr-calendar.animate.arrowBottom.arrowLeft.open > div.flatpickr-innerContainer > div > div.flatpickr-days > div > span:nth-child(10)');

            $browser->attribute('div.flatpickr-calendar.animate.arrowBottom.arrowLeft.open > div.flatpickr-innerContainer > div > div.flatpickr-days > div > span:nth-child(10)', 'aria-label');

            $browser->click('div.flatpickr-calendar.animate.arrowBottom.arrowLeft.open > div.flatpickr-innerContainer > div > div.flatpickr-days > div > span:nth-child(10)');

            $browser->pause(5000);
        });
    }
}
