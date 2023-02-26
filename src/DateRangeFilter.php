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
            $returnedValues['min'] = $valueArray[0];
            $returnedValues['max'] = $valueArray[2];
        } else {
            $returnedValues['min'] = $values[0];
            $returnedValues['max'] = $values[1];
        }
        $dateFormat = $this->getConfigs()['defaults']['dateFormat'];

        if (! DateTime::createFromFormat($dateFormat, $returnedValues['min'])) {
            return false;
        }

        if (! isset($returnedValues['max']) || isset($returnedValues['max']) && ! DateTime::createFromFormat($dateFormat, $returnedValues['max'])) {
            return false;
        }

        return $returnedValues;
    }

    /**
     * @param  string  $value
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
