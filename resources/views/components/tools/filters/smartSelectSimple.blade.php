@php
    $theme = $component->getTheme();
    $filterKey = $component->getTableName() . '.filters.' . $filter->getKey();
    $xRefKey = 'smartSelectSearchBox' . $filter->getKey();
    $configs = $filter->getConfigs();
    $iconStyling = $configs['iconStyling'];
    $listStyling = $configs['listStyling'];
    
@endphp

@if ($theme === 'tailwind')
    <div class="h-12 -mr-4 relative" x-data="{
        displayIdEnabled: true,
        currentFilteredList: [],
        filteredList: [],
        selectedItems: $wire.entangle('{{ $filterKey }}'),
        resetCurrentFilteredList() {
            $refs.{{ $xRefKey }}.value = '';
            this.currentFilteredList = [];
        },
        updateCurrentFilteredList() {
            currentlyFilteredObject = [];
            if ($refs.{{ $xRefKey }}.value != '') {
                this.filteredList.filter(function(elem, index) {
                    if (elem.toLowerCase().includes($refs.{{ $xRefKey }}.value.toLowerCase())) {
                        currentlyFilteredObject.push({ 'id': index, 'name': elem });
                    }
                    return true;
                });
                this.currentFilteredList = currentlyFilteredObject;
            } else {
                this.currentFilteredList = [];
            }
        },
        addSelectedItem(itemID) {
            var index = this.selectedItems.indexOf(itemID);
            if (index !== -1) {
                $refs.{{ $xRefKey }}.value = ''
            } else {
                $refs.{{ $xRefKey }}.value = '';
                this.selectedItems.push(itemID.toString())
            }
        },
        removeSelectedItem(itemID) {
            var index = this.selectedItems.indexOf(itemID);
            this.currentFilteredList = []
            $refs.{{ $xRefKey }}.value = '';
            while (index !== -1) {
                this.selectedItems.splice(index, 1);
                index = this.selectedItems.indexOf(itemID)
            }
        },
        init() {
            this.currentFilteredList = []
            var testObject = Object.entries({{ json_encode($filter->getOptions()) }}).map((entry) => this.filteredList[entry[0]] = entry[1]);
        },
    }">

        <!-- Start Graph API Pin -->
        <div class="w-full rounded-md relative">
            <div x-data="{ popOpen: false }" x-cloak
                class="w-full inline-flex place-items-end justify-items-end items-end pr-2">
                <div class="w-8 absolute -top-8 right-0 ">
                    <button @click="popOpen = ! popOpen" type="button"
                        class="inline bg-white w-4 rounded-full items-center text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-200 z-0"
                        id="menu-button" aria-expanded="true" aria-haspopup="true">
                        <span class="sr-only">Open options</span>
                        <!-- Heroicon name: solid/dots-vertical -->
                        <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            aria-hidden="true">
                            <path
                                d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                        </svg>
                    </button>
                </div>

                <div x-show="popOpen" @click.outside="popOpen = false" x-transition
                    class="origin-top-right left-56 absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                    role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                    <div class="pt-1 z-50">
                        <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
                        <a href="#" x-on:click="popOpen = false"
                            class="text-gray-700 inline-block w-full text-right px-4 py-2 text-lg" role="menuitem"
                            tabindex="-1" id="menu-item-0">
                            <svg class="inline" width="2em" height="2em" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect width="24" height="24" fill="white" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M14 7C14 6.44772 13.5523 6 13 6C12.4477 6 12 6.44772 12 7V8V10.5C12 11.3284 12.6716 12 13.5 12H16H17C17.5523 12 18 11.5523 18 11C18 10.4477 17.5523 10 17 10H16L14 10V8V7ZM6 13C6 13.5523 6.44772 14 7 14H8H9.99991L9.99997 16L10 17C10 17.5523 10.4477 18 11 18C11.5523 18 12 17.5523 12 17L12 16L11.9999 13.5C11.9999 12.6719 11.3287 12 10.5 12H8H7C6.44771 12 6 12.4477 6 13ZM2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12Z"
                                    fill="#323232" />
                            </svg>


                        </a>
                        <div class="flex flex-col gap-y-2">
                            <template x-for="(selectedItemID,index) in selectedItems">
                                <div class="w-full pl-2 text-sm">
                                    <a href="#" x-on:click="removeSelectedItem(selectedItemID)">
                                        <svg @class([
                                            'inline-block' => $iconStyling['delete']['defaults'],
                                            $iconStyling['delete']['classes'],
                                        ])
                                            width="{{ $iconStyling['delete']['svgSize'] }}"
                                            height="{{ $iconStyling['delete']['svgSize'] }}"
                                            fill="{{ $iconStyling['delete']['svgFill'] }}" version="1.1"
                                            viewBox="0 0 297 297" xml:space="preserve"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="m150.33 203.76c0-32.35 26.317-58.667 58.667-58.667 6.527 0 12.8 1.087 18.669 3.063l4.882-58.587h-185.39l14.518 174.21c1.552 18.627 17.41 33.219 36.103 33.219h84.147c18.692 0 34.551-14.592 36.103-33.219l0.173-2.081c-3.001 0.475-6.075 0.729-9.207 0.729-32.349 0-58.667-26.317-58.667-58.667z" />
                                            <path
                                                d="m209 158.71c-24.839 0-45.048 20.209-45.048 45.048s20.209 45.048 45.048 45.048 45.048-20.209 45.048-45.048-20.209-45.048-45.048-45.048zm22.101 57.518c2.659 2.66 2.659 6.971 0 9.631-1.33 1.329-3.073 1.994-4.816 1.994-1.742 0-3.486-0.665-4.816-1.994l-12.469-12.47-12.47 12.47c-1.33 1.329-3.073 1.994-4.816 1.994-1.742 0-3.486-0.665-4.816-1.994-2.659-2.66-2.659-6.971 0-9.631l12.47-12.47-12.47-12.47c-2.659-2.66-2.659-6.971 0-9.631 2.66-2.658 6.971-2.658 9.631 0l12.47 12.47 12.47-12.47c2.661-2.658 6.972-2.658 9.632 0 2.659 2.66 2.659 6.971 0 9.631l-12.47 12.47 12.47 12.47z" />
                                            <path
                                                d="m112.1 26.102c0-6.883 5.6-12.483 12.483-12.483h30.556c6.884 0 12.484 5.6 12.484 12.483v8.507h13.619v-8.507c1e-3 -14.392-11.709-26.102-26.102-26.102h-30.556c-14.392 0-26.102 11.71-26.102 26.102v8.507h13.618v-8.507z" />
                                            <path
                                                d="m236.76 63.643c0-8.5-6.915-15.415-15.415-15.415h-162.98c-8.5 0-15.415 6.915-15.415 15.415v12.31h193.81v-12.31z" />
                                        </svg>
                                    </a>
                                    <span x-text="filteredList[Number(selectedItemID)]"></span>

                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Stop Graph API Pin -->
        <div class=" w-full flex flex-col items-center justify-center ">
            <input x-on:keyup="updateCurrentFilteredList" type="text" x-ref="{{ $xRefKey }}"
                placeholder="Search Here..."
                class=" w-full mr-4	border-gray-300 rounded-md dark:bg-gray-800 dark:text-white dark:border-gray-600">
            <div class="flex-col w-full pr-4 overflow-visible z-50 ">
                <ul class="bg-white dark:bg-darker flex-col w-full">
                    <template x-for="filteredItem in currentFilteredList" :key="filteredItem.id">
                        <li @class([
                            'cursor-pointer w-full flex text-gray-700 px-2 mt-2 text-black dark:text-white bg-white hover:bg-sky-700 dark:bg-darker hover:dark:bg-sky-700' =>
                                $listStyling['defaults'],
                            $listStyling['classes'],
                        ])>
                            <template x-if="selectedItems.indexOf(Number(filteredItem.id)) > -1">
                                <a class="cursor-pointer" x-on:click="removeSelectedItem(filteredItem.id)">
                                    @if ($iconStyling['delete']['svgEnabled'])
                                        <svg @class([
                                            'inline-block' => $iconStyling['delete']['defaults'],
                                            $iconStyling['delete']['classes'],
                                        ])
                                            width="{{ $iconStyling['delete']['svgSize'] }}"
                                            height="{{ $iconStyling['delete']['svgSize'] }}"
                                            fill="{{ $iconStyling['delete']['svgFill'] }}" version="1.1"
                                            viewBox="0 0 297 297" xml:space="preserve"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="m150.33 203.76c0-32.35 26.317-58.667 58.667-58.667 6.527 0 12.8 1.087 18.669 3.063l4.882-58.587h-185.39l14.518 174.21c1.552 18.627 17.41 33.219 36.103 33.219h84.147c18.692 0 34.551-14.592 36.103-33.219l0.173-2.081c-3.001 0.475-6.075 0.729-9.207 0.729-32.349 0-58.667-26.317-58.667-58.667z" />
                                            <path
                                                d="m209 158.71c-24.839 0-45.048 20.209-45.048 45.048s20.209 45.048 45.048 45.048 45.048-20.209 45.048-45.048-20.209-45.048-45.048-45.048zm22.101 57.518c2.659 2.66 2.659 6.971 0 9.631-1.33 1.329-3.073 1.994-4.816 1.994-1.742 0-3.486-0.665-4.816-1.994l-12.469-12.47-12.47 12.47c-1.33 1.329-3.073 1.994-4.816 1.994-1.742 0-3.486-0.665-4.816-1.994-2.659-2.66-2.659-6.971 0-9.631l12.47-12.47-12.47-12.47c-2.659-2.66-2.659-6.971 0-9.631 2.66-2.658 6.971-2.658 9.631 0l12.47 12.47 12.47-12.47c2.661-2.658 6.972-2.658 9.632 0 2.659 2.66 2.659 6.971 0 9.631l-12.47 12.47 12.47 12.47z" />
                                            <path
                                                d="m112.1 26.102c0-6.883 5.6-12.483 12.483-12.483h30.556c6.884 0 12.484 5.6 12.484 12.483v8.507h13.619v-8.507c1e-3 -14.392-11.709-26.102-26.102-26.102h-30.556c-14.392 0-26.102 11.71-26.102 26.102v8.507h13.618v-8.507z" />
                                            <path
                                                d="m236.76 63.643c0-8.5-6.915-15.415-15.415-15.415h-162.98c-8.5 0-15.415 6.915-15.415 15.415v12.31h193.81v-12.31z" />
                                        </svg>
                                    @endif
                                    <span class="smartSelect-NameDisplay-Wrapper">
                                        <template x-if="displayIdEnabled">
                                            <span class="smartSelect-NameDisplay-ID">
                                                &#40;<span x-text="filteredItem.id"></span>&#41;
                                            </span>
                                        </template>
                                        <span class="smartSelect-NameDisplay-Name" x-text="filteredItem.name"></span>
                                    </span>
                                </a>
                            </template>
                            <template x-if="selectedItems.indexOf(Number(filteredItem.id)) < 0">
                                <a class="cursor-pointer" x-on:click="addSelectedItem(filteredItem.id)">
                                    @if ($iconStyling['add']['svgEnabled'])
                                        <svg @class([
                                            'inline-block' => $iconStyling['add']['defaults'],
                                            $iconStyling['add']['classes'],
                                        ]) width="{{ $iconStyling['add']['svgSize'] }}"
                                            height="{{ $iconStyling['add']['svgSize'] }}"
                                            fill="{{ $iconStyling['add']['svgFill'] }}" version="1.1"
                                            viewBox="0 0 297 297" xml:space="preserve"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="m182.41 170.37c-11.317 0-22.632 4.307-31.248 12.922-17.23 17.231-17.23 45.265 0 62.496 17.232 17.23 45.264 17.23 62.496 0 17.23-17.231 17.23-45.265 0-62.496-8.615-8.614-19.931-12.922-31.248-12.922zm23.981 50.851h-17.301v17.301c0 3.689-2.991 6.68-6.68 6.68s-6.68-2.991-6.68-6.68v-17.301h-17.301c-3.689 0-6.68-2.991-6.68-6.68s2.991-6.68 6.68-6.68h17.301v-17.301c0-3.689 2.991-6.68 6.68-6.68s6.68 2.991 6.68 6.68v17.301h17.301c3.689 0 6.68 2.991 6.68 6.68s-2.99 6.68-6.68 6.68z" />
                                            <path
                                                d="m227.29 92.758c-2.303 0-4.62 0.117-6.931 0.349-8.746-32.121-38.001-54.816-71.858-54.816s-63.112 22.695-71.858 54.817c-2.311-0.232-4.628-0.349-6.931-0.349-38.439-1e-3 -69.711 31.271-69.711 69.71s31.272 69.712 69.711 69.712h57.948c-6.372-19.889-1.696-42.58 14.06-58.337 22.44-22.438 58.949-22.438 81.39 0 15.556 15.557 20.318 37.875 14.304 57.58 33.655-4.918 59.587-33.956 59.587-68.955 0-38.439-31.272-69.711-69.711-69.711z" />
                                        </svg>
                                    @endif
                                    <span class="smartSelect-NameDisplay-Wrapper">
                                        <template x-if="displayIdEnabled">
                                            <span class="smartSelect-NameDisplay-ID">
                                                &#40;<span x-text="filteredItem.id"></span>&#41;
                                            </span>
                                        </template>
                                        <span class="smartSelect-NameDisplay-Name" x-text="filteredItem.name"></span>
                                    </span>
                                </a>
                            </template>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </div>
@elseif ($theme === 'bootstrap-4' || $theme === 'bootstrap-5')
    <div class="mb-3 mb-md-0 input-group">
        <input wire:model.stop="{{ $component->getTableName() }}.filters.{{ $filter->getKey() }}"
            wire:key="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
            id="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}" type="text"
            @if ($filter->hasConfig('placeholder')) placeholder="{{ $filter->getConfig('placeholder') }}" @endif
            @if ($filter->hasConfig('maxlength')) maxlength="{{ $filter->getConfig('maxlength') }}" @endif
            class="form-control" />
    </div>
@endif
