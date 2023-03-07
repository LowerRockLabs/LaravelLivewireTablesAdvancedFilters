<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters;

// @codeCoverageIgnoreStart
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filter;

// @codeCoverageIgnoreEnd

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
     * @param  array<mixed>|string  $values
     * @return array<mixed>|bool
     */
    public function validate($values)
    {
        if (! is_array($values)) {
            $tmp = explode(',', $values);
            asort($tmp);

            $values = [];
            $values['min'] = (isset($tmp[0]) ? $tmp[0] : $this->getConfig('minRange'));
            $values['max'] = (isset($tmp[1]) ? $tmp[1] : $this->getConfig('maxRange'));
        }

        if (! isset($values['min']) || ! isset($values['max'])) {
            return false;
        }

        if (is_null($values['max']) || is_null($values['min']) || $values['min'] == '' || $values['max'] == '') {
            return false;
        }

        if (is_string($values['min']) && ! ctype_digit($values['min'])) {
            return false;
        }

        if (is_string($values['max']) && ! ctype_digit($values['max'])) {
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

        return ['min' => $values['min'], 'max' => $values['max']];
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
            if (is_null($value['max']) || is_null($value['min'])) {
                return true;
            }

            if ($value['min'] == '' || $value['max'] == '') {
                return true;
            }
            if (intval($value['min']) == intval($this->getConfig('minRange')) && intval($value['max']) == intval($this->getConfig('maxRange'))) {
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
            return __('Min:').$values['min'].', '.__('Max:').$values['max'];
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
        if (! isset($component->{$component->getTableName()}['filters'][$this->getKey()])) {
            $component->{$component->getTableName()}['filters'][$this->getKey()] = $this->getDefaultValue();
        }

        return view('livewiretablesadvancedfilters::components.tools.filters.numberRange', [
            'component' => $component,
            'theme' => $component->getTheme(),
            'filter' => $this,
        ]);
    }
}
