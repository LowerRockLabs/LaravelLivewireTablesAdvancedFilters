@php
    $theme = $component->getTheme();
    $tableName = $component->getTableName();
    $filterKey = $filter->getKey();
    $options = [];
    $empty = [];
    $options = $filter->getOptions();
    $filterLabelPath = $tableName . '-filter-' . $filterKey;
    $filterName = $filter->getName();
    $xRefKey = 'slimSelectSearchBox' . $filterKey;
    $smartSelectID = 'slimSelectSearchBox' . $filterKey . '-id';
@endphp

@pushOnce('scripts')
    <script src="https://unpkg.com/slim-select@latest/dist/slimselect.min.js"></script>
    <link href="https://unpkg.com/slim-select@latest/dist/slimselect.css" rel="stylesheet" />
@endPushOnce

<div x-data="{
    allFilters: $wire.entangle('{{ $tableName }}.filters'),

    slimSelect: [],
    booting: true,
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
                            $wire.set('{{ $tableName }}.filters.{{ $filterKey }}', this.slimSelect.getSelected());
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
        $watch('open', value => this.swapLabels());
        $watch('allFilters', value => this.swapLabels());
    }
}">
    @if ($theme === 'tailwind')
        <x-livewiretablesadvancedfilters::elements.labelInternal-tw :filterLabelPath=$filterLabelPath
            :filterName=$filterName />
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
        <x-livewiretablesadvancedfilters::elements.labelInternal-bs :filterLabelPath=$filterLabelPath
            :filterName=$filterName />
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
