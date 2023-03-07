@props(['theme', 'filterKey', 'dateString', 'filterLabelPath'])
@if ($theme == 'tailwind')
    <input type="text" x-ref="dateRangeInput{{ $filterKey }}" x-on:click="childElementOpen = true"
        value="{{ $dateString }}" wire:key="{{ $filterLabelPath }}" id="{{ $filterLabelPath }}"
        class="w-full inline transition duration-150 ease-in-out border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:text-white dark:border-gray-600" />
@else
    <input type="text" x-ref="dateRangeInput{{ $filterKey }}" x-on:click="childElementOpen = true"
        value="{{ $dateString }}" wire:key="{{ $filterLabelPath }}" id="{{ $filterLabelPath }}"
        class="d-inline-block form-control w-100 pr-2 transition duration-150 ease-in-out border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:text-white dark:border-gray-600" />
@endif
