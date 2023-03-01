@php
    $theme = $component->getTheme();
    $filterKey = $component->getTableName() . '.filters.' . $filter->getKey();
    $xRefKey = 'smartSelectSearchBox' . $filter->getKey();
    $svgEnabled = $filter->getConfig('svgEnabled');
    $iconStyling = $filter->getConfig('iconStyling');
    $listStyling = $filter->getConfig('listStyling');
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
            if ($refs.{{ $xRefKey }}.value != '') {
                this.currentFilteredList = this.filteredList.filter(i => i.name.toLowerCase().includes($refs.{{ $xRefKey }}.value.toLowerCase()))
            } else {
                this.currentFilteredList = [];
            }
        },
        addSelectedItem(itemID) {
            var index = this.selectedItems.indexOf(itemID);
            if (index !== -1) {
                $refs.{{ $xRefKey }}.value = ''
                this.currentFilteredList = []
            } else {
                $refs.{{ $xRefKey }}.value = '';
                this.currentFilteredList = []
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
            this.currentFilteredList = [];
            var testObject = Object.entries({{ json_encode($filter->getOptions()) }}).map((entry) => this.filteredList[entry[0]] = entry[1]);
            this.filteredList = Array.from(new Set({{ json_encode($filter->getOptions()) }}));
        },
    }">
        <div class=" w-full flex flex-col items-center justify-center">
            <input x-on:keyup="updateCurrentFilteredList" type="text" x-ref="{{ $xRefKey }}"
                placeholder="Search Here..."
                class=" w-full mr-4	border-gray-300 rounded-md dark:bg-gray-800 dark:text-white dark:border-gray-600">
            <div class="flex-col w-full pr-4 overflow-visible z-50">
                <ul class="bg-white dark:bg-darker flex-col w-full">
                    <template x-for="filteredItem in currentFilteredList" :key="filteredItem.id">
                        <li @class([
                            'cursor-pointer w-full flex text-gray-700 px-2 mt-2 text-black dark:text-white bg-white hover:bg-sky-700 dark:bg-darker hover:dark:bg-sky-700' =>
                                $listStyling['defaults'],
                            $listStyling['classes'],
                        ])>
                            <template x-if="selectedItems.indexOf(Number(filteredItem.id)) > -1">
                                <a class="cursor-pointer" x-on:click="removeSelectedItem(filteredItem.id.toString())">
                                    @if ($iconStyling['delete']['svgEnabled'])
                                        <svg @class([
                                            'inline-block' => $iconStyling['delete']['defaults'],
                                            $iconStyling['delete']['classes'],
                                        ]) width="1.5em" height="1.5em" fill="#000000"
                                            version="1.1" viewBox="0 0 297 297" xml:space="preserve"
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
                                                (<span x-text="filteredItem.id"></span>)
                                            </span>
                                        </template>
                                        <span class="smartSelect-NameDisplay-Name" x-text="filteredItem.name"></span>
                                    </span>
                                </a>
                            </template>
                            <template x-if="selectedItems.indexOf(Number(filteredItem.id)) < 0">
                                <a class="cursor-pointer" x-on:click="addSelectedItem(filteredItem.id.toString())">
                                    @if ($iconStyling['add']['svgEnabled'])
                                        <svg @class([
                                            'inline-block' => $iconStyling['add']['defaults'],
                                            $iconStyling['add']['classes'],
                                        ]) width="1.5em" height="1.5em" fill="#000000"
                                            version="1.1" viewBox="0 0 297 297" xml:space="preserve"
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
                                                (<span x-text="filteredItem.id"></span>)
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
