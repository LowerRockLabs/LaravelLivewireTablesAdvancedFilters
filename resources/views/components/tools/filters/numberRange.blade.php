@php
    $tableName = $component->getTableName();
    $filterKey = $filter->getKey();
    $filterLabelPath = $tableName . '-filter-' . $filterKey;
    $filterBasePath = $tableName . '.filters.' . $filterKey;
    $filterMenuLabel = '[aria-labelledby="filters-menu"]';
    $filterName = $filter->getName();
    $filterConfigs = $filter->getConfigs();
    $customFilterMenuWidth = $filterConfigs['customFilterMenuWidth'];
    $suffix = $filter->getConfig('suffix');

    $defaultMin = $currentMin = $filterMin = $minRange = $filter->getConfig('minRange');
    $defaultMax = $currentMax = $filterMax = $maxRange = $filter->getConfig('maxRange');
    $minFilterWirePath = $filterBasePath . '.min';
    $maxFilterWirePath = $filterBasePath . '.max';

    if (isset($this->{$tableName}['filters'])) {
        if (!empty($this->{$tableName}['filters'][$filterKey])) {
            $currentMin = isset($this->{$tableName}['filters'][$filterKey]['min']) ? $this->{$tableName}['filters'][$filterKey]['min'] : $defaultMin;
            $currentMax = isset($this->{$tableName}['filters'][$filterKey]['max']) ? $this->{$tableName}['filters'][$filterKey]['max'] : $defaultMax;
        }
    }
    $lightStyling = $filter->getConfig('styling')['light'];
    $darkStyling = $filter->getConfig('styling')['dark'];
