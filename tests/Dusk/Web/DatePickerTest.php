<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Dusk\Web;

use Carbon\Carbon;
use Laravel\Dusk\Browser;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Dusk\DuskTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class DatePickerTest extends DuskTestCase
{
    public static function urlProvider(): array
    {
        return [
            'TW2' => [
               '/tailwind',
            ],
            'TW2-slidedown' => [
                '/tailwind-slidedown',
             ],
            'TW3' => [
               '/tailwind3',
            ],
             'TW3-slidedown' => [
                '/tailwind3-slidedown',
             ],
            'BS4' => [
                '/bootstrap-4',
            ],
            'BS4-slidedown' => [
                '/bootstrap-4-slidedown',
            ],
            /*
            'BS5' => [
                '/bootstrap-5',
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
        $this->browse(function (Browser $browser) use ($baseURL) {
            $browser->visit($baseURL);

            $browser->pause(1000);

            $browser->screenshot("datePicker_" . trim($baseURL, '//') . "_1_Initial_Load_" . date('Y-m-d H'));

            $browser->press('@filtBtn');

            $browser->pause(1000);

            $browser->screenshot("datePicker_" . trim($baseURL, '//') . "_2_Clicked_Filter_Button_" . date('Y-m-d H'));

            $browser->assertDontSee('Wed');

            $browser->click('#users2-filter-verified_before_date');
            
            $browser->pause(1000);

            $browser->screenshot("datePicker_" . trim($baseURL, '//') . "_3_opened_Flatpickr_" . date('Y-m-d H'));

            $browser->assertSee('Sun')->assertSee('Mon')->assertSee('Tue')->assertSee('Wed')->assertSee('Thu')->assertSee('Fri')->assertSee('Sat')->assertSee('28')->assertSee('15')->assertSee('1')->assertSee('7')->assertSee('25')->assertSee(date('F'));

            $browser->assertVisible('div.flatpickr-calendar.animate.arrowBottom.arrowLeft.open > div.flatpickr-innerContainer > div > div.flatpickr-days > div > span:nth-child(10)');

            $currentDate = $browser->attribute('div.flatpickr-calendar.animate.arrowBottom.arrowLeft.open > div.flatpickr-innerContainer > div > div.flatpickr-days > div > span:nth-child(10)', 'aria-label');

            $browser->click('div.flatpickr-calendar.animate.arrowBottom.arrowLeft.open > div.flatpickr-innerContainer > div > div.flatpickr-days > div > span:nth-child(10)');

            $browser->pause(1000);

            $browser->screenshot("datePicker_" . trim($baseURL, '//') . "_4_Selected_Date_" . date('Y-m-d H'));
           
            $browser->press('@filtBtn');

            $browser->pause(1000);

            $browser->assertSee($currentDate);

            $browser->assertDontSee(date('F j, Y'));

            $browser->assertAttribute('.today', 'aria-label', date('F j, Y'));

            $this->assertEquals($browser->attribute('.today', 'aria-label'), date('F j, Y'));

            $this->assertNotEquals($browser->attribute('.today', 'aria-label'), Carbon::tomorrow()->format('F j, Y'));

            $browser->screenshot("datePicker_" . trim($baseURL, '//') . "_5_Final_Check_" . date('Y-m-d H'));
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
