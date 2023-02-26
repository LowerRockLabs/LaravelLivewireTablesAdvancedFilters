@php
    $theme = $component->getTheme();
    $dateInput = date('Y-m-d');
    if (isset($this->{$component->getTableName()}['filters'])) {
        $dateInput = $this->{$component->getTableName()}['filters'][$filter->getKey()];
    }
    
@endphp
@if (Config::get('livewiretablesadvancedfilters.datePicker.publishFlatpickrJS'))
    @pushOnce('scripts')
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @endPushOnce
@endif
@if (Config::get('livewiretablesadvancedfilters.datePicker.publishFlatpickrCSS'))
    @pushOnce('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @endPushOnce
@endif

@if ($theme === 'tailwind')
    <div class="rounded-md shadow-sm"
        x-data='{
            extraClass: $store.darkMode.on ? "dark" : "",
            init() {
                flatpickr($refs.input, {
                    onChange: function (selectedDate, dateStr, instance)
                    {
                        if (selectedDates.length == 2)
                        {
                             $wire.set("{{ $component->getTableName() }}.filters.{{ $filter->getKey() }}", dateStr);
                        }
                    },
                    mode:"single",
                    ariaDateFormat:"{{ $filter->getConfig('ariaDateFormat') }}",
                    allowInput:"{{ $filter->getConfig('allowInput') }}",
                    altFormat:"{{ $filter->getConfig('altFormat') }}",
                    altInput:"{{ $filter->getConfig('altInput') }}",
                    dateFormat:"{{ $filter->getConfig('dateFormat') }}",
                    defaultDate:[$refs.input.value],
                    enableTime: false,
                    locale: "{{ App::currentLocale() }}",
                    @if ($filter->hasConfig('minDate')) minDate:"{{ $filter->getConfig('minDate') }}", @endif
                    @if ($filter->hasConfig('maxDate')) maxDate:"{{ $filter->getConfig('maxDate') }}", @endif
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
