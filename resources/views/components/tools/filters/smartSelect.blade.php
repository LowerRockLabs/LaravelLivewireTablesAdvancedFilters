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
        selectedItems: {{ json_encode($selectedItems) }},
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
            this.checkedItems = Array.from(new Set(this.selectedItems));
            this.checkedItems.push(itemID);
            this.currentFilteredList = []
            $refs.smartSelectSearchBox.value = '';
            $wire.set('{{ $filterKey }}', this.checkedItems);
    
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
                        <li class="w-full flex text-gray-700 px-2 mt-2 text-black dark:text-white bg-white hover:bg-sky-700 dark:bg-darker hover:dark:bg-sky-700"
                            x-on:click="pushToSelected(filteredItem.id)">
                            <span>T</span>
                            <span x-text="filteredItem.name"></span>
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
