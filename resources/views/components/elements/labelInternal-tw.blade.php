@props(['filterLabelPath', 'filterName'])
<label for="{{ $filterLabelPath }}" class="hidden block text-sm font-medium leading-5 text-gray-700 dark:text-white"
    id="{{ $filterLabelPath }}-labelInternal">
    {{ $filterName }}
</label>
