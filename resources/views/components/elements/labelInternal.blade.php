@props(['theme', 'filterLabelPath', 'filterName'])

@if ($theme == 'tailwind')
    <label for="{{ $filterLabelPath }}" class="hidden block text-sm font-medium leading-5 text-gray-700 dark:text-white"
        id="{{ $filterLabelPath }}-labelInternal">
        {{ $filterName }}
    </label>
@else
    <label for="{{ $filterLabelPath }}" class="hidden block text-sm font-medium leading-5 text-gray-700 dark:text-white"
        id="{{ $filterLabelPath }}-labelInternal">
        {{ $filterName }}
    </label>
@endif
