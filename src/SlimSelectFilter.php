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
        $this->config = config('lrlAdvancedTableFilters.slimSelect');
        $this->config['customFilterMenuWidth'] = config('lrlAdvancedTableFilters.customFilterMenuWidth');
    }

    /**
     * @param  array<mixed>  $config
     * @return $this
     */
    public function config($config = []): SlimSelectFilter
    {
        $version = explode(".", app()->version())[0];
        if ($version == 8) {
            foreach ($config as $configIndex => $configValue) {
                if (! is_array($configValue)) {
                    $this->config[$configIndex] = $configValue;
                } else {
                    foreach ($configValue as $configIndex2 => $configValue2) {
                        if (! is_array($configValue2)) {
                            $this->config[$configIndex][$configIndex2] = $configValue2;
                        } else {
                            foreach ($configValue2 as $configIndex3 => $configValue3) {
                                $this->config[$configIndex][$configIndex2][$configIndex3] = $configValue3;
                            }
                        }
                    }
                }
            }
        } else {
            $flattened = \Illuminate\Support\Arr::dot($config);

            \Illuminate\Support\Arr::map($flattened, function (string $value, string $key) {
                \Illuminate\Support\Arr::set($this->config, $key, $value);
    
                return true;
            });
        }

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
        // @codeCoverageIgnoreStart
        if (! isset($component->{$component->getTableName()}['filters'][$this->getKey()])) {
            $component->{$component->getTableName()}['filters'][$this->getKey()] = [];
        }

        // @codeCoverageIgnoreEnd
        return view('lrlAdvancedTableFilters::components.tools.filters.slimSelect', [
            'component' => $component,
            'theme' => $component->getTheme(),
            'filter' => $this,
        ]);
    }
}
