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

    public function __construct(string $name, string $key = null)
    {
        parent::__construct($name, (isset($key) ? $key : null));
        $this->config = config('livewiretablesadvancedfilters.smartSelect');
        $this->options = config('livewiretablesadvancedfilters.smartSelect.defaults');
    }

  //  public function setCallback(): SmartSelectFilter
 //   {
 //       //$this->component->setSelect2Options
        //dd($this->getConfigs());
 //       //$this->setSelect2Options('fa');
  //      return $this;
  //  }

    /**
     * @param  array<mixed>  $config
     * @return $this
     */
    public function config($config = []): SmartSelectFilter
    {
        $this->config = array_merge($this->config, $config);

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
     * @param  mixed  $value
     * @return array<mixed>|bool
     */
    public function validate($value)
    {
        if (is_array($value)) {
            if (empty($value)) {
                return false;
            }
            foreach ($value as $index => $val) {
                // Remove the bad value
                if (! in_array($val, $this->getKeys())) {
                    unset($value[$index]);
                }
            }
            if (count($value) == 0) {
                return false;
            }
        } else {
            if ($value == '') {
                return false;
            }
            if (in_array($value, $this->getKeys())) {
                $value[] = $value;
            } else {
                $value = [];
            }
        }

        return array_unique($value);
    }

    /**
     * @param  string|array<mixed>  $value
     */
    public function getFilterPillValue($value): ?string
    {
        $values = [];
        if (is_array($value)) {
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
     * @param  string  $value
     */
    public function isEmpty($value): bool
    {
        return empty($value) || $value == '';
    }

    /**
     * @return array<mixed>
     */
    public function getDefaultValue(): array
    {
        return [];
    }

    /**
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
