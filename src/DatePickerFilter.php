<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters;

use DateTime;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class DatePickerFilter extends Filter
{
    /**
     * @var array<mixed>
     */
    protected array $options = [];

    public function __construct(string $name, string $key = null)
    {
        parent::__construct($name, (isset($key) ? $key : null));
        $this->config = config('livewiretablesadvancedfilters.datePicker');
        $this->options = config('livewiretablesadvancedfilters.datePicker.defaults');
    }

    /**
     * @return array<mixed>
     */
    public function getKeys(): array
    {
        return ['date' => ''];
    }

    /**
     * @param  array<mixed>  $options
     * @return $this
     */
    public function options($options = []): DatePickerFilter
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
    public function config($config = []): DatePickerFilter
    {
        $this->config = array_merge($this->config, $config);

        return $this;
    }

    /**
     * @param  string  $value
     * @return bool|string
     */
    public function validate($value)
    {
        if ($value == '') {
            return false;
        }
        if (is_array($value)) {
            if ($value['date'] == '' || empty($value['date'])) {
                return false;
            }
        }
        if (empty($value['date'])) {
            return false;
        }
        $dateFormat = $this->getConfigs()['defaults']['dateFormat'];

        if (! DateTime::createFromFormat($dateFormat, $value['date'])) {
            return false;
        }

        return $value;
    }

    /**
     * @param  string  $value
     */
    public function isEmpty($value): bool
    {
        return $value === '' || (empty($value['date']) || $value['date'] == '');
    }

    /**
     * @return array<mixed>
     */
    public function getDefaultValue(): array
    {
        return ['date' => ''];
    }

    /**
     * @param  mixed  $value
     */
    public function getFilterPillValue($value): ?string
    {
        $dateFormat = $this->getConfigs()['defaults']['dateFormat'];
        $displayFormat = $this->getConfigs()['defaults']['ariaDateFormat'];
        if ($value['date'] != '' && ! empty($value['date'])) {
            $date = (! empty($value['date'] && $value['date'] != '') ? DateTime::createFromFormat($dateFormat, $value['date'])->format($displayFormat) : '');
        } else {
            return '';
        }

        return $date;
    }

    /**
     * @return \Illuminate\View\View|\Illuminate\View\Factory
     */
    public function render(DataTableComponent $component)
    {
        return view('livewiretablesadvancedfilters::components.tools.filters.datePicker', [
            'component' => $component,
            'filter' => $this,
        ]);
    }
}
