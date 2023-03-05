@php
    $theme = $component->getTheme();
    $tableName = $component->getTableName();
    $filterKey = $filter->getKey();
    
    $defaultMin = $currentMin = $filterMin = $minRange = $filter->getConfig('minRange');
    $defaultMax = $currentMax = $filterMax = $maxRange = $filter->getConfig('maxRange');
    $filterBasePath = $tableName . '.filters.' . $filterKey;
    $minFilterWirePath = $filterBasePath . '.min';
    $maxFilterWirePath = $filterBasePath . '.max';
    
    if (isset($this->{$tableName}['filters'])) {
        $currentMin = !is_null($this->{$tableName}['filters'][$filterKey]['min']) ? $this->{$tableName}['filters'][$filterKey]['min'] : $defaultMin;
        $currentMax = isset($this->{$tableName}['filters'][$filterKey]['max']) ? (!is_null($this->{$tableName}['filters'][$filterKey]['max']) ? $this->{$tableName}['filters'][$filterKey]['max'] : $filterMax) : $filterMax;
    }
    $lightStyling = $filter->getConfig('styling')['light'];
    $darkStyling = $filter->getConfig('styling')['dark'];
@endphp


<div x-data="{
    allFilters: $wire.entangle('{{ $tableName }}.filters'),
    currentMin: $refs.filterMin.value,
    currentMax: $refs.filterMax.value,
    minValue: $wire.entangle('{{ $minFilterWirePath }}'),
    maxValue: $wire.entangle('{{ $maxFilterWirePath }}'),
    defaultMin: {{ $minRange }},
    defaultMax: {{ $maxRange }},
    restrictUpdates: true,
    swapLabels() {
        document.getElementById('{{ $tableName }}-filter-{{ $filterKey }}-labelInternal').classList.remove('hidden');
        document.getElementById('{{ $tableName }}-filter-{{ $filterKey }}-label').classList.add('hidden');
    },
    setupWire() {
        this.swapLabels();

        if (this.minValue === null) {
            $refs.filterMin.value = this.defaultMin;
        } else {
            $refs.filterMin.value = this.minValue;
        }
        if (this.maxValue === null) {
            $refs.filterMax.value = this.defaultMax;
        } else {
            $refs.filterMax.value = this.maxValue;
        }

        this.updateStyles();
    },
    updateStyles() {
        document.getElementById('{{ $filterBasePath }}').style.setProperty('--value-b', $refs.filterMin.value);
        document.getElementById('{{ $filterBasePath }}').style.setProperty('--text-value-b', JSON.stringify($refs.filterMin.value));
        document.getElementById('{{ $filterBasePath }}').style.setProperty('--value-a', $refs.filterMax.value);
        document.getElementById('{{ $filterBasePath }}').style.setProperty('--text-value-a', JSON.stringify($refs.filterMax.value));
    },
    allowUpdates() {
        this.restrictUpdates = false;
        this.updateWire();
    },
    updateWire() {
        this.swapLabels();
        this.updateStyles();

        if (!this.restrictUpdates) {
            if (this.minValue === null || this.maxValue === null) {
                if ($refs.filterMin.value != this.defaultMin || $refs.filterMax.value != this.defaultMax) {
                    this.minValue = $refs.filterMin.value;
                    this.maxValue = $refs.filterMax.value;
                }
            } else {
                if (this.minValue != $refs.filterMin.value) {
                    this.minValue = $refs.filterMin.value;
                }
                if (this.maxValue != $refs.filterMin.value) {
                    this.maxValue = $refs.filterMax.value;
                }
            }
            this.restrictUpdates = true;
        }
    },
    init() {
        this.setupWire();
        this.swapLabels();
        $watch('open', value => this.swapLabels());
        $watch('open', value => this.setupWire());
        $watch('allFilters', value => this.swapLabels());
        $watch('allFilters', value => this.setupWire());

    },
}">
    @if ($theme === 'tailwind')
        <label for="{{ $tableName }}-filter-{{ $filterKey }}"
            id="{{ $tableName }}-filter-{{ $filterKey }}-labelInternal"
            class="block text-sm font-medium leading-5 text-gray-700 dark:text-white hidden">
            {{ $filter->getName() }}
        </label>
        <div class="mt-4 h-22 pt-8 pb-4 grid gap-10">
            <div x-on:mouseleave="allowUpdates" class="range-slider flat" id="{{ $filterBasePath }}"
                data-ticks-position='bottom'
                style='--min:{{ $minRange }};
                --max:{{ $maxRange }};
                --value-a:{{ $currentMax }};
                --value-b:{{ $currentMin }};
                --suffix:"%";
                --text-value-a:"{{ $currentMax }}";
                --text-value-b:"{{ $currentMin }}";
                '>

                <input type="range" min="{{ $minRange }}" max="{{ $maxRange }}" value="{{ $currentMax }}"
                    id="{{ $maxFilterWirePath }}" x-ref='filterMax' x-on:change="updateWire()">
                <output></output>
                <input type="range" min="{{ $minRange }}" max="{{ $maxRange }}" value="{{ $currentMin }}"
                    id="{{ $minFilterWirePath }}" x-ref='filterMin' x-on:change="updateWire()">
                <output></output>
                <div class='range-slider__progress'></div>
            </div>
        </div>
    @elseif ($theme === 'bootstrap-4' || $theme === 'bootstrap-5')
        <label for="{{ $tableName }}-filter-{{ $filterKey }}"
            id="{{ $tableName }}-filter-{{ $filterKey }}-label2"
            class="block text-sm font-medium leading-5 text-gray-700 dark:text-white">
            {{ $filter->getName() }}
        </label>
        <div x-on:mouseleave="allowUpdates" class="range-slider flat w-100" id="{{ $filterBasePath }}"
            data-ticks-position='bottom'
            style='--min:{{ $minRange }};
        --max:{{ $maxRange }};
        --value-a:{{ $currentMax }};
        --value-b:{{ $currentMin }};
        --suffix:"%";
        --text-value-a:"{{ $currentMax }}";
        --text-value-b:"{{ $currentMin }}";
        '>
            <div class="range-slider flat w-100" data-ticks-position='bottom' id=""
                style='--min:{{ $filterMin }}; --max:{{ $filterMax }}; --value-a:{{ $currentMax }}; --value-b:{{ $currentMin }}; --suffix:"%"; --text-value-a:"{{ $currentMax }}"; --text-value-b:"{{ $currentMin }}";'>

                <input type="range" min="{{ $minRange }}" max="{{ $maxRange }}" value="{{ $currentMax }}"
                    id="{{ $maxFilterWirePath }}" x-ref='filterMax' x-on:change="updateWire()">
                <output></output>
                <input type="range" min="{{ $minRange }}" max="{{ $maxRange }}" value="{{ $currentMin }}"
                    id="{{ $minFilterWirePath }}" x-ref='filterMin' x-on:change="updateWire()">
                <output></output>
                <div class='range-slider__progress'></div>
            </div>
        </div>
    @endif
    @if ($filter->getConfig('cssInclude') == 'inline')
        @include('livewiretablesadvancedfilters::components.tools.filters.numberRangeCss')
    @elseif ($filter->getConfig('cssInclude') == 'include')
        @push('styles')
            <link href="{{ asset('css/numberRange.css') }}" rel="stylesheet">
        @endpush
    @endif

</div>
