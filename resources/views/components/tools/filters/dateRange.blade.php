@php
    $tableName = $component->getTableName();
    $filterKey = $filter->getKey();
    $filterLabelPath = $tableName . '-filter-' . $filterKey;
    $filterBasePath = $tableName . '.filters.' . $filterKey;
    $filterMenuLabel = '[aria-labelledby="filters-menu"]';
    $filterName = $filter->getName();
    $filterConfigs = $filter->getConfigs();
    $customFilterMenuWidth = (!empty($filterConfigs['customFilterMenuWidth']) ? json_encode(explode( " ", $filterConfigs['customFilterMenuWidth'])) : '');
    $pushFlatpickrCss = $filterConfigs['publishFlatpickrCSS'];
    $pushFlatpickrJS = $filterConfigs['publishFlatpickrJS'];
    $filterLayout = $component->getFilterLayout();
    $yesterday = date('Y-m-d', strtotime('-1 days'));
    $dateInput = !empty($this->{$tableName}['filters'][$filterKey]) ? $this->{$tableName}['filters'][$filterKey] : '';
    if ($dateInput != '') {
        if (is_array($dateInput)) {
            $startDate = isset($dateInput['minDate']) ? $dateInput['minDate'] : (isset($dateInput[1]) ? $dateInput[1] : date('Y-m-d'));
            $endDate = isset($dateInput['maxDate']) ? $dateInput['maxDate'] : (isset($dateInput[0]) ? $dateInput[0] : date('Y-m-d'));
        } else {
            $dateArray = explode(',', $dateInput);
            $startDate = isset($dateArray[0]) ? $dateArray[0] : date('Y-m-d');
            $endDate = isset($dateArray[2]) ? $dateArray[2] : date('Y-m-d');
        }
        $startDate = strlen($startDate) > 2 ? $startDate : $yesterday;
        $endDate = strlen($endDate) > 2 ? $endDate : date('Y-m-d');
        $dateString = $startDate . ' to ' . $endDate;

    } else {
        $dateString = '';
    }

    $filterContainerName = "dateRangeContainer";

@endphp

<div id="{{ $filterContainerName }}{{ $filterKey }}" x-data="{
    allFilters: $wire.entangle('{{ $tableName }}.filters'),
    filterMenuClasses: {{ $customFilterMenuWidth }}, 
    @if ($theme == 'tailwind') twMenuElements: document.getElementsByClassName('relative block md:inline-block text-left'), @endif
    @if ($theme === 'bootstrap-4' || $theme === 'bootstrap-5') bsMenuElements: document.getElementsByClassName('btn-group d-block d-md-inline'), @endif
    setupFilterMenu() {
        let currentFilterMenuLabel = document.querySelector('{{ $filterMenuLabel }}');

        if (currentFilterMenuLabel !== null) {
            this.filterMenuClasses.forEach(item => currentFilterMenuLabel.classList.add(item));
            currentFilterMenuLabel.style.width = '20em !important';
            currentFilterMenuLabel.classList.remove('md:w-56');
        }

    },
    init() {
        this.setupFilterMenu();
        $watch('allFilters', value => this.setupFilterMenu());
    }
}">

    @if ($pushFlatpickrJS)
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @endif

    @if ($pushFlatpickrCss)
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @endif
    <div x-data="{
        flatpickrInstance: flatpickr($refs.dateRangeInput{{ $filterKey }}, {
            mode: 'range',
            clickOpens: true,
            allowInvalidPreload: true,
            ariaDateFormat: '{{ $filter->getConfig('ariaDateFormat') }}',
            allowInput: '{{ $filter->getConfig('allowInput') }}',
            altFormat: '{{ $filter->getConfig('altFormat') }}',
            altInput: '{{ $filter->getConfig('altInput') }}',
            dateFormat: '{{ $filter->getConfig('dateFormat') }}',
            locale: '{{ App::currentLocale() }}',
            @if ($filter->hasConfig('earliestDate')) minDate: '{{ $filter->getConfig('earliestDate') }}', @endif
            @if ($filter->hasConfig('latestDate')) maxDate: '{{ $filter->getConfig('latestDate') }}', @endif
            onOpen: function() {
                childElementOpen = true;
            },
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 1) {
                    var startDate = dateStr.split(' ')[0];
                    var endDate = dateStr.split(' ')[2];
                    var wireDateArray = {};
                    wireDateArray = { 'minDate': startDate, 'maxDate': endDate };
                    $wire.set('{{ $filterBasePath }}', wireDateArray);
                }
            }
        }),
        init() {
            childElementOpen = true;
        }
    }" x-effect="init">
        @if ($theme === 'tailwind')
            @if($filter->hasCustomFilterLabel())
                @include($filter->getCustomFilterLabel(),['filter' => $filter, 'theme' => $theme, 'filterLayout' => $filterLayout, 'tableName' => $tableName  ])
            @else
                <x-livewire-tables::tools.filter-label :filter="$filter" :theme="$theme" :filterLayout="$filterLayout" :tableName="$tableName" />
            @endif


            <div class="w-full rounded-md shadow-sm text-right" placeholder="{{ __('app.enter') }} {{ __('app.date') }}">

                <x-lrlAdvancedTableFilters::forms.dateRange-textinput :theme="$theme" :filterKey="$filterKey"
                    :dateString="$dateString" :filterLabelPath="$filterLabelPath" />

                <x-lrlAdvancedTableFilters::icons.calendarIcon :theme="$theme" />

            </div>
        @elseif ($theme === 'bootstrap-4')
            @if($filter->hasCustomFilterLabel())
                @include($filter->getCustomFilterLabel(),['filter' => $filter, 'theme' => $theme, 'filterLayout' => $filterLayout, 'tableName' => $tableName  ])
            @else
                <x-livewire-tables::tools.filter-label :filter="$filter" :theme="$theme" :filterLayout="$filterLayout" :tableName="$tableName" />
            @endif

            <div class="d-inline-block w-100 mb-3 mb-md-0 input-group" placeholder="{{ __('app.enter') }} {{ __('app.date') }}">

                <x-lrlAdvancedTableFilters::forms.dateRange-textinput :theme="$theme" :filterKey="$filterKey"
                    :dateString="$dateString" :filterLabelPath="$filterLabelPath" />

                <x-lrlAdvancedTableFilters::icons.calendarIcon :theme="$theme" />
            </div>
        @elseif ($theme === 'bootstrap-5')
            @if($filter->hasCustomFilterLabel())
                @include($filter->getCustomFilterLabel(),['filter' => $filter, 'theme' => $theme, 'filterLayout' => $filterLayout, 'tableName' => $tableName  ])
            @else
                <x-livewire-tables::tools.filter-label :filter="$filter" :theme="$theme" :filterLayout="$filterLayout" :tableName="$tableName" />
            @endif

            <div class="d-inline-block w-100 mb-3 mb-md-0 input-group" placeholder="{{ __('app.enter') }} {{ __('app.date') }}">


                <x-lrlAdvancedTableFilters::forms.dateRange-textinput :theme="$theme" :filterKey="$filterKey"
                    :dateString="$dateString" :filterLabelPath="$filterLabelPath" />

                <x-lrlAdvancedTableFilters::icons.calendarIcon :theme="$theme" />
            </div>
        @endif
    </div>
</div>
