<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class NumberRangeFilter extends Filter
{
    /**
     * @var array<mixed>
     */
    protected array $options = [];

    public function __construct(string $name, string $key = null)
    {
        parent::__construct($name, (isset($key) ? $key : null));
        $this->config = config('livewiretablesadvancedfilters.numberRange');
        $this->options = config('livewiretablesadvancedfilters.numberRange.defaults');
    }

    /**
     * @param  array<mixed>  $options
     * @return $this
     */
    public function options($options = []): NumberRangeFilter
    {
        $this->options = array_merge($this->options, $options);

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
    public function config($config = []): NumberRangeFilter
    {
        $this->config = array_merge($this->config, $config);

        return $this;
    }

    /**
     * @param  array<mixed>  $values
     * @return array<mixed>|bool
     */
    public function validate($values)
    {
        if (! isset($values['min']) || ! isset($values['max'])) {
            return false;
        }
        $min = intval($values['min']);
        $max = intval($values['max']);

        if ($max < $min) {
            $newMin = $values['max'];
            $newMax = $values['min'];
            $values['max'] = $newMin;
            $values['min'] = $newMax;
        }
        if (($min == $this->getConfig('minRange') && $max == $this->getConfig('maxRange')) || ($min == $this->getConfig('minRange') && $max == $this->getConfig('minRange')) || ($min == $this->getConfig('maxRange') && $max == $this->getConfig('maxRange')) || $min < $this->getConfig('minRange') || $min > $this->getConfig('maxRange') || $max < $this->getConfig('minRange') || $max > $this->getConfig('maxRange')) {
            return false;
        }

        return $values;
    }

    /**
     * @param  array<mixed>|string  $value
     */
    public function isEmpty($value): bool
    {
        return $value === '' || (is_array($value) && (! isset($value['min']) || ! isset($value['max']))) || (is_array($value) && (isset($value['min']) && isset($value['max']) && ($value['min'] == '0' || $value['min'] == 0) && ($value['max'] == '100' || $value['max'] == '100')));
    }

    /**
     * @return array<mixed>
     */
    public function getDefaultValue(): array
    {
        return config('livewiretablesadvancedfilters.numberRange.defaults');
    }

    /**
     * @param  mixed  $values
     */
    public function getFilterPillValue($values): ?string
    {
        return __('Min:') . $values['min'] . ', ' . __('Max:') . $values['max'];
    }

    /**
     * @return \Illuminate\View\View|\Illuminate\View\Factory
     */
    public function render(DataTableComponent $component)
    {
        if (! isset($component->{$component->getTableName()}['filters'][$this->getKey()])) {
            $component->{$component->getTableName()}['filters'][$this->getKey()] = [];
        }
        $currentMin = intval($component->{$component->getTableName()}['filters'][$this->getKey()]['min']);
        $currentMax = intval($component->{$component->getTableName()}['filters'][$this->getKey()]['max']);
        if ($currentMax < $currentMin) {
            $component->{$component->getTableName()}['filters'][$this->getKey()] = ['min' => $component->{$component->getTableName()}['filters'][$this->getKey()]['max'], 'max' => $component->{$component->getTableName()}['filters'][$this->getKey()]['min']];
        }

        return view('livewiretablesadvancedfilters::components.tools.filters.numberRange', [
            'component' => $component,
            'filter' => $this,
        ]);
    }
}