@endphp
<div id="numberRangeContainer{{ $filterKey }}" x-data="{
    allFilters: $wire.entangle('{{ $tableName }}.filters'),
    @if ($theme == 'tailwind') twMenuElements: document.getElementsByClassName('relative block md:inline-block text-left'), @endif
    @if ($theme === 'bootstrap-4' || $theme === 'bootstrap-5') bsMenuElements: document.getElementsByClassName('btn-group d-block d-md-inline'), @endif
    currentMin: $refs.filterMin.value,
    currentMax: $refs.filterMax.value,
    wireValues: $wire.entangle('{{ $filterBasePath }}'),
    defaultMin: {{ $minRange }},
    defaultMax: {{ $maxRange }},
    restrictUpdates: true,
    setupFilterMenu() {
        if (document.querySelector('{{ $filterMenuLabel }}') !== null) {
            document.querySelector('{{ $filterMenuLabel }}').classList.add('{{ $customFilterMenuWidth }}');
            document.querySelector('{{ $filterMenuLabel }}').classList.remove('md:w-56');
        }

        if (document.getElementById('{{ $filterLabelPath }}-label') === null) {
            if (document.getElementById('numberRangeContainer{{ $filterKey }}').parentElement.firstElementChild !== null) {
                document.getElementById('numberRangeContainer{{ $filterKey }}').parentElement.firstElementChild.classList.add('hidden');
                document.getElementById('numberRangeContainer{{ $filterKey }}').parentElement.firstElementChild.classList.add('d-none');
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
    updateStyles() {
        document.getElementById('{{ $filterBasePath }}').style.setProperty('--value-b', $refs.filterMin.value);
        document.getElementById('{{ $filterBasePath }}').style.setProperty('--text-value-b', JSON.stringify($refs.filterMin.value));
        document.getElementById('{{ $filterBasePath }}').style.setProperty('--value-a', $refs.filterMax.value);
        document.getElementById('{{ $filterBasePath }}').style.setProperty('--text-value-a', JSON.stringify($refs.filterMax.value));
    },
    setupWire() {
        if (this.wireValues !== undefined) {
            $refs.filterMin.value = (this.wireValues['min'] !== undefined) ? this.wireValues['min'] : this.defaultMin;
            $refs.filterMax.value = (this.wireValues['max'] !== undefined) ? this.wireValues['max'] : this.defaultMax;
        } else {
            $refs.filterMin.value = this.defaultMin;
            $refs.filterMax.value = this.defaultMax;
        }
        this.updateStyles();
    },
    allowUpdates() {
        this.restrictUpdates = false;
        this.updateWire();
    },
    updateWire() {
        this.updateStyles();

        if (!this.restrictUpdates) {
            if ($refs.filterMin.value != this.defaultMin || $refs.filterMax.value != this.defaultMax) {
                this.wireValues = { 'min': $refs.filterMin.value, 'max': $refs.filterMax.value };
            }
            this.restrictUpdates = true;
        }
    },
    init() {
        this.setupWire();
        this.setupFilterMenu();
        $watch('allFilters', value => this.setupFilterMenu());
        $watch('allFilters', value => this.setupWire());
    },
}">
    @if ($theme === 'tailwind')
        <x-livewiretablesadvancedfilters::elements.labelInternal :theme="$theme" :filterLabelPath="$filterLabelPath"
            :filterName="$filterName" />
        <div class="mt-4 h-22 pt-8 pb-4 grid gap-10">
            <div x-on:mousedown.away="allowUpdates" x-on:touchstart.away="allowUpdates" x-on:mouseleave="allowUpdates"
                class="range-slider flat" id="{{ $filterBasePath }}" data-ticks-position='bottom'
                style='--min:{{ $minRange }};
                --max:{{ $maxRange }};
                --value-a:{{ $currentMax }};
                --value-b:{{ $currentMin }};
                --suffix:"{{ $suffix }}";
                --text-value-a:"{{ $currentMax }}";
                --text-value-b:"{{ $currentMin }}";
                '>

                <input type="range" min="{{ $minRange }}" max="{{ $maxRange }}" value="{{ $currentMax }}"
                    id="{{ $maxFilterWirePath }}" x-ref='filterMax' x-on:change="updateWire()" />
                <output></output>
                <input type="range" min="{{ $minRange }}" max="{{ $maxRange }}" value="{{ $currentMin }}"
                    id="{{ $minFilterWirePath }}" x-ref='filterMin' x-on:change="updateWire()" />
                <output></output>
                <div class='range-slider__progress'></div>
            </div>
        </div>
    @elseif ($theme === 'bootstrap-4')
        <x-livewiretablesadvancedfilters::elements.labelInternal :theme="$theme" :filterLabelPath="$filterLabelPath"
            :filterName="$filterName" />
        <div class="mt-4 h-22 w-100 pb-4 pt-2  grid gap-10" x-on:mouseleave="allowUpdates">
            <div class="range-slider flat w-100" id="{{ $filterBasePath }}" data-ticks-position='bottom'
                style='--min:{{ $minRange }};
                    --max:{{ $maxRange }};
                    --value-a:{{ $currentMax }};
                    --value-b:{{ $currentMin }};
                    --suffix:"{{ $suffix }}";
                    --text-value-a:"{{ $currentMax }}";
                    --text-value-b:"{{ $currentMin }}";
                    '>

                <input type="range" min="{{ $minRange }}" max="{{ $maxRange }}" value="{{ $currentMax }}"
                    id="{{ $maxFilterWirePath }}" x-ref='filterMax' x-on:change="updateWire()" />
                <output></output>
                <input type="range" min="{{ $minRange }}" max="{{ $maxRange }}" value="{{ $currentMin }}"
                    id="{{ $minFilterWirePath }}" x-ref='filterMin' x-on:change="updateWire()" />
                <output></output>
                <div class='range-slider__progress'></div>
            </div>
        </div>
    @elseif ($theme === 'bootstrap-5')
        <x-livewiretablesadvancedfilters::elements.labelInternal :theme="$theme" :filterLabelPath="$filterLabelPath"
            :filterName="$filterName" />
        <div class="mt-4 h-22 w-100 pb-4 pt-2  grid gap-10" x-on:mouseleave="allowUpdates">
            <div class="range-slider flat w-100" id="{{ $filterBasePath }}" data-ticks-position='bottom'
                style='--min:{{ $minRange }};
            --max:{{ $maxRange }};
            --value-a:{{ $currentMax }};
            --value-b:{{ $currentMin }};
            --suffix:"{{ $suffix }}";
            --text-value-a:"{{ $currentMax }}";
            --text-value-b:"{{ $currentMin }}";
            '>

                <input type="range" min="{{ $minRange }}" max="{{ $maxRange }}" value="{{ $currentMax }}"
                    id="{{ $maxFilterWirePath }}" x-ref='filterMax' x-on:change="updateWire()" />
                <output></output>
                <input type="range" min="{{ $minRange }}" max="{{ $maxRange }}" value="{{ $currentMin }}"
                    id="{{ $minFilterWirePath }}" x-ref='filterMin' x-on:change="updateWire()" />
                <output></output>
                <div class='range-slider__progress'></div>
            </div>
        </div>
    @endif
    @if ($filter->getConfig('cssInclude') == 'inline')
        <style>
            .range-slider {
                --primary-color: {{ $lightStyling['primaryColor'] }};
                --value-active-color: {{ $lightStyling['activeColor'] }};
                --value-background-hover: {{ $lightStyling['valueBgHover'] }};
                --fill-color: {{ $lightStyling['fillColor'] }};
                --progress-background: {{ $lightStyling['progressBackground'] }};
                --thumb-color: {{ $lightStyling['thumbColor'] }};
                --ticks-color: {{ $lightStyling['ticksColor'] }};
                --value-background: {{ $lightStyling['valueBg'] }};

                --value-offset-y: var(--ticks-gap);
                --value-font: 700 12px/1 Arial;
                --progress-radius: 20px;
                --track-height: calc(var(--thumb-size) / 2);
                --min-max-font: 12px Arial;
                --min-max-opacity: 1;
                --min-max-x-offset: 10%;
                --thumb-size: 22px;
                --thumb-shadow: 0 0 3px rgba(0, 0, 0, 0.4), 0 0 1px rgba(0, 0, 0, 0.5) inset,
                    0 0 0 99px var(--thumb-color) inset;
                --thumb-shadow-active: 0 0 0 calc(var(--thumb-size) / 4) inset var(--thumb-color),
                    0 0 0 99px var(--primary-color) inset, 0 0 3px rgba(0, 0, 0, 0.4);
                --thumb-shadow-hover: var(--thumb-shadow);
                --ticks-thickness: 1px;
                --ticks-height: 5px;
                --ticks-gap: var(--ticks-height,
                        0);
                --step: 1;
                --ticks-count: Calc(var(--max) - var(--min)) / var(--step);
                --maxTicksAllowed: 30;
                --too-many-ticks: Min(1, Max(var(--ticks-count) - var(--maxTicksAllowed), 0));
                --x-step: Max(var(--step),
                        var(--too-many-ticks) * (var(--max) - var(--min)));
                --tickInterval: 100/ ((var(--max) - var(--min)) / var(--step)) * var(--tickEvery, 1);
                --tickIntervalPerc: calc((100% - var(--thumb-size)) / ((var(--max) - var(--min)) / var(--x-step)) * var(--tickEvery, 1));
                --value-a: Clamp(var(--min),
                        var(--value, 0),
                        var(--max));
                --value-b: var(--value, 0);
                --text-value-a: var(--text-value, "");
                --completed-a: calc((var(--value-a) - var(--min)) / (var(--max) - var(--min)) * 100);
                --completed-b:
                    calc((var(--value-b) - var(--min)) / (var(--max) - var(--min)) * 100);
                --ca: Min(var(--completed-a),
                        var(--completed-b));
                --cb: Max(var(--completed-a), var(--completed-b));
                --thumbs-too-close: Clamp(-1, 1000 * (Min(1, Max(var(--cb) - var(--ca) - 5, -1)) + 0.001), 1);
                --thumb-close-to-min: Min(1, Max(var(--ca) - 2,
                            0));
                --thumb-close-to-max: Min(1, Max(98 - var(--cb), 0));
                display: inline-block;
                height:
                    max(var(--track-height), var(--thumb-size));
                background: linear-gradient(to right, var(--ticks-color) var(--ticks-thickness), transparent 1px) repeat-x;
                background-size: var(--tickIntervalPerc) var(--ticks-height);
                background-position-x: calc(var(--thumb-size) / 2 - var(--ticks-thickness) / 2);
                background-position-y: var(--flip-y, bottom);
                padding-bottom: var(--flip-y, var(--ticks-gap));
                padding-top:
                    calc(var(--flip-y) * var(--ticks-gap));
                position: relative;
                z-index: 1;
            }

            .dark .range-slider {
                --primary-color: {{ $darkStyling['primaryColor'] }};
                --value-active-color: {{ $darkStyling['activeColor'] }};
                --value-background-hover: {{ $darkStyling['valueBgHover'] }};
                --fill-color: {{ $darkStyling['fillColor'] }};
                --progress-background: {{ $darkStyling['progressBackground'] }};
                --thumb-color: {{ $darkStyling['thumbColor'] }};
                --ticks-color: {{ $darkStyling['ticksColor'] }};


            }


            .range-slider::before,
            .range-slider::after {
                --offset: calc(var(--thumb-size) / 2);
                content: counter(x);
                display:
                    var(--show-min-max, block);
                font: var(--min-max-font);
                position: absolute;
                bottom: var(--flip-y, -2.5ch);
                top: calc(-2.5ch * var(--flip-y));
                opacity: clamp(0, var(--at-edge), var(--min-max-opacity));
                transform:
                    translateX(calc(var(--min-max-x-offset) * var(--before, -1) * -1)) scale(var(--at-edge));
                pointer-events:
                    none;
            }

            .dark .range-slider::before,
            .dark .range-slider::after {
                color: #FFF;
            }

            .range-slider::before {
                --before: 1;
                --at-edge: var(--thumb-close-to-min);
                counter-reset: x var(--min);
                left: var(--offset);
            }

            .range-slider::after {
                --at-edge: var(--thumb-close-to-max);
                counter-reset: x var(--max);
                right: var(--offset);
            }

            .range-slider__values {
                position: relative;
                top: 50%;
                line-height: 0;
                text-align: justify;
                width: 100%;
                pointer-events: none;
                margin: 0 auto;
                z-index: 5;
            }

            .range-slider__values::after {
                content: "";
                width: 100%;
                display: inline-block;
                height: 0;
                background: red;
            }

            .range-slider__progress {
                --start-end: calc(var(--thumb-size) / 2);
                --clip-end: calc(100% - (var(--cb)) * 1%);
                --clip-start: calc(var(--ca) * 1%);
                --clip: inset(-20px var(--clip-end) -20px var(--clip-start));
                position: absolute;
                left: var(--start-end);
                right: var(--start-end);
                top: calc(var(--ticks-gap) * var(--flip-y, 0) + var(--thumb-size) / 2 - var(--track-height) / 2);
                height: calc(var(--track-height));
                background: var(--progress-background, #eee);
                pointer-events: none;
                z-index: -1;
                border-radius:
                    var(--progress-radius);
            }

            .range-slider__progress::before {
                content: "";
                position: absolute;
                left: 0;
                right: 0;
                -webkit-clip-path: var(--clip);
                clip-path: var(--clip);
                top: 0;
                bottom: 0;
                background:
                    var(--fill-color, black);
                box-shadow: var(--progress-flll-shadow);
                z-index: 1;
                border-radius: inherit;
            }

            .range-slider__progress::after {
                content: "";
                position: absolute;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                box-shadow: var(--progress-shadow);
                pointer-events: none;
                border-radius: inherit;
            }

            .range-slider>input {
                -webkit-appearance: none;
                width: 100%;
                height: var(--thumb-size);
                margin: 0;
                position: absolute;
                left: 0;
                top: calc(50% - Max(var(--track-height), var(--thumb-size)) / 2 + calc(var(--ticks-gap) / 2 * var(--flip-y,
                                -1)));
                cursor: -webkit-grab;
                cursor: grab;
                outline: none;
                background: none;
            }

            .range-slider>input:not(:only-of-type) {
                pointer-events: none;
            }

            .range-slider>input::-webkit-slider-thumb {
                -webkit-appearance: none;
                appearance: none;
                height: var(--thumb-size);
                width: var(--thumb-size);
                transform: var(--thumb-transform);
                border-radius: var(--thumb-radius, 50%);
                background: var(--thumb-color);
                box-shadow: var(--thumb-shadow);
                border: none;
                pointer-events: auto;
                -webkit-transition: 0.1s;
                transition: 0.1s;
            }

            .range-slider>input::-moz-range-thumb {
                -moz-appearance: none;
                appearance: none;
                height: var(--thumb-size);
                width: var(--thumb-size);
                transform: var(--thumb-transform);
                border-radius: var(--thumb-radius, 50%);
                background: var(--thumb-color);
                box-shadow: var(--thumb-shadow);
                border: none;
                pointer-events: auto;
                -moz-transition: 0.1s;
                transition: 0.1s;
            }

            .range-slider>input::-ms-thumb {
                appearance: none;
                height: var(--thumb-size);
                width: var(--thumb-size);
                transform: var(--thumb-transform);
                border-radius: var(--thumb-radius, 50%);
                background: var(--thumb-color);
                box-shadow: var(--thumb-shadow);
                border: none;
                pointer-events: auto;
                -ms-transition: 0.1s;
                transition: 0.1s;
            }

            .range-slider>input:hover {
                --thumb-shadow: var(--thumb-shadow-hover);
            }

            .range-slider>input:hover+output {
                --value-background: var(--value-background-hover);
                --y-offset: -5px;
                color: var(--value-active-color);
                box-shadow: 0 0 0 3px var(--value-background);
            }

            .range-slider>input:active {
                --thumb-shadow: var(--thumb-shadow-active);
                cursor: -webkit-grabbing;
                cursor: grabbing;
                z-index: 2;
            }

            .range-slider>input:active+output {
                transition: 0s;
            }

            .range-slider>input:nth-of-type(1) {
                --is-left-most: Clamp(0, (var(--value-a) - var(--value-b)) * 99999, 1);
            }

            .range-slider>input:nth-of-type(1)+output {
                --value: var(--value-a);
                --x-offset: calc(var(--completed-a) * -1%);
            }

            .range-slider>input:nth-of-type(1)+output:not(:only-of-type) {
                --flip: calc(var(--thumbs-too-close) * -1);
            }

            .range-slider>input:nth-of-type(1)+output::after {
                content: var(--prefix, "") var(--text-value-a) var(--suffix, "");
            }

            .dark .range-slider>input:nth-of-type(1)+output::after {
                color: #FFF;
            }

            .range-slider>input:nth-of-type(2) {
                --is-left-most: Clamp(0, (var(--value-b) - var(--value-a)) * 99999, 1);
            }

            .range-slider>input:nth-of-type(2)+output {
                --value: var(--value-b);
            }

            .range-slider>input:only-of-type~.range-slider__progress {
                --clip-start: 0;
            }

            .range-slider>input+output {
                --flip: -1;
                --x-offset: calc(var(--completed-b) * -1%);
                --pos: calc(((var(--value) - var(--min)) / (var(--max) - var(--min))) * 100%);
                pointer-events: none;
                position: absolute;
                z-index: 5;
                background: var(--value-background);
                border-radius: 10px;
                padding: 2px 4px;
                left: var(--pos);
                transform: translate(var(--x-offset), calc(150% * var(--flip) - (var(--y-offset, 0px) + var(--value-offset-y)) * var(--flip)));
                transition: all 0.12s ease-out, left 0s;
            }

            .range-slider>input+output::after {
                content: var(--prefix, "") var(--text-value-b) var(--suffix, "");
                font: var(--value-font);
            }

            .dark .range-slider>input+output::after {
                color: #FFF;
            }


            body>.range-slider,
            label[dir=rtl] .range-slider {
                width: clamp(300px, 50vw, 800px);
                min-width: 200px;
            }
        </style>
    @elseif ($filter->getConfig('cssInclude') == 'include')
        @push('styles')
            <link href="{{ asset('css/numberRange.css') }}" rel="stylesheet" />
        @endpush
    @endif

</div>
