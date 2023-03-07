@props(['theme'])
@php
    $tableName = $component->getTableName();
    $filterKey = $filter->getKey();
    $filterLabelPath = $tableName . '-filter-' . $filterKey;
    $filterBasePath = $tableName . '.filters.' . $filterKey;
    $filterName = $filter->getName();
    $filterConfigs = $filter->getConfigs();

    $dateString = !is_null($this->{$tableName}['filters'][$filterKey]) && $this->{$tableName}['filters'][$filterKey] != '' ? $this->{$tableName}['filters'][$filterKey] : date('Y-m-d');

@endphp
<div id="datePickerContainer{{ $filterKey }}" x-data="{
    allFilters: $wire.entangle('{{ $tableName }}.filters'),
    swapLabels() {
        if (document.getElementById('{{ $filterLabelPath }}-label') === null) {
            document.getElementById('datePickerContainer{{ $filterKey }}').parentElement.firstElementChild.classList.add('hidden');
            document.getElementById('datePickerContainer{{ $filterKey }}').parentElement.firstElementChild.classList.add('d-none');

        } else {
            document.getElementById('{{ $filterLabelPath }}-label').classList.add('hidden');
            document.getElementById('{{ $filterLabelPath }}-label').classList.add('d-none');

        }
        document.getElementById('{{ $filterLabelPath }}-labelInternal').classList.remove('hidden');
        document.getElementById('{{ $filterLabelPath }}-labelInternal').classList.remove('d-none');

    },
    init() {
        $watch('open', value => this.swapLabels());
        $watch('allFilters', value => this.swapLabels());
    }
}">

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

    <div x-data="{
        flatpickrInstance: flatpickr($refs.datePickerInput{{ $filterKey }}, {
            onOpen: function() {
                childElementOpen = true;
            },
            onChange: function(selectedDates, dateStr, instance) {
                if ($refs.datePickerInput{{ $filterKey }}.value != dateStr) {
                    $wire.set('{{ $filterBasePath }}', dateStr);

                    $refs.datePickerInput{{ $filterKey }}.value = dateStr;
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
            enableTime: @if ($filter->getConfig('timeEnabled') == 1) true, @else false, @endif
            locale: '{{ App::currentLocale() }}',
            @if ($filter->hasConfig('earliestDate')) minDate: '{{ $filter->getConfig('earliestDate') }}', @endif
            @if ($filter->hasConfig('latestDate')) maxDate: '{{ $filter->getConfig('latestDate') }}' @endif
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
