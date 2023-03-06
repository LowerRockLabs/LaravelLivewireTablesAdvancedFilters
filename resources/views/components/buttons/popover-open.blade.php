@props(['theme'])
@if ($theme == 'tailwind')
    <button @click="popOpen = ! popOpen" type="button"
        class="inline bg-white w-4 h-6 rounded-full text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-200 z-0"
        id="menu-button" aria-expanded="true" aria-haspopup="true">
        <span class="sr-only">Open options</span>
        <!-- Heroicon name: solid/dots-vertical -->
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
        </svg>
    </button>
@else
    <button @click="popOpen = ! popOpen" type="button"
        class="inline bg-white w-4 h-6 rounded-full text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-200 z-0"
        id="menu-button" aria-expanded="true" aria-haspopup="true">
        <span class="sr-only">Open options</span>
        <!-- Heroicon name: solid/dots-vertical -->
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
        </svg>
    </button>
@endif
