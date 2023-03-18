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
     * Check all versions have pages.
     */
    public function testThemesLoads(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/tailwind')
                    ->assertSee('Tailwind 2 Implementation');
        });
    
        $this->browse(function (Browser $browser) {
            $browser->visit('/tailwind3')
                    ->assertSee('Tailwind 3 Implementation');
        });
    
        $this->browse(function (Browser $browser) {
            $browser->visit('/bootstrap-4')
                    ->assertSee('Bootstrap 4 Implementation');
        });
    
        $this->browse(function (Browser $browser) {
            $browser->visit('/bootstrap-5')
                    ->assertSee('Bootstrap 5 Implementation');
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
    
        $this->browse(function (Browser $browser) {
            $browser->visit('/tailwind3-slidedown')
                    ->assertSee('Tailwind 3 Implementation');
        });
    
        $this->browse(function (Browser $browser) {
            $browser->visit('/bootstrap-4')
                    ->assertSee('Bootstrap 4 Implementation');
        });
    
        $this->browse(function (Browser $browser) {
            $browser->visit('/bootstrap-5-slidedown')
                    ->assertSee('Bootstrap 5 Implementation');
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
     * A basic TW3.
     */
    public function testBasicTailwind3(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/tailwind3')
                    ->assertSee('Mekhi Schultz');
        });
    }

    /**
     * A basic BS4.
     */
    public function testBasicBootstrap4(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/bootstrap-4')
                    ->assertSee('Mekhi Schultz');
        });
    }

    /**
     * A basic BS4.
     */
    public function testBasicBootstrap5(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/bootstrap-5')
                    ->assertSee('Mekhi Schultz');
        });
    }
}
