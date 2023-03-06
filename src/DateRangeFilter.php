<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters;

// @codeCoverageIgnoreStart
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filter;

// @codeCoverageIgnoreEnd

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
        foreach ($this->options as $index => $value) {
            if (isset($options[$index])) {
                $this->options[$index] = $options[$index];
            }
        }
        //$this->options = array_merge($this->options,$options);

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
        $returnedValues = ['minDate' => '', 'maxDate' => ''];
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
        $dateFormat = $this->getOptions()['dateFormat'] ?? $this->getConfig('defaults')['dateFormat'];

        $validator = \Illuminate\Support\Facades\Validator::make($returnedValues, [
            'minDate' => 'required|date_format:' . $dateFormat,
            'maxDate' => 'required|date_format:' . $dateFormat,
        ]);
        if ($validator->fails()) {
            return false;
        }

        $startDate = \Carbon\Carbon::createFromFormat($dateFormat, $returnedValues['minDate']);
        $endDate = \Carbon\Carbon::createFromFormat($dateFormat, $returnedValues['maxDate']);
        if (! $startDate || ! $endDate) {
            return false;
        }
        if ($startDate->gt($endDate)) {
            return false;
        }

        $earliestDateString = $this->getOptions()['earliestDate'] ?? $this->getConfig('defaults')['earliestDate'];
        if ($earliestDateString != '') {
            $earliestDate = \Carbon\Carbon::createFromFormat($dateFormat, $earliestDateString);

            if (! $earliestDate instanceof \Carbon\Carbon) {
                return false;
            }

            if ($startDate->lt($earliestDate)) {
                return false;
            }
        }

        $latestDateString = $this->getOptions()['latestDate'] ?? $this->getConfig('defaults')['latestDate'];
        if ($latestDateString != '') {
            $latestDate = \Carbon\Carbon::createFromFormat($dateFormat, $latestDateString);

            if (! $latestDate instanceof \Carbon\Carbon) {
                return false;
            }

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
            if ($value['minDate'] == null || $value['maxDate'] == null) {
                return '';
            }
            $minDateCarbon = \Carbon\Carbon::createFromFormat($dateFormat, $value['minDate']);
            $maxDateCarbon = \Carbon\Carbon::createFromFormat($dateFormat, $value['maxDate']);

            if (! $minDateCarbon || ! $maxDateCarbon) {
                return '';
            }
            $minDate = $minDateCarbon->format($ariaDateFormat);
            $maxDate = $maxDateCarbon->format($ariaDateFormat);

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
