<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Dusk\Web;

use Laravel\Dusk\Browser;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Dusk\DuskTestCase;

class TailwindTest extends DuskTestCase
{
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
}
