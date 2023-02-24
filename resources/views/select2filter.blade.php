@php
    $theme = $component->getTheme();
    $filterKey = $component->getTableName() . '.filters.' . $filter->getKey();
@endphp

@if ($theme === 'tailwind')
    <div class="h-12 -mr-4 relative" x-data="{
        search: '',
        open: false,
        checkedItems: [],
        selectedItems: $wire.get('{{ $component->getTableName() }}.filters.{{ $filter->getKey() }}'),
        items: @js($filter->getOptions()),
        get filteredItems() {
            filteredList = this.items;
            return filteredList.filter(
                i => i.name.toLowerCase().startsWith(this.search.toLowerCase())
            )
        },
        pushToSelected(itemID) {
            this.checkedItems.push(itemID);
            this.checkedItems = Array.from(new Set(this.checkedItems));
    
        },
        updateLivewire() {
            this.open = false;
            this.checkedItems = Array.from(new Set(this.checkedItems));
            this.checkedItems.sort();
    
            tmp = $wire.get('{{ $component->getTableName() }}.filters.{{ $filter->getKey() }}');
            if (tmp != this.checkedItems) {
                $wire.set('{{ $component->getTableName() }}.filters.{{ $filter->getKey() }}', this.checkedItems);
            }
        },
    }" @click.outside="updateLivewire()">
        <div class=" w-full flex flex-col items-center justify-center">
            <input x-on:keydown="open = true" type="search" x-model="search" placeholder="Search Here..."
                class=" w-full mr-4	border-gray-300 rounded-md dark:bg-gray-800 dark:text-white dark:border-gray-600">
            <div class="flex-col w-full pr-4 ">

                <ul x-show="open" class="bg-white dark:bg-darker flex-col w-full z-50">
                    <template x-for="filteredItem in filteredItems" :key="filteredItem.id">
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
