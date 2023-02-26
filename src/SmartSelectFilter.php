<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class SmartSelectFilter extends Filter
{
    /**
     * @var array<mixed>
     */
    protected array $options = [];

    /**
     * @return SmartSelectFilter
     */
    public function setCallback(): SmartSelectFilter
    {
        //$this->component->setSelect2Options
        //dd($this->getConfigs());
        //$this->setSelect2Options('fa');
        return $this;
    }

    /**
     * @param  array<mixed>  $options
     * @return $this
     */
    public function options($options = []): SmartSelectFilter
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
     * @return array<mixed>
     */
    public function getKeys(): array
    {
        return collect($this->getOptions())
            ->map(fn ($value, $key) => is_iterable($value) ? collect($value)->keys() : $key)
            ->flatten()
            ->map(fn ($value) => (string) $value)
            ->filter(fn ($value) => strlen($value) > 0)
            ->values()
            ->toArray();
    }

    /**
     * @param array<mixed> $value
     * 
     * @return array<mixed>
     */
    public function validate($value)
    {
        return array_unique($value);
    }

    /**
     * @param string|array<mixed> $value
     * 
     * @return string|null
     */
    public function getFilterPillValue($value): ?string
    {
        $values = [];
        if (is_array($value))
        {
            foreach ($value as $item) {
                $found = $this->getCustomFilterPillValue($item) ?? $this->getOptions()[$item] ?? null;
    
                if ($found) {
                    $values[] = $found;
                }
            }
        }

        $values = array_unique($values, SORT_STRING);

        return implode(', ', $values);
    }

    /**
     * @param string $value
     * 
     * @return bool
     */
    public function isEmpty($value): bool
    {
        return $value === '';
    }


    /**
     * @param DataTableComponent $component
     * 
     * @return \Illuminate\View\View|\Illuminate\View\Factory
     */
    public function render(DataTableComponent $component)
    {
        //if ($component->filters->$filterKey)
        // dd($this->{$this->tableName}['filters'][$this->getName()]);

        return view('livewiretablesadvancedfilters::components.tools.filters.smartSelect', [
            'component' => $component,
            'filter' => $this,
        ]);
    }
}
