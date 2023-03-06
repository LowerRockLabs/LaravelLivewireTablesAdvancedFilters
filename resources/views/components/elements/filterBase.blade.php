@props(['tableName', 'filterLabelPath'])

<div x-data="{
    allFilters: $wire.entangle('{{ $tableName }}.filters'),
    swapLabels() {
        if (document.getElementById('{{ $filterLabelPath }}-label') === null) {
            this.parentElement.firstElementChild.classList.add('hidden');
        } else {
            document.getElementById('{{ $filterLabelPath }}-label').classList.add('hidden');
        }
        document.getElementById('{{ $filterLabelPath }}-labelInternal').classList.remove('hidden');

    },
    init() {
        $watch('open', value => this.swapLabels());
        $watch('allFilters', value => this.swapLabels());
    }
}">
    {{ $slot }}

</div>
