<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class SlimSelectFilter extends Filter
{
    /**
     * @var array<mixed>
     */
    protected array $options = [];

    public array $selectedItems;

    public function __construct(string $name, string $key = null)
    {
        parent::__construct($name, (isset($key) ? $key : null));
        $this->config = config('livewiretablesadvancedfilters.slimSelect');
    }

    /**
     * @param  array<mixed>  $config
     * @return $this
     */
    public function config($config = []): SlimSelectFilter
    {
        $this->config = (empty($this->config) ? array_merge(config('livewiretablesadvancedfilters.slimSelect'), $config) : array_merge($this->config, $config));

        return $this;
    }

    /**
     * @param  array<mixed>  $options
     * @return $this
     */
    public function options($options = []): SlimSelectFilter
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
    public function setIconStyling($svgEnabled = true, $svgFill = '', $svgSize = '', $mode = 'both'): SlimSelectFilter
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
        $complexArray = [];

        return $this->options;
    }

    /**
     * @return array<mixed>
     */
    public function getKeys(): array
    {
        return collect($this->getOptions())
        ->keys()
        ->map(fn ($value) => (string) $value)
        ->filter(fn ($value) => strlen($value)) /** @phpstan-ignore-line */
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
            foreach ($value as $idx => $val) {
                $this->selectedItems[$val] = 'yes';
                $this->selectedItems[$idx] = 'yes';
            }
        }

        return $value;
    }

    /**
     * @param  mixed
     */
    public function getFilterPillValue($value): ?string
    {
        $values = [];

        return 'yes';
        if (is_array($value)) {
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
        } else {
            $selectedVals = $component->{$component->getTableName()}['filters'][$this->getKey()];

            foreach ($this->options as $id => $name) {
                $this->options[$id] = [
                    'id' => $id,
                    'name' => $name,
                    'selected' => (in_array($id, $selectedVals) ? true : false),
                ];
            }
        }

        return view('livewiretablesadvancedfilters::components.tools.filters.slimSelect', [
            'component' => $component,
            'filter' => $this,
        ]);
    }
}
