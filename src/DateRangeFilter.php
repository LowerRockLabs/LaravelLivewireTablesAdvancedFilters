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
     * @param  array<mixed>  $config
     * @return $this
     */
    public function config(array $config = []): DateRangeFilter
    {
        $this->config = array_merge($this->config, $config);

        return $this;
    }


    /**
     * @param array<mixed> $values
     * 
     * @return array<mixed>|bool
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
     * @param string $value
     * 
     * @return bool
     */
    public function isEmpty(string $value): bool
    {
        return $value === '';
    }

    /**
     * @param DataTableComponent $component
     * 
     * @return \Illuminate\View\View|\Illuminate\View\Factory
     */
    public function render(DataTableComponent $component)
    {
        return view('livewiretablesadvancedfilters::daterangefilter', [
            'component' => $component,
            'filter' => $this,
        ]);
    }
}
