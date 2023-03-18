<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Dusk\Web;

use Laravel\Dusk\Browser;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Dusk\DuskTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class DateRangeTest extends DuskTestCase
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
            
            'BS5' => [
                '/bootstrap-5',
            ],
             'BS5-slidedown' => [
                 '/bootstrap-5-slidedown',
             ],
             
        ];
    }

    /**
     * testDatepickerFilterOpens
     */
    #[DataProvider('urlProvider')]
    public function testDaterangeFilterOpens($baseURL): void
    {
        $this->browse(function (Browser $browser) use ($baseURL) {
            $browser->visit($baseURL);
           
            $browser->pause(1000);

            $browser->screenshot("dateRange_" . trim($baseURL, '//') . "_1_Initial_Load_" . date('Y-m-d H'));

            $browser->press('@filtBtn');

            $browser->pause(1000);

            $browser->screenshot("dateRange_" . trim($baseURL, '//') . "_2_Clicked_Filter_Button_" . date('Y-m-d H'));

            $browser->assertDontSee('Wed');

            $browser->click('#users2-filter-e_mail_verified_range');
            
            $browser->pause(1000);

            $browser->screenshot("dateRange_" . trim($baseURL, '//') . "_3_opened_Flatpickr_" . date('Y-m-d H'));

            $browser->assertSee('Sun')->assertSee('Mon')->assertSee('Tue')->assertSee('Wed')->assertSee('Thu')->assertSee('Fri')->assertSee('Sat')->assertSee('28')->assertSee('15')->assertSee('1')->assertSee('7')->assertSee('25')->assertSee(date('F'));

            $browser->assertVisible('div.flatpickr-calendar.animate.arrowBottom.arrowLeft.open > div.flatpickr-innerContainer > div > div.flatpickr-days > div > span:nth-child(10)');

            $firstDate = $browser->attribute('div.flatpickr-calendar.animate.arrowBottom.arrowLeft.open > div.flatpickr-innerContainer > div > div.flatpickr-days > div > span:nth-child(10)', 'aria-label');

            $secondDate = $browser->attribute('div.flatpickr-calendar.animate.arrowBottom.arrowLeft.open > div.flatpickr-innerContainer > div > div.flatpickr-days > div > span:nth-child(15)', 'aria-label');

            $browser->click('div.flatpickr-calendar.animate.arrowBottom.arrowLeft.open > div.flatpickr-innerContainer > div > div.flatpickr-days > div > span:nth-child(10)');

            $browser->click('div.flatpickr-calendar.animate.arrowBottom.arrowLeft.open > div.flatpickr-innerContainer > div > div.flatpickr-days > div > span:nth-child(15)');

            $browser->pause(1000);

            $browser->screenshot("dateRange_" . trim($baseURL, '//') . "_4_Selected_Dates_" . date('Y-m-d H'));
           
            $browser->press('@filtBtn');

            $browser->pause(1000);

            $browser->assertSee($firstDate)->assertSee($secondDate);

            $browser->screenshot("dateRange_" . trim($baseURL, '//') . "_5_Final_Check_" . date('Y-m-d H'));
        });
    }
}
