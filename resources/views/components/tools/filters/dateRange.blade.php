@php
    $theme = $component->getTheme();
    $tableName = $component->getTableName();
    $filterKey = $filter->getKey();
    $yesterday = date('Y-m-d', strtotime('-1 days'));
    $filterLabelPath = $tableName . '-filter-' . $filterKey;
    $filterName = $filter->getName();

    $dateInput = isset($this->{$tableName}['filters'][$filterKey]) ? $this->{$tableName}['filters'][$filterKey] : '';
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

<div id="dateRangeContainer{{ $filterKey }}" x-data="{
    allFilters: $wire.entangle('{{ $tableName }}.filters'),
    swapLabels() {

        if (document.getElementById('{{ $tableName }}-filter-{{ $filterKey }}-label') === null) {
            document.getElementById('dateRangeContainer{{ $filterKey }}').parentElement.firstElementChild.classList.add('hidden');
        } else {
            document.getElementById('{{ $tableName }}-filter-{{ $filterKey }}-label').classList.add('hidden');
        }
        document.getElementById('{{ $tableName }}-filter-{{ $filterKey }}-labelInternal').classList.remove('hidden');

    },
    init() {
        $watch('open', value => this.swapLabels());
        $watch('allFilters', value => this.swapLabels());
    }
}">
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
    <div x-data="{
        init() {
            flatpickr($refs.dateRangeInput{{ $filterKey }}, {
                mode: 'range',
                clickOpens: true,
                ariaDateFormat: '{{ $filter->getConfig('ariaDateFormat') }}',
                allowInput: '{{ $filter->getConfig('allowInput') }}',
                altFormat: '{{ $filter->getConfig('altFormat') }}',
                altInput: '{{ $filter->getConfig('altInput') }}',
                dateFormat: '{{ $filter->getConfig('dateFormat') }}',
                defaultDate: [$refs.dateRangeInput{{ $filterKey }}.value.split(' ')[0], $refs.dateRangeInput{{ $filterKey }}.value.split(' ')[2]],
                locale: '{{ App::currentLocale() }}',
                @if ($filter->hasConfig('earliestDate')) minDate: '{{ $filter->getConfig('earliestDate') }}', @endif
                @if ($filter->hasConfig('latestDate')) maxDate: '{{ $filter->getConfig('latestDate') }}', @endif
                onOpen: function() {
                    childElementOpen = true;
                },
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length == 2) {
                        $wire.set('{{ $tableName }}.filters.{{ $filterKey }}.minDate', dateStr.split(' ')[0]);
                        $wire.set('{{ $tableName }}.filters.{{ $filterKey }}.maxDate', dateStr.split(' ')[2]);

                    }
                },
                onClose: function() {
                    childElementOpen = false;
                },

            });
        }
    }" x-effect="init">
        @if ($theme === 'tailwind')
            <x-livewiretablesadvancedfilters::elements.labelInternal-tw :filterLabelPath=$filterLabelPath
                :filterName=$filterName />

            <div class="w-full flex flex-cols  rounded-md shadow-sm "
                placeholder="{{ __('app.enter') }} {{ __('app.date') }}">
                <div class="inline-block w-10/12">
                    <input type="text" x-ref="dateRangeInput{{ $filterKey }}"
                        x-on:click="childElementOpen = true" value="{{ $dateString }}"
                        wire:key="{{ $tableName }}-filter-{{ $filterKey }}"
                        id="{{ $tableName }}-filter-{{ $filterKey }}"
                        class="w-48	 transition duration-150 ease-in-out border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:text-white dark:border-gray-600" />
                </div>
                <span class="inline-block float-right h-full py-2 text-lg">
                    <svg width="1.5em" height="1.5em" lass="inline-block" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                    </svg>
                </span>
            </div>
        @elseif ($theme === 'bootstrap-4' || $theme === 'bootstrap-5')
            <x-livewiretablesadvancedfilters::elements.labelInternal-bs :filterLabelPath=$filterLabelPath
                :filterName=$filterName />
            <div class="mb-3 mb-md-0 input-group">
                <div class="rounded-md shadow-sm" placeholder="{{ __('app.enter') }} {{ __('app.date') }}">

                    <div class="w-full">
                        <div class="inline-block w-10/12">
                            <input type="text" x-ref="dateRangeInput{{ $filterKey }}"
                                value="{{ $dateString }}" x-on:click="childElementOpen = true"
                                wire:key="{{ $tableName }}-filter-{{ $filterKey }}"
                                id="{{ $tableName }}-filter-{{ $filterKey }}"
                                class="w-48	 transition duration-150 ease-in-out border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:text-white dark:border-gray-600" />
                            <span class="inline-block float-right h-full py-2 text-lg">
                                <svg width="1.5em" height="1.5em" lass="inline-block"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.8" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                                </svg>
                            </span>
                        </div>

                    </div>
                </div>
        @endif
    </div>
</div>
