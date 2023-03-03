<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters;

use DateTime;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class DateRangeFilter extends Filter
{
    /**
     * @var array<mixed>
     */
    protected array $options = [];

    public function __construct(string $name, string $key = null)
    {
        parent::__construct($name, (isset($key) ? $key : null));
        $this->config = config('livewiretablesadvancedfilters.dateRange');
        $this->options = config('livewiretablesadvancedfilters.dateRange.defaults');
    }

    /**
     * @return array<mixed>
     */
    public function getKeys(): array
    {
        return ['minDate' => '', 'maxDate' => ''];
    }

    /**
     * @param  array<mixed>  $options
     * @return $this
     */
    public function options($options = []): DateRangeFilter
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
    public function config($config = []): DateRangeFilter
    {
        $this->config = array_merge($this->config, $config);

        return $this;
    }

    /**
     * @param  array<mixed>|string  $values
     * @return array<mixed>|bool
     */
    public function validate($values)
    {
        if (is_array($values)) {
            foreach ($values as $index => $value) {
                if ($index == 0 || strtolower($index) == 'mindate') {
                    $returnedValues['minDate'] = $value;
                }
                if ($index == 1 || strtolower($index) == 'maxdate') {
                    $returnedValues['maxDate'] = $value;
                }
            }
        } else {
            $valueArray = explode(' ', $values);
            $returnedValues['minDate'] = $valueArray[0];
            $returnedValues['maxDate'] = ((isset($valueArray[1]) && $valueArray[1] != ' to ') ? $valueArray[1] : (isset($valueArray[2]) ? $valueArray[2] : ''));
        }

        $dateFormat = $this->getConfigs()['defaults']['dateFormat'];

        if ($returnedValues['minDate'] == '' || $returnedValues['maxDate'] == '') {
            return false;
        }

        if (! DateTime::createFromFormat($dateFormat, $returnedValues['minDate'])) {
            return false;
        }

        if (! DateTime::createFromFormat($dateFormat, $returnedValues['maxDate'])) {
            return false;
        }

        return $returnedValues;
    }

    /**
     * @return array<mixed>
     */
    public function getDefaultValue(): array
    {
        return ['minDate' => null, 'maxDate' => null];
    }

    /**
     * @param  mixed  $value
     */
    public function getFilterPillValue($value): ?string
    {
        if ($this->validate($value)) {
            if (is_array($value)) {
                foreach ($value as $index => $val) {
                    if ($index == 0 || strtolower($index) == 'mindate') {
                        $minDate = $val;
                    }
                    if ($index == 1 || strtolower($index) == 'maxdate') {
                        $maxDate = $val;
                    }
                }
            }

            if ($minDate != '' && $maxDate != '' && ! is_null($minDate) && ! is_null($maxDate) && ! empty($minDate) && ! empty($maxDate)) {
                $dateFormat = $this->getConfigs()['defaults']['dateFormat'];
                $displayFormat = $this->getConfigs()['defaults']['ariaDateFormat'];

                $minDate = DateTime::createFromFormat($dateFormat, $minDate)->format($displayFormat);
                $maxDate = DateTime::createFromFormat($dateFormat, $maxDate)->format($displayFormat);
                if ($minDate != '' && $maxDate != '') {
                    return $minDate . ' ' . __('to') . ' ' . $maxDate;
                }
            }
        }

        return '';
    }

    /**
     * @param  mixed  $value
     */
    public function isEmpty($value): bool
    {
        if (is_null($value) || empty($value) || $value == $this->getDefaultValue() || ! isset($value['minDate']) || ! isset($value['maxDate']) || $value['minDate'] == '' || $value['maxDate'] == '' || is_null($value['maxDate']) || is_null($value['minDate']) || empty($value['maxDate']) || empty($value['minDate'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return \Illuminate\View\View|\Illuminate\View\Factory
     */
    public function render(DataTableComponent $component)
    {
        if (! isset($component->{$component->getTableName()}['filters'][$this->getKey()])) {
            $component->resetFilter($this);
        }

        return view('livewiretablesadvancedfilters::components.tools.filters.dateRange', [
            'component' => $component,
            'filter' => $this,
        ]);
    }
}
