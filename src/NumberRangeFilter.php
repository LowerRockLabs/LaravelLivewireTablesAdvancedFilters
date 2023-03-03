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

        if (is_null($values['max']) || is_null($values['min']) || $values['min'] == '' || $values['max'] == '') {
            return false;
        }

        $min = intval($values['min']);
        $max = intval($values['max']);

        $minRange = intval($this->getConfig('minRange'));
        $maxRange = intval($this->getConfig('maxRange'));

        if ($max < $min) {
            $newMin = $values['max'];
            $values['max'] = $newMax = $values['min'];
            $values['min'] = $newMin;
            $min = intval($newMin);
            $max = intval($newMax);
        }

        if (($min == $minRange && $max == $maxRange) || ($min == $minRange && $max == $minRange) || ($min == $maxRange && $max == $maxRange) || $min < $minRange || $min > $maxRange || $max < $minRange || $max > $maxRange) {
            return false;
        }

        return $values;
    }

    /**
     * @param  array<mixed>|string  $value
     */
    public function isEmpty($value): bool
    {
        if (! is_array($value)) {
            return true;
        } else {
            if (! isset($value['min']) || ! isset($value['max'])) {
                return true;
            }
            if (is_null($value['max']) || is_null($value['min']) || $value['min'] == '' || $value['max'] == '') {
                return true;
            }
            if (intval($value['min']) == intval($this->getDefaultValue()['min']) && intval($value['max']) == intval($this->getDefaultValue()['max'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<mixed>
     */
    public function getDefaultValue(): array
    {
        return ['min' => null, 'max' => null];
    }

    /**
     * @param  mixed  $values
     */
    public function getFilterPillValue($values): ?string
    {
        if ($this->validate($values)) {
            return __('Min:') . $values['min'] . ', ' . __('Max:') . $values['max'];
        }

        return '';
    }

    /**
     * @return \Illuminate\View\View|\Illuminate\View\Factory
     */
    public function render(DataTableComponent $component)
    {
        // $currentMin = intval($component->{$component->getTableName()}['filters'][$this->getKey()]['min']);
        //$currentMax = intval($component->{$component->getTableName()}['filters'][$this->getKey()]['max']);
        //if ($currentMax < $currentMin) {
        //    $component->{$component->getTableName()}['filters'][$this->getKey()] = ['min' => $component->{$component->getTableName()}['filters'][$this->getKey()]['max'], 'max' => $component->{$component->getTableName()}['filters'][$this->getKey()]['min']];
        // }

        return view('livewiretablesadvancedfilters::components.tools.filters.numberRange', [
            'component' => $component,
            'filter' => $this,
        ]);
    }
}
