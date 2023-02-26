<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters;

use DateTime;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class DatePickerFilter extends Filter
{
    /**
     * @param string $name
     * @param string|null $key
     */
    public function __construct(string $name, string $key = null)
    {
        $this->config = config('livewiretablesadvancedfilters.datePicker.defaults');

        parent::__construct($name, (isset($key) ? $key : null));
    }

    /**
     * @param  array<mixed>  $config
     * @return $this
     */
    public function config($config = []): DatePickerFilter
    {
        $this->config = array_merge($this->config, $config);

        return $this;
    }

    /**
     * @param  string  $value
     * @return bool|string
     */
    public function validate($value)
    {
        $dateFormat = $this->getConfig('dateFormat');
        if (! DateTime::createFromFormat($dateFormat, $value)) {
            return false;
        }

        return $value;
    }

    /**
     * @param  string  $value
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
        return view('livewiretablesadvancedfilters::components.tools.filters.datePicker', [
            'component' => $component,
            'filter' => $this,
        ]);
    }
}
