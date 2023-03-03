@php
    $theme = $component->getTheme();
    $tableName = $component->getTableName();
    $filterKey = $filter->getKey();
    $dateInput = !is_null($this->{$tableName}['filters'][$filterKey]) && $this->{$tableName}['filters'][$filterKey] != '' ? $this->{$tableName}['filters'][$filterKey] : date('Y-m-d');
    
@endphp
@if (Config::get('livewiretablesadvancedfilters.datePicker.publishFlatpickrJS'))
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    @pushOnce('scripts')
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @endPushOnce
@endif

@if (Config::get('livewiretablesadvancedfilters.datePicker.publishFlatpickrCSS'))
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    @pushOnce('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @endPushOnce
@endif

<div>
    @if ($theme === 'tailwind')
        <label for="{{ $tableName }}-filter-{{ $filterKey }}"
            class="block text-sm font-medium leading-5 text-gray-700 dark:text-white">
            {{ $filter->getName() }}
        </label>
        <div class="-ml-2 -px-2 rounded-md shadow-sm "
            x-data='{
            init() {
                flatpickr($refs.input, {
                    onOpen: function()
                    {
                        childElementOpen = true;
                    },
                    onChange: function (selectedDates, dateStr, instance)
                    {
                        if ($refs.input.value != dateStr)
                        {
                            $refs.input.value = dateStr;
                        }

                    },
                    onClose: function()
                    {
                        childElementOpen = false;
                    },
                    mode:"single",
                    clickOpens: true,
                    ariaDateFormat:"{{ $filter->getConfig('ariaDateFormat') }}",
                    allowInput:"{{ $filter->getConfig('allowInput') }}",
                    altFormat:"{{ $filter->getConfig('altFormat') }}",
                    altInput:"{{ $filter->getConfig('altInput') }}",
                    dateFormat:"{{ $filter->getConfig('dateFormat') }}",
                    defaultDate:"{{ $dateInput }}",
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
                    wire:model.debounce.2000ms="{{ $tableName }}.filters.{{ $filterKey }}"
                    wire:key="{{ $tableName }}-filter-{{ $filterKey }}"
                    id="{{ $tableName }}-filter-{{ $filterKey }}"
                    class="inline-block w-11/12 transition duration-150 ease-in-out border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:text-white dark:border-gray-600" />
                <a class="inline-block w-6 h-6 -ml-8 input-button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                    </svg>
                </a>
            </div>

        </div>
    @elseif ($theme === 'bootstrap-4' || $theme === 'bootstrap-5')
        <label for="{{ $tableName }}-filter-{{ $filterKey }}"
            class="block text-sm font-medium leading-5 text-gray-700 dark:text-white">
            {{ $filter->getName() }}
        </label>
        <div class="mb-3 mb-md-0 -ml-2 -px-2 input-group"
            x-data='{
            init() {
                flatpickr($refs.input, {
                    onOpen: function()
                    {
                        childElementOpen = true;
                    },
                    onChange: function (selectedDates, dateStr, instance)
                    {
                        $refs.input.value = dateStr;
                    },
                    onClose: function()
                    {
                        childElementOpen = false;
                    },
                    mode:"single",
                    clickOpens: true,
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
                <input type="text" class="form-control" x-ref="input"
                    wire:model.debounce.2000ms="{{ $tableName }}.filters.{{ $filterKey }}"
                    wire:key="{{ $tableName }}-filter-{{ $filterKey }}"
                    id="{{ $tableName }}-filter-{{ $filterKey }}"
                    class="inline-block w-11/12 transition duration-150 ease-in-out border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:text-white dark:border-gray-600" />
                <a class="inline-block w-6 h-6 -ml-8 input-button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                    </svg>
                </a>
            </div>
        </div>
    @endif
</div>
