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
        $this->config = config('livewiretablesadvancedfilters.dateRange');
        $this->options = config('livewiretablesadvancedfilters.dateRange.defaults');

        parent::__construct($name, (isset($key) ? $key : null));
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
        if (! is_array($values)) {
            $valueArray = explode(' ', $values);
            $returnedValues['minDate'] = $valueArray[0];
            $returnedValues['maxDate'] = $valueArray[2];
        } else {
            $returnedValues['minDate'] = (isset($values['minDate']) ? $values['minDate'] : (isset($values[0]) ? $values[0] : ''));
            $returnedValues['maxDate'] = (isset($values['maxDate']) ? $values['maxDate'] : (isset($values[1]) ? $values[1] : ''));
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
        return [];
    }

    /**
     * @param  mixed  $value
     */
    public function getFilterPillValue($value): ?string
    {
        $values = [];
        $values['minDate'] = $this->getCustomFilterPillValue('minDate') ?? $this->getOptions()['minDate'] ?? '';
        $values['maxDate'] = $this->getCustomFilterPillValue('maxDate') ?? $this->getOptions()['maxDate'] ?? '';

        return implode(',', $values);
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
        return view('livewiretablesadvancedfilters::components.tools.filters.dateRange', [
            'component' => $component,
            'filter' => $this,
        ]);
    }
}
