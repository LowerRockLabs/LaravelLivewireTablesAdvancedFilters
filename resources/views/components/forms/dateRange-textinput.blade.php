@props(['theme', 'filterKey', 'dateString', 'filterLabelPath'])

<input type="text" x-ref="dateRangeInput{{ $filterKey }}" x-on:click="childElementOpen = true"
    value="{{ $dateString }}" wire:key="{{ $filterLabelPath }}" id="{{ $filterLabelPath }}"
    class="w-full inline transition duration-150 ease-in-out border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:text-white dark:border-gray-600" />
