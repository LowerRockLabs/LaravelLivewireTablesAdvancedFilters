@php
    $theme = $component->getTheme();
    $tableName = $component->getTableName();
    $filterKey = $filter->getKey();
    
    $currentMin = $filterMin = $filter->getConfig('minRange');
    $currentMax = $filterMax = $filter->getConfig('maxRange');
    
    $tableName = $tableName;
    $filterBasePath = $tableName . '.filters.' . $filterKey;
    $minFilterWirePath = $filterBasePath . '.min';
    $maxFilterWirePath = $filterBasePath . '.max';
    
    if (isset($this->{$tableName}['filters'])) {
        if (isset($this->{$tableName}['filters'][$filterKey])) {
            $currentMin = isset($this->{$tableName}['filters'][$filterKey]['min']) ? $this->{$tableName}['filters'][$filterKey]['min'] : $filterMin;
            $currentMax = isset($this->{$tableName}['filters'][$filterKey]['max']) ? $this->{$tableName}['filters'][$filterKey]['max'] : $filterMax;
        }
    }
    $lightStyling = $filter->getConfig('styling')['light'];
    $darkStyling = $filter->getConfig('styling')['dark'];
@endphp

<div>
    @if ($theme === 'tailwind')
        <label for="{{ $tableName }}-filter-{{ $filterKey }}"
            class="block text-sm font-medium leading-5 text-gray-700 dark:text-white">
            {{ $filter->getName() }}
        </label>
        <div class="mt-4 h-22 pt-8 pb-4 grid gap-10">
            <div class="range-slider flat" data-ticks-position='bottom'
                style='--min:{{ $filterMin }}; --max:{{ $filterMax }}; --value-a:{{ $currentMax }}; --value-b:{{ $currentMin }}; --suffix:"%"; --text-value-a:"{{ $currentMax }}"; --text-value-b:"{{ $currentMin }}";'>

                <input type="range" min="{{ $filterMin }}" max="{{ $filterMax }}" value={{ $currentMax }}
                    wire:model="{{ $maxFilterWirePath }}"
                    onchange="this.parentNode.style.setProperty('--value-a',this.value); this.parentNode.style.setProperty('--text-value-a', JSON.stringify(this.value))">
                <output></output>
                <input type="range" min="{{ $filterMin }}" max="{{ $filterMax }}" value="{{ $currentMin }}"
                    wire:model="{{ $minFilterWirePath }}"
                    onchange="this.parentNode.style.setProperty('--value-b',this.value); this.parentNode.style.setProperty('--text-value-b', JSON.stringify(this.value));">
                <output></output>
                <div class='range-slider__progress'></div>
            </div>
        </div>
    @elseif ($theme === 'bootstrap-4' || $theme === 'bootstrap-5')
        <label for="{{ $tableName }}-filter-{{ $filterKey }}"
            class="block text-sm font-medium leading-5 text-gray-700 dark:text-white">
            {{ $filter->getName() }}
        </label>
        <div class="w-100 mt-4 h-22 pt-8 pb-4 grid gap-10">
            <div class="range-slider flat w-100" data-ticks-position='bottom'
                style='--min:{{ $filterMin }}; --max:{{ $filterMax }}; --value-a:{{ $currentMax }}; --value-b:{{ $currentMin }}; --suffix:"%"; --text-value-a:"{{ $currentMax }}"; --text-value-b:"{{ $currentMin }}";'>

                <input type="range" min="{{ $filterMin }}" max="{{ $filterMax }}"
                    wire:model="{{ $maxFilterWirePath }}"
                    onchange="this.parentNode.style.setProperty('--value-a',this.value); this.parentNode.style.setProperty('--text-value-a', JSON.stringify(this.value))">
                <output></output>
                <input type="range" min="{{ $filterMin }}" max="{{ $filterMax }}"
                    wire:model="{{ $minFilterWirePath }}"
                    onchange="this.parentNode.style.setProperty('--value-b',this.value); this.parentNode.style.setProperty('--text-value-b', JSON.stringify(this.value));">
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
