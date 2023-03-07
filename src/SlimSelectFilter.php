<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters;

// @codeCoverageIgnoreStart
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filter;

// @codeCoverageIgnoreEnd

class SlimSelectFilter extends Filter
{
    /**
     * @var array<mixed>
     */
    protected array $options = [];

    /**
     * @var array<mixed>
     */
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
        return collect($this->getOptions())->toArray();
    }

    /**
     * @param  mixed  $value
     * @return array<mixed>|bool
     */
    public function validate($value)
    {
        //if (is_array($value)) {

        //}

        return $value;
    }

    /**
     * @param  mixed  $value
     */
    public function getFilterPillValue($value): ?string
    {
        $values = [];

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

        return view('livewiretablesadvancedfilters::components.tools.filters.slimSelect', [
            'component' => $component,
            'theme' => $component->getTheme(),
            'filter' => $this,
        ]);
    }
}
