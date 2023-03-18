<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Dusk\Web;

use Laravel\Dusk\Browser;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Dusk\DuskTestCase;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\DataProvider;

final class DatePickerTest extends DuskTestCase
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
            
            
            'BS4' => [
                '/bootstrap-4',
            ],/*
            'BS5' => [
                '/bootstrap-5',
            ],*/
          /*  
             'BS4-slidedown' => [
                 '/bootstrap-4-slidedown',
             ],
             'BS5-slidedown' => [
                 '/bootstrap-5-slidedown',
             ],  */      
        ];
    }



    /**
     * testDatepickerFilterOpens
     */
    #[DataProvider('urlProvider')]
    public function testDatepickerFilterOpens($baseURL): void
    {
        $this->browse(function (Browser $browser) use ($baseURL)  {
            $browser->visit($baseURL);

            $browser->pause(1000);

            $browser->press('@filtBtn');

            $browser->pause(1000);

            $browser->assertDontSee('Wed');

            $browser->pause(1000);

            $browser->click('#users2-filter-verified_before_date');
            
            $browser->pause(1000);

            $browser->screenshot(trim($baseURL,'//')."_flatpickrDatepicker-testDatepickerFilterOpens-click-verified_before_date".date('Y-m-d H'));

            $browser->assertSee('Sun')->assertSee('Mon')->assertSee('Tue')->assertSee('Wed')->assertSee('Thu')->assertSee('Fri')->assertSee('Sat');

            $browser->assertSee('28')->assertSee('15')->assertSee('1')->assertSee('7')->assertSee('25');

            $browser->assertSee(date('F'));

            $browser->screenshot(trim($baseURL,'//')."_flatpickrDatepicker-testDatepickerFilterOpens-click-verified_before_date-seeYear".date('Y-m-d H'));

            $browser->assertVisible('div.flatpickr-calendar.animate.arrowBottom.arrowLeft.open > div.flatpickr-innerContainer > div > div.flatpickr-days > div > span:nth-child(10)');

            $currentDate = $browser->attribute('div.flatpickr-calendar.animate.arrowBottom.arrowLeft.open > div.flatpickr-innerContainer > div > div.flatpickr-days > div > span:nth-child(10)', 'aria-label');

            $browser->click('div.flatpickr-calendar.animate.arrowBottom.arrowLeft.open > div.flatpickr-innerContainer > div > div.flatpickr-days > div > span:nth-child(10)');

            $browser->pause(1000);

            $browser->screenshot(trim($baseURL,'//')."_flatpickrDatepicker-testDatepickerFilterOpens-AssetSeeDates".date('Y-m-d H'));
           
            $browser->press('@filtBtn');

            $browser->pause(1000);

            $browser->assertSee($currentDate);

            $browser->assertDontSee(date('F j, Y'));

            $browser->pause(1000);

            $browser->screenshot(trim($baseURL,'//')."_flatpickrDatepicker-testDatepickerFilterOpens-".date('Y-m-d H'));

            $browser->assertAttribute('.today','aria-label',date('F j, Y'));

            $this->assertEquals($browser->attribute('.today','aria-label'),date('F j, Y'));

            $this->assertNotEquals($browser->attribute('.today','aria-label'),Carbon::tomorrow()->format('F j, Y'));


        });
    }
/*
    #[DataProvider('urlProvider')]
    public function testTodayIsDefaultDate($baseURL): void
    {
        $this->browse(function (Browser $browser) use ($baseURL) {
            $browser->visit($baseURL);

            $browser->pause(1000);

            $browser->press('@filtBtn');

            $browser->pause(2000);

            $browser->assertDontSee('Wed');

            $browser->pause(1000);

            $browser->click('#users2-filter-verified_before_date');
            
            $browser->pause(3000);

            $browser->assertAttribute('.today','aria-label',date('F j, Y'));

            $this->assertEquals($browser->attribute('.today','aria-label'),date('F j, Y'));

            $this->assertNotEquals($browser->attribute('.today','aria-label'),Carbon::tomorrow()->format('F j, Y'));

        });
    }
*/


}
