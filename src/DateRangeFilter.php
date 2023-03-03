<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters;

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
        $this->options = $this->config['defaults'];
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
        $returnedValues = [];
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
            $returnedValues['maxDate'] = ((isset($valueArray[1]) && $valueArray[1] != 'to') ? $valueArray[1] : (isset($valueArray[2]) ? $valueArray[2] : ''));
        }

        if ($returnedValues['minDate'] == '' || $returnedValues['maxDate'] == '') {
            return false;
        }
        $dateFormat = $this->getConfig('dateFormat') ?? $this->getConfig('defaults')['dateFormat'];

        $validator = \Illuminate\Support\Facades\Validator::make($returnedValues, [
            'minDate' => 'required|date_format:' . $dateFormat,
            'maxDate' => 'required|date_format:' . $dateFormat,
        ]);
        if ($validator->fails()) {
            return false;
        }

        $startDate = \Carbon\Carbon::createFromFormat($dateFormat, $returnedValues['minDate']);
        $endDate = \Carbon\Carbon::createFromFormat($dateFormat, $returnedValues['maxDate']);

        if ($startDate->gt($endDate)) {
            return false;
        }

        $earliestDateString = $this->getConfig('earliestDate') ?? $this->getConfig('defaults')['earliestDate'];
        if ($earliestDateString != '') {
            $earliestDate = \Carbon\Carbon::createFromFormat($dateFormat, $earliestDateString);
            if ($startDate->lt($earliestDate)) {
                return false;
            }
        }

        $latestDateString = $this->getConfig('latestDate') ?? $this->getConfig('defaults')['latestDate'];
        if ($latestDateString != '') {
            $latestDate = \Carbon\Carbon::createFromFormat($dateFormat, $latestDateString);
            if ($endDate->gt($latestDate)) {
                return false;
            }
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
        $validatedValue = $this->validate($value);

        if ($validatedValue) {
            $dateFormat = $this->getConfig('dateFormat') ?? $this->getConfig('defaults')['dateFormat'];
            $ariaDateFormat = $this->getConfig('ariaDateFormat') ?? $this->getConfig('defaults')['ariaDateFormat'];

            $minDate = \Carbon\Carbon::createFromFormat($dateFormat, $value['minDate'])->format($ariaDateFormat);
            $maxDate = \Carbon\Carbon::createFromFormat($dateFormat, $value['maxDate'])->format($ariaDateFormat);

            return $minDate . ' ' . __('to') . ' ' . $maxDate;
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
