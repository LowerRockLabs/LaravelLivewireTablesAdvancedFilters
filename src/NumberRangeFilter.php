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

        $values['min'] = intval($values['min']);
        $values['max'] = intval($values['max']);

        if ($values['min'] == 0 && $values['max'] == 0) {
            return false;
        }

        if ($values['min'] == $this->getOptions()['minRange'] && $values['max'] == $this->getOptions()['maxRange']) {
            return false;
        }

        if ($values['min'] < $this->getOptions()['minRange'] || $values['min'] > $this->getOptions()['maxRange']) {
            return false;
        }

        if ($values['max'] < $this->getOptions()['minRange'] || $values['max'] > $this->getOptions()['maxRange']) {
            return false;
        }

        return $values;
    }

    /**
     * @param  array<mixed>|string  $value
     */
    public function isEmpty($value): bool
    {
        return $value === '' || (is_array($value) && (! isset($value['min']) || ! isset($value['max']))) || (is_array($value) && (isset($value['min']) && isset($value['max']) && $value['min'] == 0 && $value['max'] == 100));
    }

    /**
     * @return array<mixed>
     */
    public function getDefaultValue(): array
    {
        return [];
    }

    /**
     * @param  mixed  $values
     */
    public function getFilterPillValue($values): ?string
    {
        return implode(',', $values);
    }

    /**
     * @return \Illuminate\View\View|\Illuminate\View\Factory
     */
    public function render(DataTableComponent $component)
    {
        return view('livewiretablesadvancedfilters::components.tools.filters.numberRange', [
            'component' => $component,
            'filter' => $this,
        ]);
    }
}
