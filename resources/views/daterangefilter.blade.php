@php
    $theme = $component->getTheme();
    $dateInput = '';
    if (isset($this->{$component->getTableName()}['filters'])) {
        $dateInput = $this->{$component->getTableName()}['filters'][$filter->getKey()];
    }
    if ($dateInput != '') {
        $dateArray = explode(',', $dateInput);
        $startDate = isset($dateArray[0]) ? $dateArray[0] : date('Y-m-d');
        $endDate = isset($dateArray[2]) ? $dateArray[2] : date('Y-m-d');
    } else {
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d');
    }
@endphp
@if ($theme === 'tailwind')
    <div class="rounded-md shadow-sm"
        x-data='{
            extraClass: $store.darkMode.on ? "dark" : "", dateString: "",
            init() {
                window.flatpickr($refs.input, {
                    onChange: function (selectedDates, dateStr, instance)
                    {
                        if (selectedDates.length == 2)
                        {
                             $wire.set("{{ $component->getTableName() }}.filters.{{ $filter->getKey() }}", dateStr);
                        }
                    },
                    mode:"range",
                    @if ($filter->hasConfig('dateFormat')) dateFormat:"{{ $filter->getConfig('dateFormat') }}",
                    @else
                    dateFormat:"Y-m-d", @endif
                    enableTime: false,
                    altInput: true,
                    locale: "{{ App::currentLocale() }}",
                    @if ($filter->hasConfig('altFormat')) altFormat:"{{ $filter->getConfig('altFormat') }}",
                    @else
                    altFormat:"F j, Y", @endif
                    @if ($filter->hasConfig('minDate')) minDate:"{{ $filter->getConfig('minDate') }}", @endif
                    @if ($filter->hasConfig('maxDate')) maxDate:"{{ $filter->getConfig('maxDate') }}", @endif
                    defaultDate:[$refs.input.value.split(",")[0],$refs.input.value.split(",")[1]],
                });
            }
        }'
        x-effect="init" placeholder="{{ __('app.enter') }} {{ __('app.date') }}">


        <input type="text" x-ref="input"
            wire:model.defer="{{ $component->getTableName() }}.filters.{{ $filter->getKey() }}"
            wire:key="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
            id="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
            class="px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none dark:bg-darker dark:text-white focus:outline-none focus:shadow-outline" />
    </div>
@elseif ($theme === 'bootstrap-4' || $theme === 'bootstrap-5')
    <div class="mb-3 mb-md-0 input-group">
        <input wire:model.stop="{{ $component->getTableName() }}.filters.{{ $filter->getKey() }}"
            wire:key="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
            id="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}" type="date"
            @if ($filter->hasConfig('min')) min="{{ $filter->getConfig('min') }}" @endif
            @if ($filter->hasConfig('max')) max="{{ $filter->getConfig('max') }}" @endif class="form-control" />
    </div>
@endif
