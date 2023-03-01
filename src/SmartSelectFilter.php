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
    }

    /**
     * @param  array<mixed>  $config
     * @return $this
     */
    public function config($config = []): SmartSelectFilter
    {
        $this->config = (empty($this->config) ? array_merge(config('livewiretablesadvancedfilters.smartSelect'), $config) : array_merge($this->config, $config));

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
            return collect($this->getOptions())->pluck('id')->toArray();
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
        if (is_array($value)) {
            if ($this->getConfig('optionsMethod') == 'complex') {
                $optArray = $this->getOptions();
                foreach ($optArray as $option) {
                    $optionArray[$option['id']] = $option['name'];
                }

                foreach ($value as $item) {
                    $found = $this->getCustomFilterPillValue($item) ?? $optionArray[$item] ?? null;

                    if ($found) {
                        $values[] = $found;
                    }
                }
            } else {
                foreach ($value as $item) {
                    $found = $this->getCustomFilterPillValue($item) ?? $this->getOptions()[$item] ?? null;

                    if ($found) {
                        if (is_array($found)) {
                            $found = implode(',', $found);
                        }
                        $values[] = $found;
                    }
                }
            }
        } elseif (isset($this->getOptions()[$value])) {
            $values[] = $value;
        }

        return implode(', ', array_unique($values));
    }

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
     * @return \Illuminate\View\View|\Illuminate\View\Factory
     */
    public function render(DataTableComponent $component)
    {
        if (! isset($component->{$component->getTableName()}['filters'][$this->getKey()])) {
            $component->{$component->getTableName()}['filters'][$this->getKey()] = [];
        }

        return view('livewiretablesadvancedfilters::components.tools.filters.smartSelectHero', [
            'component' => $component,
            'filter' => $this,
        ]);
    }
}
