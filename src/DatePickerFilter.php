<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters;

use DateTime;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class DatePickerFilter extends Filter
{
    public function __construct(string $name, string $key = null)
    {
        $this->config = config('livewiretablesadvancedfilters.datePicker.defaults');

        parent::__construct($name, (isset($key) ? $key : null));
    }

    /**
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function config(array $config = []): DatePickerFilter
    {
        $this->config = array_merge($this->config, $config);

        return $this;
    }

    /**
     * @param  mixed  $value
     * @return mixed
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
