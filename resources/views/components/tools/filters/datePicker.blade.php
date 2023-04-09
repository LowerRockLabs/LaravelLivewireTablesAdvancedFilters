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

    $dateString = !is_null($this->{$tableName}['filters'][$filterKey]) && $this->{$tableName}['filters'][$filterKey] != '' ? $this->{$tableName}['filters'][$filterKey] : date('Y-m-d');
    $filterContainerName = "datePickerContainer";
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
        <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"
            integrity="sha256-Huqxy3eUcaCwqqk92RwusapTfWlvAasF6p2rxV6FJaE=" crossorigin="anonymous"></script>
    @endif

    @if ($pushFlatpickrCss)
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css"
            integrity="sha256-GzSkJVLJbxDk36qko2cnawOGiqz/Y8GsQv/jMTUrx1Q=" crossorigin="anonymous">
    @endif

    <div x-data="{
        flatpickrInstance: flatpickr($refs.datePickerInput{{ $filterKey }}, {
            onOpen: function() {
                childElementOpen = true;
            },
            onChange: function(selectedDates, dateStr, instance) {
                if (dateStr.length > 3) {
                    if ($refs.datePickerInput{{ $filterKey }}.value != dateStr) {
                        $refs.datePickerInput{{ $filterKey }}.value = dateStr;
                    }
                    if ($wire.get('{{ $filterBasePath }}') != dateStr)
                    {
                        $wire.set('{{ $filterBasePath }}', dateStr);
                    }
                }
            },
            mode: 'single',
            clickOpens: true,
            allowInvalidPreload: true,
            ariaDateFormat: '{{ $filter->getConfig('ariaDateFormat') }}',
            allowInput: '{{ $filter->getConfig('allowInput') }}',
            altFormat: '{{ $filter->getConfig('altFormat') }}',
            altInput: '{{ $filter->getConfig('altInput') }}',
            dateFormat: '{{ $filter->getConfig('dateFormat') }}',
            defaultDate: '{{ $dateString }}',
            locale: '{{ App::currentLocale() }}',
            @if($filter->getConfig('timeEnabled') == 1)enableTime: true, @else enableTime: false,@endif
            @if($filter->hasConfig('earliestDate'))minDate: '{{ $filter->getConfig('earliestDate') }}',@endif
            @if($filter->hasConfig('latestDate')) maxDate: '{{ $filter->getConfig('latestDate') }}' @endif
        }),
        init() {
            childElementOpen = true;
        }
    }">
        @if ($theme === 'tailwind')
            <x-lrlAdvancedTableFilters::elements.labelInternal :theme="$theme" :filterLabelPath="$filterLabelPath"
                :filterName="$filterName" />


            <div class="w-full rounded-md shadow-sm text-right"
                placeholder="{{ __('app.enter') }} {{ __('app.date') }}">

                <x-lrlAdvancedTableFilters::forms.datePicker-textinput :filterKey="$filterKey" :theme="$theme"
                    :tableName="$tableName" :dateString="$dateString" :filterLabelPath="$filterLabelPath" :filterBasePath="$filterBasePath" />

                <x-lrlAdvancedTableFilters::icons.calendarIcon :theme="$theme" />
            </div>
        @elseif ($theme === 'bootstrap-4' || $theme === 'bootstrap-5')
            <x-lrlAdvancedTableFilters::elements.labelInternal :theme="$theme" :filterLabelPath="$filterLabelPath"
                :filterName="$filterName" />

            <div class="d-inline-block w-100 mb-3 mb-md-0 input-group"
                placeholder="{{ __('app.enter') }} {{ __('app.date') }}">

                <x-lrlAdvancedTableFilters::forms.datePicker-textinput :filterKey="$filterKey" :theme="$theme"
                    :tableName="$tableName" :dateString="$dateString" :filterLabelPath="$filterLabelPath" :filterBasePath="$filterBasePath" />

                <x-lrlAdvancedTableFilters::icons.calendarIcon :theme="$theme" />
            </div>
        @endif
    </div>
</div>
