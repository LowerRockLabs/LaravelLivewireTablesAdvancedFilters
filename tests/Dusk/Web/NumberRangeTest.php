<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Dusk\Web;

use Laravel\Dusk\Browser;
use LowerRockLabs\LaravelLivewireTablesAdvancedFilters\Tests\Dusk\DuskTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class NumberRangeTest extends DuskTestCase
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
                         'BS5-slidedown' => [
                 '/bootstrap-5-slidedown',
             ],
             
        ];
    }

    /**
     * testNumberRangeFilterOpens
     */
    #[DataProvider('urlProvider')]
    public function testNumberRangeFilterOpens($baseURL): void
    {
        $this->browse(function (Browser $browser) use ($baseURL) {
            $browser->visit($baseURL);
           
            $browser->pause(1000);

            $browser->screenshot("numberRange_" . trim($baseURL, '//') . "_1_Initial_Load_" . date('Y-m-d H'));

            $browser->press('@filtBtn');

            $browser->pause(1000);

            $browser->screenshot("numberRange_" . trim($baseURL, '//') . "_2_Clicked_Filter_Button_" . date('Y-m-d H'));

            $browser->scrollIntoView('#users2\.filters\.success_rate\.min');

            $initialMinValue = $browser->value('#users2\.filters\.success_rate\.min');

            $this->assertEquals(0, $initialMinValue);

            $initialMaxValue = $browser->value('#users2\.filters\.success_rate\.max');

            $this->assertEquals(100, $initialMaxValue);
            
            $browser->pause(1000);
            
            $browser->scrollIntoView('#users2\.filters\.success_rate\.min');

            $browser->value('#users2\.filters\.success_rate\.max', "75");

            $this->assertEquals(75, $browser->value('#users2\.filters\.success_rate\.max'));

            $browser->pause(500);

            $browser->scrollIntoView('#users2\.filters\.success_rate\.min');

            $browser->screenshot("numberRange_" . trim($baseURL, '//') . "_4a_First_Drag" . date('Y-m-d H'));
            
            
            $browser->dragOffset('#users2\.filters\.success_rate\.min', $x = 20, $y = -50);

            $browser->scrollIntoView('#users2\.filters\.success_rate\.min');

            $browser->pause(1000);

            $browser->scrollIntoView('#users2\.filters\.success_rate\.min');

            $browser->screenshot("numberRange_" . trim($baseURL, '//') . "_4b_Second_Drag" . date('Y-m-d H'));

            $this->assertEquals(0, $browser->value('#users2\.filters\.success_rate\.min'));

            $browser->pause(2000);

            $browser->scrollIntoView('@filtBtn');

            $browser->screenshot("numberRange_" . trim($baseURL, '//') . "_5a_Final_Check_" . date('Y-m-d H'));

            $browser->scrollIntoView('.sf-dump');

            $browser->screenshot("numberRange_" . trim($baseURL, '//') . "_5b_Final_Check_" . date('Y-m-d H'));
        });
    }
}
