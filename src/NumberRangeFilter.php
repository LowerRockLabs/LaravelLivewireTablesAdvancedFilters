<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class NumberRangeFilter extends Filter
{
    protected array $options = [];

    public function __construct(string $name, string $key = null)
    {
        parent::__construct($name, (isset($key) ? $key : null));
        $this->config = config('livewiretablesadvancedfilters.numberRange');
        $this->options = config('livewiretablesadvancedfilters.numberRange.defaults');
    }

    public function options(array $options = []): NumberRangeFilter
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function config(array $config = []): NumberRangeFilter
    {
        $this->config = array_merge($this->config, $config);

        return $this;
    }

    /**
     * @param  mixed  $values
     * @return mixed
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
     * @param  mixed  $value
     */
    public function isEmpty($value): bool
    {
        return ! is_array($value);
    }

    public function getFilterPillValue($values): ?string
    {
        return implode(',', $values);
    }

    public function render(DataTableComponent $component)
    {
        return view('livewiretablesadvancedfilters::numberrangefilter', [
            'component' => $component,
            'filter' => $this,
        ]);
    }
}
