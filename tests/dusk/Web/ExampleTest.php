<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Dusk\Web;

use Laravel\Dusk\Browser;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Dusk\DuskTestCase;

class ExampleTest extends DuskTestCase
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
     * A basic TW2.
     */
    public function testBasicTailwind2(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/tailwind')
                    ->assertSee('Telly Stokes');
        });
    }

    /**
     * A basic TW3.
     */
    public function testBasicTailwind3(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/tailwind3')
                    ->assertSee('Telly Stokes');
        });
    }

    /**
     * A basic BS4.
     */
    public function testBasicBootstrap4(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/bootstrap-4')
                    ->assertSee('Telly Stokes');
        });
    }

    /**
     * A basic BS4.
     */
    public function testBasicBootstrap5(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/bootstrap-5')
                    ->assertSee('Telly Stokes');
        });
    }
}
