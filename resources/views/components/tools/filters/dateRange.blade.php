@php
    $theme = $component->getTheme();
    $yesterday = date('Y-m-d', strtotime('-1 days'));
    
    $dateInput = isset($this->{$component->getTableName()}['filters']) ? $this->{$component->getTableName()}['filters'][$filter->getKey()] : '';
    if ($dateInput != '') {
        if (is_array($dateInput)) {
            $startDate = isset($dateInput['minDate']) ? $dateInput['minDate'] : (isset($dateInput[1]) ? $dateInput[1] : date('Y-m-d'));
            $endDate = isset($dateInput['maxDate']) ? $dateInput['maxDate'] : (isset($dateInput[0]) ? $dateInput[0] : date('Y-m-d'));
        } else {
            $dateArray = explode(',', $dateInput);
            $startDate = isset($dateArray[0]) ? $dateArray[0] : date('Y-m-d');
            $endDate = isset($dateArray[2]) ? $dateArray[2] : date('Y-m-d');
        }
    } else {
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d');
    }
    
    $startDate = strlen($startDate) > 2 ? $startDate : $yesterday;
    
    $endDate = strlen($endDate) > 2 ? $endDate : date('Y-m-d');
    
    $dateString = $startDate . ' to ' . $endDate;
    
    $filterConfigs = $filter->getConfigs();
@endphp

@if (Config::get('livewiretablesadvancedfilters.dateRange.publishFlatpickrJS'))
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    @pushOnce('scripts')
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @endPushOnce
@endif

@if (Config::get('livewiretablesadvancedfilters.dateRange.publishFlatpickrCSS'))
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    @pushOnce('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @endPushOnce
@endif

@if ($theme === 'tailwind')
    <div class="rounded-md shadow-sm"
        x-data='{
            init() {
                flatpickr($refs.input, {
                    onChange: function (selectedDates, dateStr, instance)
                    {
                        if (selectedDates.length == 2)
                        {
                             $wire.set("{{ $component->getTableName() }}.filters.{{ $filter->getKey() }}", [dateStr.split(" ")[0],dateStr.split(" ")[2]] );
                        }
                    },
                    mode:"range",
                    ariaDateFormat:"{{ $filter->getConfig('ariaDateFormat') }}",
                    allowInput:"{{ $filter->getConfig('allowInput') }}",
                    altFormat:"{{ $filter->getConfig('altFormat') }}",
                    altInput:"{{ $filter->getConfig('altInput') }}",
                    dateFormat:"{{ $filter->getConfig('dateFormat') }}",
                    defaultDate:[$refs.input.value.split(" ")[0],$refs.input.value.split(" ")[2]],
                    enableTime: false,
                    locale: "{{ App::currentLocale() }}",
                    @if ($filter->hasConfig('earliestDate')) minDate:"{{ $filter->getConfig('earliestDate') }}", @endif
                    @if ($filter->hasConfig('latestDate')) maxDate:"{{ $filter->getConfig('latestDate') }}", @endif
                });
            }
        }'
        x-effect="init" placeholder="{{ __('app.enter') }} {{ __('app.date') }}">


        <input type="text" x-ref="input" value="{{ $dateString }}"
            wire:key="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
            id="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
            class="px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none dark:bg-darker dark:text-white focus:outline-none focus:shadow-outline" />
    </div>
@elseif ($theme === 'bootstrap-4' || $theme === 'bootstrap-5')
    <div class="mb-3 mb-md-0 input-group">
        <input wire:model.stop="{{ $component->getTableName() }}.filters.{{ $filter->getKey() }}"
            wire:key="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
            id="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}" type="date"
            @if ($filter->hasConfig('earliestDate')) min="{{ $filter->getConfig('earliestDate') }}" @endif
            @if ($filter->hasConfig('latestDate')) max="{{ $filter->getConfig('latestDate') }}" @endif
            class="form-control" />
    </div>
@endif
