@props(['theme', 'xRefKey'])

@if ($theme == 'tailwind')
    <input x-on:keyup="updateCurrentFilteredList" type="text" x-ref="{{ $xRefKey }}" placeholder="Search Here..."
        class=" w-full mr-4	border-gray-300 rounded-md dark:bg-gray-800  dark:border-gray-600" />
@else
    <input x-on:keyup="updateCurrentFilteredList" type="text" x-ref="{{ $xRefKey }}" placeholder="Search Here..."
        class=" w-100	border border-gray rounded-sm dark:bg-gray-800  dark:border-gray-600" />
@endif
