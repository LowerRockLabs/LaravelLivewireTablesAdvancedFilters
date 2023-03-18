@php
    $tableName = $component->getTableName();
    $filterKey = $filter->getKey();
    $filterLabelPath = $tableName . '-filter-' . $filterKey;
    $filterBasePath = $tableName . '.filters.' . $filterKey;
    $filterMenuLabel = '[aria-labelledby="filters-menu"]';
    $filterName = $filter->getName();
    $filterConfigs = $filter->getConfigs();
    $customFilterMenuWidth = (!empty($filterConfigs['customFilterMenuWidth']) ? json_encode(explode( " ", $filterConfigs['customFilterMenuWidth'])) : '');

    $options = [];
    $empty = [];
    $options = $filter->getOptions();
    $xRefKey = 'slimSelectSearchBox' . $filterKey;
    $smartSelectID = 'slimSelectSearchBox' . $filterKey . '-id';
    $filterContainerName = "smartSelectContainer";
@endphp
<div id="{{ $filterContainerName }}{{ $filterKey }}" x-data="{
    allFilters: $wire.entangle('{{ $tableName }}.filters'),
    filterMenuClasses: {{ $customFilterMenuWidth }}, 
    @if ($theme == 'tailwind') twMenuElements: document.getElementsByClassName('relative block md:inline-block text-left'), @endif
    @if ($theme === 'bootstrap-4' || $theme === 'bootstrap-5') bsMenuElements: document.getElementsByClassName('btn-group d-block d-md-inline'), @endif
    slimSelect: [],
    booting: true,
    setupFilterMenu() {
        let parentLabelElement = document.getElementById('{{ $filterLabelPath }}-label');
        let currentFilterMenuLabel = document.querySelector('{{ $filterMenuLabel }}');
        let newFilterLabelElement = document.getElementById('{{ $filterLabelPath }}-labelInternal');

        if (currentFilterMenuLabel !== null) {
            this.filterMenuClasses.forEach(item => currentFilterMenuLabel.classList.add(item));
            currentFilterMenuLabel.style.width = '20em !important';
            currentFilterMenuLabel.classList.remove('md:w-56');
        }

        @if ($theme === 'tailwind') 
            if (parentLabelElement === null) {
                let parentLabelContainer = document.getElementById('{{ $filterContainerName }}{{ $filterKey }}').parentElement.firstElementChild;
                if (parentLabelContainer !== null) {
                    parentLabelContainer.classList.add('hidden');
                }
            } else {
                parentLabelElement.classList.add('hidden');
            }

            if (newFilterLabelElement !== null) {
                newFilterLabelElement.classList.remove('hidden');
            }

            for (let i = 0; i < this.twMenuElements.length; i++) {
                if (!this.twMenuElements.item(i).hasAttribute('x-data'))
                {
                    this.twMenuElements.item(i).setAttribute('x-data', '{ open: false, childElementOpen: true  }');
                    this.twMenuElements.item(i).setAttribute('x-on:mousedown.away', 'if (!childElementOpen) { open = false }');
                }
                else if (!this.twMenuElements.item(i).getAttribute('x-data').includes('childElementOpen'))
                {
                    this.twMenuElements.item(i).setAttribute('x-data', '{ open: false, childElementOpen: true  }');
                    this.twMenuElements.item(i).setAttribute('x-on:mousedown.away', 'if (!childElementOpen) { open = false }');
                }
            } 
        @endif

        @if ($theme === 'bootstrap-4' || $theme === 'bootstrap-5') 
            if (parentLabelElement === null) {
                let parentLabelContainer = document.getElementById('{{ $filterContainerName }}{{ $filterKey }}').parentElement.firstElementChild;
                if (parentLabelContainer !== null) {
                    parentLabelContainer.classList.add('d-none');
                }
            } else {
                parentLabelElement.classList.add('d-none');
            }

            if (newFilterLabelElement !== null) {
                newFilterLabelElement.classList.remove('d-none');
            }

            for (let i = 0; i < this.bsMenuElements.length; i++) {
                if (!this.bsMenuElements.item(i).hasAttribute('x-data'))
                {
                    this.bsMenuElements.item(i).setAttribute('x-data', '{ open: false, childElementOpen: false  }');
                    this.bsMenuElements.item(i).setAttribute('x-on:mousedown.away', 'if (!childElementOpen) { open = false }');
                }
                else
                {
                    if (!this.bsMenuElements.item(i).getAttribute('x-data').includes('childElementOpen'))
                    {
                        this.bsMenuElements.item(i).setAttribute('x-data', '{ open: false, childElementOpen: false  }');
                        this.bsMenuElements.item(i).setAttribute('x-on:mousedown.away', 'if (!childElementOpen) { open = false }');
                    }
                }
            } 
        @endif
    },
    bootSlimSelect() {
        this.slimSelect = new SlimSelect({
            select: '#{{ $smartSelectID }}',
            settings: {
                placeholderText: 'Select Values',
                allowDeselect: true
            },
            events: {
                afterChange: (newVal) => {
                    if (!this.booting) {
                        if (this.slimSelect.getSelected().length > 0) {
                            $wire.set('{{ $filterBasePath }}', this.slimSelect.getSelected());
                        }
                    } else {
                        this.booting = false;
                    }

                }
            }
        });
    },
    updateSlimSelect() {
        if (this.slimSelect.length == 0) {
            this.bootSlimSelect();
            this.slimSelect.setData({{ json_encode($filter->getOptions()) }});
        } else {
            this.slimSelect.setData({{ json_encode($filter->getOptions()) }});
        }

    },
    init() {
        this.booting = true;
        this.updateSlimSelect()
        this.setupFilterMenu();
        $watch('allFilters', value => this.setupFilterMenu());
    }
}">
    @pushOnce('scripts')
        <script src="https://unpkg.com/slim-select@latest/dist/slimselect.min.js"></script>
    @endPushOnce
    @pushOnce('styles')
        <link href="https://unpkg.com/slim-select@latest/dist/slimselect.css" rel="stylesheet" />
    @endPushOnce
    @if ($theme === 'tailwind')
        <x-livewiretablesadvancedfilters::elements.labelInternal :theme="$theme" :filterLabelPath="$filterLabelPath"
            :filterName="$filterName" />
        <div wire:ignore wire:key>
            <div wire:key class="rounded-md shadow-sm">

            </div>
            <div wire:key>
                <select multiple xx-ref="{{ $xRefKey }}" id="{{ $smartSelectID }}" wire:key>
                    <option data-placeholder="true"></option>
                </select>
            </div>
        </div>
    @elseif ($theme === 'bootstrap-4' || $theme === 'bootstrap-5')
        <x-livewiretablesadvancedfilters::elements.labelInternal :theme="$theme" :filterLabelPath="$filterLabelPath"
            :filterName="$filterName" />
        <div wire:ignore wire:key>
            <div wire:key class="rounded-md shadow-sm">

            </div>
            <div wire:key>
                <select x-ref="{{ $xRefKey }}" id="{{ $smartSelectID }}" multiple wire:key
                    class="{{ $theme === 'bootstrap-4' ? 'form-control' : 'form-select' }}">
                    <option data-placeholder="true"></option>
                </select>
            </div>
        </div>
    @endif
</div>
