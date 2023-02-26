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
        if (isset($values['min'])) {
            if (intval($values['min']) < $this->getConfig('defaults')['min'] || intval($values['min']) > $this->getConfig('defaults')['max']) {
                return false;
            }
        } else {
            $values['min'] = intval($this->getConfig('defaults')['min']);
        }

        if (isset($values['max'])) {
            if (intval($values['max']) < $this->getConfig('defaults')['min'] || intval($values['max']) > $this->getConfig('defaults')['max']) {
                return false;
            }
        } else {
            $values['max'] = intval($this->getConfig('defaults')['max']);
        }

        return $values;
    }

    /**
     * @param  array<mixed>|string  $value
     */
    public function isEmpty($value): bool
    {
        return ! is_array($value);
    }

    /**
     * @param  array<mixed>  $values
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
