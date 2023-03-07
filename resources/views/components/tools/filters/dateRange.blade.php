@php
    $tableName = $component->getTableName();
    $filterKey = $filter->getKey();
    $filterLabelPath = $tableName . '-filter-' . $filterKey;
    $filterBasePath = $tableName . '.filters.' . $filterKey;
    $filterMenuLabel = '[aria-labelledby="filters-menu"]';
    $filterName = $filter->getName();
    $filterConfigs = $filter->getConfigs();
    $yesterday = date('Y-m-d', strtotime('-1 days'));
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

@endphp

<div id="dateRangeContainer{{ $filterKey }}" x-data="{
    allFilters: $wire.entangle('{{ $tableName }}.filters'),
    twMenuElements: document.getElementsByClassName('relative block md:inline-block text-left'),
    bsMenuElements: document.getElementsByClassName('btn-group d-block d-md-inline'),
    setupFilterMenu() {
        if (document.querySelector('{{ $filterMenuLabel }}') !== null) {
            document.querySelector('{{ $filterMenuLabel }}').classList.add('md:w-80');
            document.querySelector('{{ $filterMenuLabel }}').classList.remove('md:w-56');
        }

        if (document.getElementById('{{ $filterLabelPath }}-label') === null) {
            if (document.getElementById('dateRangeContainer{{ $filterKey }}').parentElement.firstElementChild !== null) {
                document.getElementById('dateRangeContainer{{ $filterKey }}').parentElement.firstElementChild.classList.add('hidden');
                document.getElementById('dateRangeContainer{{ $filterKey }}').parentElement.firstElementChild.classList.add('d-none');
            }
        } else {
            document.getElementById('{{ $filterLabelPath }}-label').classList.add('hidden');
            document.getElementById('{{ $filterLabelPath }}-label').classList.add('d-none');
        }

        if (document.getElementById('{{ $filterLabelPath }}-labelInternal') !== null) {
            document.getElementById('{{ $filterLabelPath }}-labelInternal').classList.remove('hidden');
            document.getElementById('{{ $filterLabelPath }}-labelInternal').classList.remove('d-none');
        }

        for (let i = 0; i < this.twMenuElements.length; i++) {
            if (this.twMenuElements.item(i).getAttribute('x-data') != '{ open: true, childElementOpen: true  }') {
                this.twMenuElements.item(i).setAttribute('x-data', '{ open: true, childElementOpen: true  }');
                this.twMenuElements.item(i).setAttribute('x-on:mousedown.away', 'if (!childElementOpen) { open = false }');
            }
        }

        for (let i = 0; i < this.bsMenuElements.length; i++) {
            if (this.bsMenuElements.item(i).getAttribute('x-data') != '{ open: true, childElementOpen: true  }') {
                this.bsMenuElements.item(i).setAttribute('x-data', '{ open: true, childElementOpen: true  }');
                this.bsMenuElements.item(i).setAttribute('x-on:mousedown.away', 'if (!childElementOpen) { open = false }');
            }
        }
    },
    init() {
        $watch('open', value => this.setupFilterMenu());
        $watch('allFilters', value => this.setupFilterMenu());
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
        flatpickrInstance: flatpickr($refs.dateRangeInput{{ $filterKey }}, {
            mode: 'range',
            clickOpens: false,
            allowInvalidPreload: true,
            defaultDate: [$refs.dateRangeInput{{ $filterKey }}.value.split(' ')[0], $refs.dateRangeInput{{ $filterKey }}.value.split(' ')[2]],
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
                if (selectedDates.length == 2) {
                    $wire.set('{{ $filterBasePath }}.minDate', dateStr.split(' ')[0]);
                    $wire.set('{{ $filterBasePath }}.maxDate', dateStr.split(' ')[2]);
                }
            },
            onClose: function() {
                childElementOpen = false;
            }
        }),
        init() {

        }
    }" x-effect="init">
        @if ($theme === 'tailwind')
            <x-livewiretablesadvancedfilters::elements.labelInternal :theme="$theme" :filterLabelPath="$filterLabelPath"
                :filterName="$filterName" />


            <div x-on:click="flatpickrInstance.toggle()" class="w-full rounded-md shadow-sm text-right"
                placeholder="{{ __('app.enter') }} {{ __('app.date') }}">

                <x-livewiretablesadvancedfilters::forms.dateRange-textinput :theme="$theme" :filterKey="$filterKey"
                    :dateString="$dateString" :filterLabelPath="$filterLabelPath" />

                <x-livewiretablesadvancedfilters::icons.calendarIcon :theme="$theme" />

            </div>
        @elseif ($theme === 'bootstrap-4')
            <x-livewiretablesadvancedfilters::elements.labelInternal :theme="$theme" :filterLabelPath="$filterLabelPath"
                :filterName="$filterName" />

            <div x-on:click="flatpickrInstance.toggle()" class="d-inline-block w-100 mb-3 mb-md-0 input-group"
                placeholder="{{ __('app.enter') }} {{ __('app.date') }}">

                <x-livewiretablesadvancedfilters::forms.dateRange-textinput :theme="$theme" :filterKey="$filterKey"
                    :dateString="$dateString" :filterLabelPath="$filterLabelPath" />

                <x-livewiretablesadvancedfilters::icons.calendarIcon :theme="$theme" />
            </div>
        @elseif ($theme === 'bootstrap-5')
            <x-livewiretablesadvancedfilters::elements.labelInternal :theme="$theme" :filterLabelPath="$filterLabelPath"
                :filterName="$filterName" />

            <div x-on:click="flatpickrInstance.toggle()" class="d-inline-block w-100 mb-3 mb-md-0 input-group"
                placeholder="{{ __('app.enter') }} {{ __('app.date') }}">


                <x-livewiretablesadvancedfilters::forms.dateRange-textinput :theme="$theme" :filterKey="$filterKey"
                    :dateString="$dateString" :filterLabelPath="$filterLabelPath" />

                <x-livewiretablesadvancedfilters::icons.calendarIcon :theme="$theme" />
            </div>
        @endif
    </div>
</div>
