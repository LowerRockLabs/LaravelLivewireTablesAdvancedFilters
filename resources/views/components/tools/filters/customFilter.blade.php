@php
    $theme = $component->getTheme();
    $tableName = $component->getTableName();
    $filterKey = $filter->getKey();
    $wireKey = $tableName . '.filters.' . $filterKey;

@endphp

<div x-data="{
    allFilters: $wire.entangle('{{ $tableName }}.filters'),
    selectedItems: $wire.entangle('{{ $wireKey }}'),
    items: $refs.testComponent,
    updateItems() {
        var result = [];
        var options = this.items.options;
        var opt;
        for (var i = 0, iLen = options.length; i < iLen; i++) {
            opt = options[i];

            if (opt.selected) {
                result.push(opt.value);
            }
        }
        this.selectedItems = result;
    }
}">
    @if ($theme === 'tailwind')
        <div>

            <label for="{{ $tableName }}-filter-{{ $filterKey }}"
                class="block text-sm font-medium leading-5 text-gray-700 dark:text-white">
                {{ $filter->getName() }}
            </label>
            @livewire('test-component')
        </div>
    @endif
</div>
