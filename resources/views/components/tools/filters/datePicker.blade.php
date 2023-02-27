@php
    $theme = $component->getTheme();
    $dateInput = date('Y-m-d');
    $dateInput = isset($this->{$component->getTableName()}['filters'][$filter->getKey()]) ? $this->{$component->getTableName()}['filters'][$filter->getKey()] : '';
    if ($dateInput == '') {
        $dateInput = date('Y-m-d');
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
    <div class="rounded-md shadow-sm -ml-2 "
        x-data='{
            init() {
                flatpickr($refs.input, {
                    onOpen: function()
                    {
                        pickerOpen = true;
                        open = true;
                    },
                    onClose: function()
                    {
                        pickerOpen = false;
                    },
                    onChange: function (selectedDates, dateStr, instance)
                    {
                        $refs.input.value = dateStr;
                    },
                    mode:"single",
                    ariaDateFormat:"{{ $filter->getConfig('ariaDateFormat') }}",
                    allowInput:"{{ $filter->getConfig('allowInput') }}",
                    altFormat:"{{ $filter->getConfig('altFormat') }}",
                    altInput:"{{ $filter->getConfig('altInput') }}",
                    dateFormat:"{{ $filter->getConfig('dateFormat') }}",
                    defaultDate:[$refs.input.value],
                    enableTime: @if ($filter->getConfig('timeEnabled') == 1) true, @else false, @endif
                    locale: "{{ App::currentLocale() }}",
                    @if ($filter->hasConfig('earliestDate')) minDate:"{{ $filter->getConfig('earliestDate') }}", @endif
                    @if ($filter->hasConfig('latestDate')) maxDate:"{{ $filter->getConfig('latestDate') }}", @endif
                });
            }
        }'
        x-effect="init" placeholder="{{ __('app.enter') }} {{ __('app.date') }}">

        <div class="w-full">
            <input type="text" x-ref="input"
                wire:model.debounce.2000ms="{{ $component->getTableName() }}.filters.{{ $filter->getKey() }}"
                x-on:click="pickerOpen = true"
                wire:key="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
                id="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
                class="inline-block w-11/12 border-gray-300 rounded-md shadow-sm transition duration-150 ease-in-out focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:text-white dark:border-gray-600" />
            <a x-on:click="flatpickr($refs.input).toggle" class="inline-block input-button -ml-8 w-6 h-6">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                </svg>
            </a>
        </div>

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
