<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class Select2Filter extends Filter
{
    protected array $options = [];

    public function setCallback(): Select2Filter
    {
        //$this->component->setSelect2Options
        //dd($this->getConfigs());
        //$this->setSelect2Options('fa');
        return $this;
    }

    public function options(array $options = []): Select2Filter
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

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

    public function validate($value)
    {
        return array_unique($value);
    }

    public function getFilterPillValue($value): ?string
    {
        $values = [];

        foreach ($value as $item) {
            $found = $this->getCustomFilterPillValue($item) ?? $this->getOptions()[$item] ?? null;

            if ($found) {
                $values[] = $found;
            }
        }
        $values = array_unique($values, SORT_STRING);

        return implode(', ', $values);
    }

    public function isEmpty($value): bool
    {
        return $value === '';
    }

    public function render(DataTableComponent $component)
    {
        //if ($component->filters->$filterKey)
        // dd($this->{$this->tableName}['filters'][$this->getName()]);

        return view('livewiretablesadvancedfilters::select2filter', [
            'component' => $component,
            'filter' => $this,
        ]);
    }
}
