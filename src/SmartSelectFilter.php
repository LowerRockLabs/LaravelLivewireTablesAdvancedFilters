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

    public array $fullSelectedList = [];

    public $componentname = '';

    public function __construct(string $name, string $key = null)
    {
        parent::__construct($name, (isset($key) ? $key : null));
        $this->config = config('livewiretablesadvancedfilters.smartSelect');
    }

    public function boot()
    {
        $this->getFullSelectedList($component->{$component->getTableName()}['filters'][$this->getKey()]);
    }

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
     * @param  bool  $svgEnabled
     * @param  string  $svgFill
     * @param  string  $svgSize
     * @param  mixed  $mode='both'
     */
    public function setIconStyling($svgEnabled = true, $svgFill = '', $svgSize = '', $mode = 'both'): SmartSelectFilter
    {
        //$this->config['iconStyling'] =
        if ($mode == 'add' || $mode == 'both') {
            $this->config['iconStyling']['add']['svgEnabled'] = $svgEnabled;
            if ($svgFill != '') {
                $this->config['iconStyling']['add']['svgFill'] = $svgFill;
            }
            if ($svgSize != '') {
                $this->config['iconStyling']['add']['svgSize'] = $svgSize;
            }
        }
        if ($mode == 'delete' || $mode == 'both') {
            $this->config['iconStyling']['delete']['svgEnabled'] = $svgEnabled;
            if ($svgFill != '') {
                $this->config['iconStyling']['delete']['svgFill'] = $svgFill;
            }
            if ($svgSize != '') {
                $this->config['iconStyling']['delete']['svgSize'] = $svgSize;
            }
        }

        return $this;
    }

    /**
     * @return array<mixed>
     */
    public function getOptions(): array
    {
        if ($this->getConfig('optionsMethod') == 'complex') {
            return $this->options;
        } else {
            $complexArray = [];
            foreach ($this->options as $id => $name) {
                $complexArray[] = ['id' => $id, 'name' => $name];
            }

            return $complexArray;
        }
    }

    /**
     * @return array<mixed>
     */
    public function getKeys(): array
    {
        if ($this->getConfig('optionsMethod') == 'complex') {
            return collect($this->getOptions())->toArray();
        } else {
            return collect($this->getOptions())
            ->keys()
            ->map(fn ($value) => (string) $value)
            ->filter(fn ($value) => strlen($value)) /** @phpstan-ignore-line */
            ->values()
            ->toArray();
        }
    }

    /**
     * @param  mixed  $value
     * @return array<mixed>|bool
     */
    public function validate($value)
    {
        if (is_array($value)) {
            if (count($value) == 0) {
                return false;
            }
            $value = array_unique($value);

            foreach ($value as $index => $val) {
                // Remove the bad value
                if (! in_array($val, $this->getKeys())) {
                    unset($value[$index]);
                }

                $this->fullSelectedList[$val] = ['id' => $val, 'name' => $this->getOptions()[$val]['name']];
            }

            if (count($value) == 0) {
                return false;
            }
        } else {
            if ($value == '') {
                return false;
            }
            if (in_array($value, $this->getKeys())) {
                $val = $value;
                $value = [];
                $value[] = $val;
            } else {
                return false;
            }
        }

        return $value;
    }

    /**
     * @param  string|array<mixed>  $value
     */
    public function getFilterPillValue($value): ?string
    {
        $values = [];
        $values = $this->generatePillArray($value);

        return implode(', ', $values);
    }

    /**
     * @param  string|array<mixed>  $value
     */
    public function generatePillArray($value): ?array
    {
        $values = [];
        if (is_array($value)) {
            foreach ($value as $item) {
                $found = $this->getCustomFilterPillValue($item) ?? $this->getOptions()[$item] ?? null;

                if ($found) {
                    if (is_array($found)) {
                        $found = (isset($found['name']) ? $found['name'] : (isset($found[1]) ? $found[1] : ''));
                    }
                    $values[$item] = $found;
                } else {
                    $values[] = implode($item);
                }
            }
        } elseif (isset($this->getOptions()[$value])) {
            $values[$value] = $this->getOptions()[$value];
        }

        return array_unique($values);
    }

    /*public function getFilterPillLinkItems($value)
    {
        $returnValues = [];
        $linkItems = $this->generatePillArray($value);

        foreach ($linkItems as $itemID => $itemName) {
            $returnValues[$itemID] = $itemName;
        }

        return $returnValues;
    }*/

    /**
     * @param  string  $value
     */
    public function isEmpty($value): bool
    {
        return $value === '';
    }

    /**
     * @return array<mixed>
     */
    public function getDefaultValue(): array
    {
        return [];
    }

    /**
     * @return array<mixed>
     */
    public function getFullSelectedList($itemList): array
    {
        $newCol = collect();
        foreach ($itemList as $val) {
            $newCol->push(['id' => $val, 'name' => $this->getOptions()[$val]['name']]);
        }
        if ($newCol->count() > 0) {
            return $newCol->sortBy('name')->values()->toArray();
        } else {
            return [];
        }
    }

    /**
     * @return \Illuminate\View\View|\Illuminate\View\Factory
     */
    public function render(DataTableComponent $component)
    {
        if (! isset($component->{$component->getTableName()}['filters'][$this->getKey()])) {
            $component->{$component->getTableName()}['filters'][$this->getKey()] = [];
        }

        //if (! isset($component->{$component->getTableName()}['filterdata'])) {
        //    $component->{$component->getTableName()}['filterdata'] = [$this->getKey() => []];
        //}

        //$this->filterdata[$component->getTableName()][$this->getKey()] = $this->getFullSelectedList($component->{$component->getTableName()}['filters'][$this->getKey()]);
        //$this->filterdatas[$component->getTableName()][$this->getKey()] = 'test';

        $component->filterData[$this->getKey()] = $this->getFullSelectedList($component->{$component->getTableName()}['filters'][$this->getKey()]);

        return view('livewiretablesadvancedfilters::components.tools.filters.smartSelect', [
            'component' => $component,
            'filter' => $this,
        ]);
    }
}
