@php
    $theme = $component->getTheme();
    $tableName = $component->getTableName();
    $filterKey = $filter->getKey();
    $dateString = !is_null($this->{$tableName}['filters'][$filterKey]) && $this->{$tableName}['filters'][$filterKey] != '' ? $this->{$tableName}['filters'][$filterKey] : date('Y-m-d');
    $filterLabelPath = $tableName . '-filter-' . $filterKey;
    $filterName = $filter->getName();

@endphp
<div id="datePickerContainer{{ $filterKey }}" x-data="{
    allFilters: $wire.entangle('{{ $tableName }}.filters'),
    swapLabels() {
        if (document.getElementById('{{ $tableName }}-filter-{{ $filterKey }}-label') === null) {
            document.getElementById('datePickerContainer{{ $filterKey }}').parentElement.firstElementChild.classList.add('hidden');
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
                    $refs.datePickerInput{{ $filterKey }}.value = dateStr;
                }

            },
            onClose: function() {
                childElementOpen = false;
            },
            mode: 'single',
            clickOpens: false,
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
            <x-livewiretablesadvancedfilters::elements.labelInternal :filterLabelPath=$filterLabelPath
                :filterName=$filterName />

            <div x-on:click="flatpickrInstance.toggle()" class="w-full rounded-md shadow-sm text-right"
                placeholder="{{ __('app.enter') }} {{ __('app.date') }}">

                <x-livewiretablesadvancedfilters::forms.datePicker-textinput :filterKey=$filterKey :tableName=$tableName
                    :dateString=$dateString />

                <x-livewiretablesadvancedfilters::icons.calendarIcon />
            </div>
        @elseif ($theme === 'bootstrap-4' || $theme === 'bootstrap-5')
            <x-livewiretablesadvancedfilters::elements.labelInternal :filterLabelPath=$filterLabelPath
                :filterName=$filterName />
            <div class="w-fullmb-3 mb-md-0 -ml-2 -px-2 input-group"
                placeholder="{{ __('app.enter') }} {{ __('app.date') }}">

                <x-livewiretablesadvancedfilters::forms.datePicker-textinput :filterKey=$filterKey :tableName=$tableName
                    :dateString=$dateString />

                <x-livewiretablesadvancedfilters::icons.calendarIcon />
            </div>
        @endif
    </div>
</div>
