<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class DatePickerFilter extends Filter
{
    /**
     * @var array<mixed>
     */
    protected array $options = [];

    public function __construct(string $name, string $key = null)
    {
        parent::__construct($name, (isset($key) ? $key : null));
        $this->config = config('livewiretablesadvancedfilters.datePicker');
        $this->options = config('livewiretablesadvancedfilters.datePicker.defaults');
    }

    /**
     * @param  array<mixed>  $options
     * @return $this
     */
    public function options($options = []): DatePickerFilter
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return array<mixed>
     */
    public function getOptions(): array
    {
        return $this->options;
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
     * @param  string  $values
     * @return string|bool
     */
    public function validate($value)
    {
        if ($value == '') {
            return false;
        }

        $returnedValues['date'] = $value;

        $dateFormat = $this->getConfig('dateFormat') ?? $this->getConfig('defaults')['dateFormat'];

        $validator = \Illuminate\Support\Facades\Validator::make($returnedValues, [
            'date' => 'required|date_format:' . $dateFormat,
        ]);
        if ($validator->fails()) {
            return false;
        }

        $date = \Carbon\Carbon::createFromFormat($dateFormat, $value);

        $earliestDateString = $this->getConfig('earliestDate') ?? $this->getConfig('defaults')['earliestDate'];
        if ($earliestDateString != '') {
            $earliestDate = \Carbon\Carbon::createFromFormat($dateFormat, $earliestDateString);
            if ($date->lt($earliestDate)) {
                return false;
            }
        }

        $latestDateString = $this->getConfig('latestDate') ?? $this->getConfig('defaults')['latestDate'];
        if ($latestDateString != '') {
            $latestDate = \Carbon\Carbon::createFromFormat($dateFormat, $latestDateString);
            if ($date->gt($latestDate)) {
                return false;
            }
        }

        return $value;
    }

    /**
     * @param  string  $value
     */
    public function isEmpty($value): bool
    {
        if ($value == '' || empty($value) || $value === '' || is_null($value)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param  mixed  $value
     */
    public function getFilterPillValue($value): ?string
    {
        $validatedValue = $this->validate($value);

        if ($validatedValue) {
            $dateFormat = $this->getConfig('dateFormat') ?? $this->getConfig('defaults')['dateFormat'];
            $displayFormat = $this->getConfig('displayFormat') ?? $this->getConfig('defaults')['displayFormat'];

            return \Carbon\Carbon::createFromFormat($dateFormat, $validatedValue)->format($displayFormat);
        }

        return '';
    }

    /**
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
