@props(['theme', 'filterKey', 'dateString', 'filterLabelPath', 'filterBasePath'])

@if ($theme == 'tailwind')
    <input type="text" x-ref="datePickerInput{{ $filterKey }}" wire:key="{{ $filterLabelPath }}"
        id="{{ $filterLabelPath }}" value="{{ $dateString }}"
        class="w-full inline transition duration-150 ease-in-out border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:text-white dark:border-gray-600" />
@else
    <input type="text" x-ref="datePickerInput{{ $filterKey }}" wire:key="{{ $filterLabelPath }}"
        id="{{ $filterLabelPath }}" value="{{ $dateString }}"
        class="d-inline-block form-control w-100 pr-2 transition duration-150 ease-in-out border border-gray rounded-sm shadow-sm
        focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:text-white dark:border-gray-600" />
@endif
