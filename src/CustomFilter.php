<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters;

// @codeCoverageIgnoreStart
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filter;

// @codeCoverageIgnoreEnd

class CustomFilter extends Filter
{
    /**
     * @var array<mixed>
     */
    protected array $options = [];

    public function __construct(string $name, string $key = null)
    {
        parent::__construct($name, (isset($key) ? $key : null));
    }

    /**
     * @param  array<mixed>  $options
     * @return $this
     */
    public function options($options = []): CustomFilter
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
     * @param  array<mixed>  $config
     * @return $this
     */
    public function config($config = []): CustomFilter
    {
        $version = explode(".",app()->version())[0];
        if ($version == 8)
        {
            foreach ($config as $configIndex => $configValue)
            {
                if (!is_array($configValue))
                {
                    $this->config[$configIndex] = $configValue;
                }
                else
                {
                    foreach ($configValue as $configIndex2 => $configValue2)
                    {
                        if (!is_array($configValue2))
                        {
                            $this->config[$configIndex][$configIndex2] = $configValue2;
                        }
                        else
                        {
                            foreach ($configValue2 as $configIndex3 => $configValue3)
                            {
                                $this->config[$configIndex][$configIndex2][$configIndex3] = $configValue3;
                            }
                        }
                    }
                }
            }
        }
        else
        {
            $flattened = \Illuminate\Support\Arr::dot($config);

            \Illuminate\Support\Arr::map($flattened, function (string $value, string $key) {
                \Illuminate\Support\Arr::set($this->config, $key, $value);
    
                return true;
            });
        }
        return $this;
    }


    /**
     * @param  array<mixed>|string  $values
     * @return array<mixed>|bool
     */
    public function validate($values)
    {
        return is_array($values);
    }

    /**
     * @param  mixed  $values
     */
    public function isEmpty($values): bool
    {
        return empty($values);
    }

    /**
     * @param  mixed  $value
     */
    public function getFilterPillValue($value): ?string
    {
        return '';
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
            $component->{$component->getTableName()}['filters'][$this->getKey()] = $this->getDefaultValue();
        }

        // @codeCoverageIgnoreEnd

        return view('livewiretablesadvancedfilters::components.tools.filters.customFilter', [
            'component' => $component,
            'filter' => $this,
        ]);
    }
}
