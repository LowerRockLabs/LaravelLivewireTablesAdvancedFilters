<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters;

use DateTime;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class DatePickerFilter extends Filter
{
    /**
     * @param  mixed  $value
     * @return mixed
     */
    public function validate($value)
    {
        $dateFormat = ($this->hasConfig('dateFormat') ? $this->getConfig('dateFormat') : 'Y-m-d');
        if (! DateTime::createFromFormat($dateFormat, $value)) {
            return false;
        }

        return $value;
    }

    /**
     * @param  mixed  $value
     */
    public function isEmpty($value): bool
    {
        return $value === '';
    }

    public function render(DataTableComponent $component)
    {
        return view('livewiretablesadvancedfilters::datepickerfilter', [
            'component' => $component,
            'filter' => $this,
        ]);
    }
}
