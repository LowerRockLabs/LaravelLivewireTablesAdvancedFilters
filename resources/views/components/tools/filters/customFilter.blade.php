@php
    $theme = $component->getTheme();
    $tableName = $component->getTableName();
    $filterKey = $filter->getKey();
    $wireKey = $tableName . '.filters.' . $filterKey;
    $filterLayout = $component->getFilterLayout();

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
            @if($filter->hasCustomFilterLabel())
                @include($filter->getCustomFilterLabel(),['filter' => $filter, 'theme' => $theme, 'filterLayout' => $filterLayout, 'tableName' => $tableName  ])
            @else
                <x-livewire-tables::tools.filter-label :filter="$filter" :theme="$theme" :filterLayout="$filterLayout" :tableName="$tableName" />
            @endif        
            @livewire('test-component')
        </div>
    @endif
</div>
