<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters;

use DateTime;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class DateRangeFilter extends Filter
{
    /**
     * @param string $name
     * @param string|null $key
     */
    public function __construct(string $name, string $key = null)
    {
        $this->config = config('livewiretablesadvancedfilters.dateRange.defaults');

        parent::__construct($name, (isset($key) ? $key : null));
    }

    /**
     * @param  array<mixed>  $config
     * @return $this
     */
    public function config($config = []): DateRangeFilter
    {
        $this->config = array_merge($this->config, $config);

        return $this;
    }


    /**
     * @param array<mixed>|string $values
     * 
     * @return array<mixed>|bool
     */
    public function validate($values)
    {
        if (!is_array($values)) {
            $valueArray = explode(' ', $values);
        }
        else
        {
            $valueArray = $values;
        }
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
    public function isEmpty($value): bool
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
        return view('livewiretablesadvancedfilters::components.tools.filters.dateRange', [
            'component' => $component,
            'filter' => $this,
        ]);
    }
}
