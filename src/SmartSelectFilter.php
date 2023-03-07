<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters;

// @codeCoverageIgnoreStart
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filter;

// @codeCoverageIgnoreEnd

class SmartSelectFilter extends Filter
{
    /**
     * @var array<mixed>
     */
    protected array $options = [];

    /**
     * @var array<mixed>
     */
    public array $fullSelectedList = [];

    public function __construct(string $name, string $key = null)
    {
        parent::__construct($name, (isset($key) ? $key : null));
        $this->config = config('livewiretablesadvancedfilters.smartSelect');
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
        return $this->options;
    }

    /**
     * @return array<mixed>
     */
    public function getKeys(): array
    {
        return collect($this->getOptions())->pluck('id')->toArray(); //->toArray();
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
            //$value = array_unique($value);

            foreach ($value as $index => $val) {
                // Remove the bad value
                if (in_array(intval($val), $this->getKeys())) {
                    $this->fullSelectedList[$val] = ['id' => $val, 'name' => $this->getOptions()[$val]['name']];
                } else {
                    unset($value[$index]);

                    continue;
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
     * @param  mixed  $value
     */
    public function getFilterPillValue($value): ?string
    {
        $values = [];
        $values = $this->generatePillArray($value);

        return (count($values) > 0) ? implode(", ", $values) : "";
    }

    /**
     * @param mixed $value
     *
     * @return array<mixed>|null
     */
    public function generatePillArray($value): ?array
    {
        $values = [];
        $movedItem = [];
        $itemKey = '';
        if (is_array($value)) {
            foreach ($value as $item) {
                if (is_array($item)) {
                    $movedItem = $item;
                    $itemKey = array_keys($item)[0];
                    while (is_array($item)) {
                        $item = array_values($item)[0];
                    }
                }
                $found = $this->getCustomFilterPillValue($item) ?? $this->getOptions()[$item] ?? $this->getOptions()[$itemKey] ?? $this->getCustomFilterPillValue($itemKey) ?? null;

                if ($found) {
                    if (is_array($found)) {
                        $found = (isset($found['name']) ? $found['name'] : (isset($found[1]) ? $found[1] : ''));
                    }
                    if ($found != '') {
                        $values[$itemKey] = $found;
                    }
                } else {
                    if (is_array($movedItem)) {
                        $values[] = implode(" - ", $movedItem);
                    } else {
                        $values[] = $movedItem;
                    }
                }
            }
        } elseif (isset($this->getOptions()[intval($value)])) {
            $values[intval($value)] = $this->getOptions()[intval($value)]['name'];
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
     * @param mixed $itemList
     *
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

        if (isset($component->filterData)) {
            $component->filterData[$this->getKey()] = $this->getFullSelectedList($component->{$component->getTableName()}['filters'][$this->getKey()]);
        }


        return view('livewiretablesadvancedfilters::components.tools.filters.smartSelect', [
            'component' => $component,
            'theme' => $component->getTheme(),
            'filter' => $this,
        ]);
    }
}
