<?php

namespace LowerRockLabs\LaravelLivewireTablesAdvancedFilters;

// @codeCoverageIgnoreStart
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filter;

// @codeCoverageIgnoreEnd

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
        $flattened = \Illuminate\Support\Arr::dot($config);

        \Illuminate\Support\Arr::map($flattened, function (string $value, string $key) {
            \Illuminate\Support\Arr::set($this->config, $key, $value);

            return true;
        });

        return $this;
    }

    /**
     * @param  string  $value
     * @return string|bool
     */
    public function validate($value)
    {
        if ($value == '') {
            return false;
        }

        $returnedValues['date'] = $value;

        $dateFormat = $this->getConfig('dateFormat') ?? $this->getConfig('defaults')['dateFormat'];

        $validator = \Illuminate\Support\Facades\Validator::make($returnedValues, [
            'date' => 'required|date_format:'.$dateFormat,
        ]);
        if ($validator->fails()) {
            return false;
        }

        $date = \Carbon\Carbon::createFromFormat($dateFormat, $value);
        if (! $date) {
            return false;
        }

        $earliestDateString = $this->getConfig('earliestDate') ?? $this->getConfig('defaults')['earliestDate'];
        if ($earliestDateString != '') {
            $earliestDate = \Carbon\Carbon::createFromFormat($dateFormat, $earliestDateString);
            if (! $earliestDate) {
                return false;
            }
            if ($date->lt($earliestDate)) {
                return false;
            }
        }

        $latestDateString = $this->getConfig('latestDate') ?? $this->getConfig('defaults')['latestDate'];
        if ($latestDateString != '') {
            $latestDate = \Carbon\Carbon::createFromFormat($dateFormat, $latestDateString);
            if (! $latestDate) {
                return false;
            }
            if ($date->gt($latestDate)) {
                return false;
            }
        }

        return $value;
    }

    /**
     * @param  string  $value
     */
    public function isEmpty($value): bool
    {
        if ($value === '' || empty($value)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param  mixed  $value
     */
    public function getFilterPillValue($value): ?string
    {
        $validatedValue = $this->validate($value);

        if ($validatedValue) {
            $dateFormat = $this->getConfig('dateFormat') ?? $this->getConfig('defaults')['dateFormat'];
            $ariaDateFormat = $this->getConfig('ariaDateFormat') ?? $this->getConfig('defaults')['ariaDateFormat'];

            $carbonInstance = \Carbon\Carbon::createFromFormat($dateFormat, $value);



            return $carbonInstance->format($ariaDateFormat);
        }

        return '';
    }

    /**
     * @return array<mixed>|string|null
     */
    public function getDefaultValue()
    {
        return null;
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

        return view('livewiretablesadvancedfilters::components.tools.filters.datePicker', [
            'component' => $component,
            'theme' => $component->getTheme(),
            'filter' => $this,
        ]);
    }
}
