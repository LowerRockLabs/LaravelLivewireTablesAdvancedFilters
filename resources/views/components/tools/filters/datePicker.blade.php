@props(['theme'])
@php
    $tableName = $component->getTableName();
    $filterKey = $filter->getKey();
    $filterLabelPath = $tableName . '-filter-' . $filterKey;
    $filterBasePath = $tableName . '.filters.' . $filterKey;
    $filterMenuLabel = '[aria-labelledby="filters-menu"]';
    $filterName = $filter->getName();
    $filterConfigs = $filter->getConfigs();
    $customFilterMenuWidth = $filterConfigs['customFilterMenuWidth'];
    $pushFlatpickrCss = $filterConfigs['publishFlatpickrCSS'];
    $pushFlatpickrJS = $filterConfigs['publishFlatpickrJS'];

    $dateString = !is_null($this->{$tableName}['filters'][$filterKey]) && $this->{$tableName}['filters'][$filterKey] != '' ? $this->{$tableName}['filters'][$filterKey] : date('Y-m-d');

@endphp
<div id="datePickerContainer{{ $filterKey }}" x-data="{
    allFilters: $wire.entangle('{{ $tableName }}.filters'),
    @if ($theme == 'tailwind') twMenuElements: document.getElementsByClassName('relative block md:inline-block text-left'), @endif
    @if ($theme === 'bootstrap-4' || $theme === 'bootstrap-5') bsMenuElements: document.getElementsByClassName('btn-group d-block d-md-inline'), @endif
    setupFilterMenu() {
        if (document.querySelector('{{ $filterMenuLabel }}') !== null) {
            document.querySelector('{{ $filterMenuLabel }}').classList.add('{{ $customFilterMenuWidth }}');
            document.querySelector('{{ $filterMenuLabel }}').style.width = '20em !important';
            document.querySelector('{{ $filterMenuLabel }}').classList.remove('md:w-56');
        }

        if (document.getElementById('{{ $filterLabelPath }}-label') === null) {
            if (document.getElementById('datePickerContainer{{ $filterKey }}').parentElement.firstElementChild !== null) {
                document.getElementById('datePickerContainer{{ $filterKey }}').parentElement.firstElementChild.classList.add('hidden');
                document.getElementById('datePickerContainer{{ $filterKey }}').parentElement.firstElementChild.classList.add('d-none');
            }
        } else {
            document.getElementById('{{ $filterLabelPath }}-label').classList.add('hidden');
            document.getElementById('{{ $filterLabelPath }}-label').classList.add('d-none');
        }

        if (document.getElementById('{{ $filterLabelPath }}-labelInternal') !== null) {
            document.getElementById('{{ $filterLabelPath }}-labelInternal').classList.remove('hidden');
            document.getElementById('{{ $filterLabelPath }}-labelInternal').classList.remove('d-none');
        }
        @if ($theme === 'tailwind') for (let i = 0; i < this.twMenuElements.length; i++) {
            if (this.twMenuElements.item(i).getAttribute('x-data') != '{ open: true, childElementOpen: true  }') {
                this.twMenuElements.item(i).setAttribute('x-data', '{ open: true, childElementOpen: true  }');
                this.twMenuElements.item(i).setAttribute('x-on:mousedown.away', 'if (!childElementOpen) { open = false }');
            }
        } @endif
        @if ($theme === 'bootstrap-4' || $theme === 'bootstrap-5') for (let i = 0; i < this.bsMenuElements.length; i++) {
            if (this.bsMenuElements.item(i).getAttribute('x-data') != '{ open: true, childElementOpen: true  }') {
                this.bsMenuElements.item(i).setAttribute('x-data', '{ open: true, childElementOpen: true  }');
                this.bsMenuElements.item(i).setAttribute('x-on:mousedown.away', 'if (!childElementOpen) { open = false }');
            }
        } @endif
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
        flatpickrInstance: flatpickr($refs.datePickerInput{{ $filterKey }}, {
            onOpen: function() {
                childElementOpen = true;
            },
            onChange: function(selectedDates, dateStr, instance) {
                if (dateStr.length > 3) {
                    if ($refs.datePickerInput{{ $filterKey }}.value != dateStr) {
                        $wire.set('{{ $filterBasePath }}', dateStr);

                        $refs.datePickerInput{{ $filterKey }}.value = dateStr;
                    }
                }


            },
            onClose: function() {
                childElementOpen = false;
            },
            mode: 'single',
            clickOpens: false,
            allowInvalidPreload: true,
            ariaDateFormat: '{{ $filter->getConfig('ariaDateFormat') }}',
            allowInput: '{{ $filter->getConfig('allowInput') }}',
            altFormat: '{{ $filter->getConfig('altFormat') }}',
            altInput: '{{ $filter->getConfig('altInput') }}',
            dateFormat: '{{ $filter->getConfig('dateFormat') }}',
            defaultDate: '{{ $dateString }}',
            locale: '{{ App::currentLocale() }}',
            enableTime: @if($filter->getConfig('timeEnabled') == 1)
            true,
            @else
            false,
            @endif
            @if($filter->hasConfig('earliestDate'))
            minDate: '{{ $filter->getConfig('earliestDate') }}',
            @endif
            @if($filter->hasConfig('latestDate'))
            maxDate: '{{ $filter->getConfig('latestDate') }}'
            @endif
        }),
        init() {

        }
    }">


        @if ($theme === 'tailwind')
            <x-livewiretablesadvancedfilters::elements.labelInternal :theme="$theme" :filterLabelPath="$filterLabelPath"
                :filterName="$filterName" />


            <div x-on:click="flatpickrInstance.toggle()" class="w-full rounded-md shadow-sm text-right"
                placeholder="{{ __('app.enter') }} {{ __('app.date') }}">

                <x-livewiretablesadvancedfilters::forms.datePicker-textinput :filterKey="$filterKey" :theme="$theme"
                    :tableName="$tableName" :dateString="$dateString" :filterLabelPath="$filterLabelPath" :filterBasePath="$filterBasePath" />

                <x-livewiretablesadvancedfilters::icons.calendarIcon :theme="$theme" />
            </div>
        @elseif ($theme === 'bootstrap-4' || $theme === 'bootstrap-5')
            <x-livewiretablesadvancedfilters::elements.labelInternal :theme="$theme" :filterLabelPath="$filterLabelPath"
                :filterName="$filterName" />

            <div x-on:click="flatpickrInstance.toggle()" class="d-inline-block w-100 mb-3 mb-md-0 input-group"
                placeholder="{{ __('app.enter') }} {{ __('app.date') }}">

                <x-livewiretablesadvancedfilters::forms.datePicker-textinput :filterKey="$filterKey" :theme="$theme"
                    :tableName="$tableName" :dateString="$dateString" :filterLabelPath="$filterLabelPath" :filterBasePath="$filterBasePath" />

                <x-livewiretablesadvancedfilters::icons.calendarIcon :theme="$theme" />
            </div>
        @endif
    </div>
</div>
