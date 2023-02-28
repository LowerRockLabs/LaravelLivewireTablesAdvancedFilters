@php
    $theme = $component->getTheme();
    $filterKey = $component->getTableName() . '.filters.' . $filter->getKey();
    $selectedItems = !empty($component->getAppliedFilterWithValue($filter->getKey())) ? $component->getAppliedFilterWithValue($filter->getKey()) : [];
@endphp

@if ($theme === 'tailwind')
    <div class="h-12 -mr-4 relative" x-data="{
        search: '',
        smartSelectOpen: false,
        checkedItems: [],
        currentFilteredList: [],
        filteredList: [],
        tmp: {{ json_encode($selectedItems) }},
        selectedItems: $wire.entangle('{{ $filterKey }}'),
        items: {{ json_encode($filter->getOptions()) }},
        setupCurrentFilteredList() {
            this.filteredList = Array.from(new Set({{ json_encode($filter->getOptions()) }}));
            updateCurrentFilteredList();
        },
        updateCurrentFilteredList() {
            if (this.filteredList.length === 0) {
                this.filteredList = Array.from(new Set({{ json_encode($filter->getOptions()) }}));
            }
    
            if ($refs.smartSelectSearchBox.value != '') {
                this.currentFilteredList = this.filteredList.filter(
                    i => i.name.toLowerCase().startsWith($refs.smartSelectSearchBox.value)
                );
            }
        },
        getCurrentFilteredList() {
            if (this.filteredList.length === 0) {
                this.filteredList = Array.from(new Set({{ json_encode($filter->getOptions()) }}));
            }
            return this.filteredList.filter(
                i => i.name.toLowerCase().startsWith($refs.smartSelectSearchBox.value)
            );
        },
        pushToSelected(itemID) {
    
            var index = this.selectedItems.indexOf(itemID);
            if (index !== -1) {
                this.currentFilteredList = []
                $refs.smartSelectSearchBox.value = '';
                this.selectedItems.splice(index, 1);
    
            } else {
                this.currentFilteredList = []
                $refs.smartSelectSearchBox.value = '';
                this.selectedItems.push(itemID);
    
            }
    
        },
        delToSelected(itemID) {
            var index = this.selectedItems.indexOf(itemID);
    
            this.currentFilteredList = []
            $refs.smartSelectSearchBox.value = '';
    
            if (index !== -1) {
                this.selectedItems.splice(index, 1);
            }
        },
        init() {
            if (this.filteredList.length === 0) {
                this.filteredList = Array.from(new Set(this.items));
            }
            this.currentFilteredList = [];
        },
    }">
        <div class=" w-full flex flex-col items-center justify-center">
            <input x-on:keyup="updateCurrentFilteredList" type="text" x-ref="smartSelectSearchBox"
                placeholder="Search Here..."
                class=" w-full mr-4	border-gray-300 rounded-md dark:bg-gray-800 dark:text-white dark:border-gray-600">
            <div class="flex-col w-full pr-4 ">
                <ul class="bg-white dark:bg-darker flex-col w-full z-50">
                    <template x-for="filteredItem in currentFilteredList" :key="filteredItem.id">
                        <li
                            class="cursor-pointer w-full flex text-gray-700 px-2 mt-2 text-black dark:text-white bg-white hover:bg-sky-700 dark:bg-darker hover:dark:bg-sky-700">

                            <template x-if="selectedItems.indexOf(filteredItem.id) > -1">
                                <span class="cursor-pointer" x-on:click="delToSelected(filteredItem.id)">
                                    <svg fill="#000000" height="1.5em" width="1.5em" version="1.1" id="Layer_1"
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        viewBox="0 0 297 297" xml:space="preserve">
                                        <g>
                                            <g>
                                                <g>
                                                    <path
                                                        d="M150.333,203.762c0-32.35,26.317-58.667,58.667-58.667c6.527,0,12.8,1.087,18.669,3.063l4.882-58.587H47.163
                                           l14.518,174.21C63.233,282.408,79.091,297,97.784,297h84.147c18.692,0,34.551-14.592,36.103-33.219l0.173-2.081
                                           c-3.001,0.475-6.075,0.729-9.207,0.729C176.651,262.429,150.333,236.112,150.333,203.762z" />
                                                    <path
                                                        d="M209,158.714c-24.839,0-45.048,20.209-45.048,45.048c0,24.839,20.209,45.048,45.048,45.048s45.048-20.209,45.048-45.048
                                           C254.048,178.923,233.839,158.714,209,158.714z M231.101,216.232c2.659,2.66,2.659,6.971,0,9.631
                                           c-1.33,1.329-3.073,1.994-4.816,1.994c-1.742,0-3.486-0.665-4.816-1.994L209,213.393l-12.47,12.47
                                           c-1.33,1.329-3.073,1.994-4.816,1.994c-1.742,0-3.486-0.665-4.816-1.994c-2.659-2.66-2.659-6.971,0-9.631l12.47-12.47
                                           l-12.47-12.47c-2.659-2.66-2.659-6.971,0-9.631c2.66-2.658,6.971-2.658,9.631,0l12.47,12.47l12.47-12.47
                                           c2.661-2.658,6.972-2.658,9.632,0c2.659,2.66,2.659,6.971,0,9.631l-12.47,12.47L231.101,216.232z" />
                                                    <path
                                                        d="M112.095,26.102c0-6.883,5.6-12.483,12.483-12.483h30.556c6.884,0,12.484,5.6,12.484,12.483v8.507h13.619v-8.507
                                           C181.238,11.71,169.528,0,155.135,0h-30.556c-14.392,0-26.102,11.71-26.102,26.102v8.507h13.618V26.102z" />
                                                    <path
                                                        d="M236.762,63.643c0-8.5-6.915-15.415-15.415-15.415H58.367c-8.5,0-15.415,6.915-15.415,15.415v12.31h193.81V63.643z" />
                                                </g>
                                            </g>
                                        </g>
                                    </svg>

                                </span>
                            </template>
                            <template x-if="selectedItems.indexOf(filteredItem.id) < 0">
                                <span class="cursor-pointer" x-on:click="pushToSelected(filteredItem.id)">
                                    <svg fill="#000000" height="1.5em" width="1.5em" version="1.1" id="Layer_1"
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        viewBox="0 0 297 297" xml:space="preserve">
                                        <g>
                                            <g>
                                                <g>
                                                    <path
                                                        d="M182.413,170.368c-11.317,0-22.632,4.307-31.248,12.922c-17.23,17.231-17.23,45.265,0,62.496
				c17.232,17.23,45.264,17.23,62.496,0c17.23-17.231,17.23-45.265,0-62.496C205.046,174.676,193.73,170.368,182.413,170.368z
				 M206.394,221.219h-17.301v17.301c0,3.689-2.991,6.68-6.68,6.68c-3.689,0-6.68-2.991-6.68-6.68v-17.301h-17.301
				c-3.689,0-6.68-2.991-6.68-6.68c0-3.689,2.991-6.68,6.68-6.68h17.301v-17.301c0-3.689,2.991-6.68,6.68-6.68
				c3.689,0,6.68,2.991,6.68,6.68v17.301h17.301c3.689,0,6.68,2.991,6.68,6.68C213.074,218.228,210.084,221.219,206.394,221.219z" />
                                                    <path
                                                        d="M227.289,92.758c-2.303,0-4.62,0.117-6.931,0.349c-8.746-32.121-38.001-54.816-71.858-54.816
				S85.388,60.986,76.642,93.108c-2.311-0.232-4.628-0.349-6.931-0.349C31.272,92.758,0,124.03,0,162.469
				c0,38.439,31.272,69.712,69.711,69.712h57.948c-6.372-19.889-1.696-42.58,14.06-58.337c22.44-22.438,58.949-22.438,81.39,0
				c15.556,15.557,20.318,37.875,14.304,57.58C271.068,226.506,297,197.468,297,162.469C297,124.03,265.728,92.758,227.289,92.758z" />
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </span>
                            </template>

                            <span class="cursor-pointer" x-on:click="pushToSelected(filteredItem.id)">

                                <span x-text="filteredItem.name"></span>
                            </span>
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
