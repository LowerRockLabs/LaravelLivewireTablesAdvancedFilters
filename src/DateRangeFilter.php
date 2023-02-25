<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters;

use DateTime;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class DateRangeFilter extends Filter
{
    public function __construct(string $name, string $key = null)
    {
        $this->config = config('livewiretablesadvancedfilters.dateRange.defaults');

        parent::__construct($name, (isset($key) ? $key : null));
    }

    /**
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function config(array $config = []): DateRangeFilter
    {
        $this->config = array_merge($this->config, $config);

        return $this;
    }

    /**
     * @param  mixed  $values
     * @return mixed
     */
    public function validate($values)
    {
        $valueArray = explode(' ', $values);
        $dateFormat = $this->getConfig('dateFormat');

        if (! DateTime::createFromFormat($dateFormat, $valueArray[0])) {
            return false;
        }

        if (! isset($valueArray[2]) || isset($valueArray[2]) && ! DateTime::createFromFormat($dateFormat, $valueArray[2])) {
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
