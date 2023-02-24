<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters;

use DateTime;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class DateRangeFilter extends Filter
{
    /**
     * @param  mixed  $values
     * @return mixed
     */
    public function validate($values)
    {
        $valueArray = explode(' ', $values);
        $dateFormat = ($this->hasConfig('dateFormat') ? $this->getConfig('dateFormat') : 'Y-m-d');
        if (! DateTime::createFromFormat($dateFormat, $valueArray[0])) {
            return false;
        }
        if (! DateTime::createFromFormat($dateFormat, $valueArray[2])) {
            return false;
        }

        return $values;
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
        return view('livewiretablesadvancedfilters::daterangefilter', [
            'component' => $component,
            'filter' => $this,
        ]);
    }
}
