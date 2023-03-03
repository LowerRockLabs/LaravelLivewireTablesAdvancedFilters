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
        if (is_array($value)) {
            foreach ($value as $index => $val) {
                if ($index == 0 || strtolower($index) == 'mindate') {
                    $returnedValues['minDate'] = $val;
                }
                if ($index == 1 || strtolower($index) == 'maxdate') {
                    $returnedValues['maxDate'] = $val;
                }
            }
        }
        if (! is_array($values)) {
            $valueArray = explode(' ', $values);
            $returnedValues['minDate'] = $valueArray[0];
            $returnedValues['maxDate'] = $valueArray[2];
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
        return ['minDate' => '', 'maxDate' => ''];
    }

    /**
     * @param  mixed  $value
     */
    public function getFilterPillValue($value): ?string
    {
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
        $dateFormat = $this->getConfigs()['defaults']['dateFormat'];
        $displayFormat = $this->getConfigs()['defaults']['ariaDateFormat'];

        $minDate = (! empty($minDate) ? DateTime::createFromFormat($dateFormat, $minDate)->format($displayFormat) : '');
        $maxDate = (! empty($maxDate) ? DateTime::createFromFormat($dateFormat, $maxDate)->format($displayFormat) : '');

        return $minDate . ' ' . __('to') . ' ' . $maxDate;
    }

    /**
     * @param  mixed  $value
     */
    public function isEmpty($value): bool
    {
        return $value === '';
    }

    /**
     * @return \Illuminate\View\View|\Illuminate\View\Factory
     */
    public function render(DataTableComponent $component)
    {
        if (! isset($component->{$component->getTableName()}['filters'][$this->getKey()])) {
            $component->{$component->getTableName()}['filters'][$this->getKey()] = $this->getDefaultValue();
        }

        return view('livewiretablesadvancedfilters::components.tools.filters.dateRange', [
            'component' => $component,
            'filter' => $this,
        ]);
    }
}
