<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Dusk\Web;

use Laravel\Dusk\Browser;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Dusk\DuskTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class ExampleTest extends DuskTestCase
{
    public static function urlProvider(): array
    {
        return [
            'TW2' => [
                '/tailwind',
             ],
             'TW3' => [
                '/tailwind3',
             ],
             'TW2-slidedown' => [
                 '/tailwind-slidedown',
              ],
              'TW3-slidedown' => [
                 '/tailwind3-slidedown',
              ],
        ];
    }
    
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

    #[DataProvider('urlProvider')]
    public function testThemesLoadsData($baseURL): void
    {
        $this->browse(function (Browser $browser) use ($baseURL) {
            $browser->visit($baseURL)->assertSee('Mekhi Schultz');
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
            
            $browser->visit('/tailwind-slidedown')
                    ->assertSee('Tailwind 2 Implementation');

            $browser->visit('/tailwind3')
                    ->assertSee('Tailwind 3 Implementation');

            $browser->visit('/tailwind3-slidedown')
                    ->assertSee('Tailwind 3 Implementation');

            $browser->visit('/bootstrap-4')
                    ->assertSee('Bootstrap 4 Implementation');

            $browser->visit('/bootstrap-4')
                    ->assertSee('Bootstrap 4 Implementation');

            $browser->visit('/bootstrap-5')
                    ->assertSee('Bootstrap 5 Implementation');

            $browser->visit('/bootstrap-5-slidedown')
                    ->assertSee('Bootstrap 5 Implementation');
        });
    }
}
