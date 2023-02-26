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

    /**
     * @param string $name
     * @param string|null $key
     */
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
            if (intval($values['min']) < $this->getConfig('min') || intval($values['min']) > $this->getConfig('max')) {
                return false;
            }
        } else {
            $values['min'] = intval($this->getConfig('min'));
        }

        if (isset($values['max'])) {
            if (intval($values['max']) < $this->getConfig('min') || intval($values['max']) > $this->getConfig('max')) {
                return false;
            }
        } else {
            $values['max'] = intval($this->getConfig('max'));
        }

        return $values;
    }

    /**
     * @param  array<mixed>  $value
     */
    public function isEmpty($value): bool
    {
        return ! is_array($value);
    }

    /**
     * @param array<mixed> $values
     * 
     * @return string|null
     */
    public function getFilterPillValue($values): ?string
    {
        return implode(',', $values);
    }

    /**
     * @param DataTableComponent $component
     * 
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
