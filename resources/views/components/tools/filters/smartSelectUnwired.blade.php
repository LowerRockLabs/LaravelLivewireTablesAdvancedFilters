@php
    $tableName = $component->getTableName();
    $filterKey = $filter->getKey();
    $filterLabelPath = $tableName . '-filter-' . $filterKey;
    $filterBasePath = $tableName . '.filters.' . $filterKey;
    $filterMenuLabel = '[aria-labelledby="filters-menu"]';
    $filterName = $filter->getName();
    $filterConfigs = $filter->getConfigs();

    $wireKey = $tableName . '.filters.' . $filterKey;
    $selectedWireKey = 'filterData.' . $filterKey;
    $xRefKey = 'smartSelectSearchBox' . $filterKey;

    $iconStyling = $filterConfigs['iconStyling'];
    $listStyling = $filterConfigs['listStyling'];
    $displayIdEnabled = $filterConfigs['displayIdEnabled'] ?? 'false';
    $optionsMethod = $filterConfigs['optionsMethod'];
@endphp

<div id="smartSelectContainer{{ $filterKey }}" x-data="{
    allFilters: $wire.entangle('{{ $tableName }}.filters'),
    twMenuElements: document.getElementsByClassName('relative block md:inline-block text-left'),
    bsMenuElements: document.getElementsByClassName('btn-group d-block d-md-inline'),
    setupFilterMenu() {
        if (document.querySelector('{{ $filterMenuLabel }}') !== null) {
            document.querySelector('{{ $filterMenuLabel }}').classList.add('md:w-80');
            document.querySelector('{{ $filterMenuLabel }}').classList.remove('md:w-56');
        }

        if (document.getElementById('{{ $filterLabelPath }}-label') === null) {
            if (document.getElementById('smartSelectContainer{{ $filterKey }}').parentElement.firstElementChild !== null) {
                document.getElementById('smartSelectContainer{{ $filterKey }}').parentElement.firstElementChild.classList.add('hidden');
                document.getElementById('smartSelectContainer{{ $filterKey }}').parentElement.firstElementChild.classList.add('d-none');
            }
        } else {
            document.getElementById('{{ $filterLabelPath }}-label').classList.add('hidden');
            document.getElementById('{{ $filterLabelPath }}-label').classList.add('d-none');
        }

        if (document.getElementById('{{ $filterLabelPath }}-labelInternal') !== null) {
            document.getElementById('{{ $filterLabelPath }}-labelInternal').classList.remove('hidden');
            document.getElementById('{{ $filterLabelPath }}-labelInternal').classList.remove('d-none');
        }
        for (let i = 0; i < this.twMenuElements.length; i++) {
            if (this.twMenuElements.item(i).getAttribute('x-data') != '{ open: true, childElementOpen: true  }') {
                this.twMenuElements.item(i).setAttribute('x-data', '{ open: true, childElementOpen: true  }');
                this.twMenuElements.item(i).setAttribute('x-on:mousedown.away', 'if (!childElementOpen) { open = false }');
            }
        }
        for (let i = 0; i < this.bsMenuElements.length; i++) {
            if (this.bsMenuElements.item(i).getAttribute('x-data') != '{ open: true, childElementOpen: true  }') {
                this.bsMenuElements.item(i).setAttribute('x-data', '{ open: true, childElementOpen: true  }');
                this.bsMenuElements.item(i).setAttribute('x-on:mousedown.away', 'if (!childElementOpen) { open = false }');
            }
        }
    },
    optionsMethod: '{{ $optionsMethod }}',
    displayIdEnabled: {{ $displayIdEnabled }},
    currentFilteredList: [],
    filteredList: [],
    popOpen: false,
    selectedItems: $wire.entangle('{{ $filterBasePath }}'),
    resetCurrentFilteredList() {
        $refs.{{ $xRefKey }}.value = '';
        this.currentFilteredList = [];
    },
    updateCurrentFilteredList() {
        currentlyFilteredObject = [];
        if ($refs.{{ $xRefKey }}.value != '') {
            this.filteredList.filter(function(elem, index) {
                if (elem.name.toString().toLowerCase().includes($refs.{{ $xRefKey }}.value.toLowerCase())) {
                    currentlyFilteredObject.push({ 'id': index, 'name': elem.name.toString() });
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
            this.currentFilteredList = [];
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
        this.currentFilteredList = [];
    },
    init() {
        this.setupFilterMenu();
        this.currentFilteredList = [];
        var testObject = Object.entries({{ json_encode($filter->getOptions()) }}).map((entry) => this.filteredList[entry[0]] = entry[1]);
        $watch('open', value => this.setupFilterMenu());
        $watch('allFilters', value => this.setupFilterMenu());
    },
}">
    @if ($theme === 'tailwind')
        <div class="relative" class="h-16">
            <!-- Start Label Replacement -->
            <div class="flex flex-cols w-full hidden h-8 pr-4" id="{{ $filterLabelPath }}-labelInternal">
                <label for="{{ $filterLabelPath }}"
                    class="inline-block w-11/12 text-sm font-medium leading-5 text-gray-700 dark:text-white ">
                    {{ $filter->getName() }}
                </label>
                <div class="inline-block w-1/12">
                    <x-livewiretablesadvancedfilters::buttons.popover-open :theme="$theme" />
                </div>
            </div>
            <!-- End Label Replacement -->

            <!-- Start Existing Pop-Over -->
            <div x-cloak
                class="w-full rounded-md relative inline-flex place-items-end justify-items-end items-end pr-2 smartSelectExistingPopOverWrapper"
                role="menu" aria-orientation="vertical" aria-labelledby="menu-button">
                <x-livewiretablesadvancedfilters::elements.smartselect-popover :theme="$theme" :iconStyling="$iconStyling" />
            </div>
            <!-- Stop Existing Pop-Over -->

            <!-- Start Drop Down -->
            <div class=" w-full flex flex-col items-center justify-center text-black dark:text-white pr-4 ">
                <x-livewiretablesadvancedfilters::forms.smartselect-textinput :theme="$theme" :xRefKey="$xRefKey" />

                <div :class="{
                    'border-solid border-2 rounded-md border-gray-300 dark:border-gray-600': currentFilteredList
                        .length > 0
                }"
                    class="flex-col w-full overflow-visible z-50">
                    <ul class="bg-white dark:bg-gray-700 flex-col w-full ">
                        <template x-for="(filteredItem, index) in currentFilteredList" :key="filteredItem.id">
                            <li class="px-2 py-1 hover:bg-blue-500 dark:hover:bg-gray-400"
                                :class="{ 'dark:bg-gray-800': (index % 2) }">
                                <template x-if="selectedItems.indexOf(filteredItem.id.toString()) > -1">
                                    <x-livewiretablesadvancedfilters::elements.smartselect-item-rem :iconStyling="$iconStyling"
                                        :theme="$theme" />
                                </template>
                                <template x-if="selectedItems.indexOf(filteredItem.id.toString()) < 0">
                                    <x-livewiretablesadvancedfilters::elements.smartselect-item-add :iconStyling="$iconStyling"
                                        :theme="$theme" />
                                </template>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
            <!-- End Drop Down -->
        </div>
    @elseif ($theme === 'bootstrap-4')
        <div class="position-relative">
            <!-- Start Label Replacement -->
            <div class="d-flex flex-column align-items-start w-100 d-none" id="{{ $filterLabelPath }}-labelInternal">
                <label for="{{ $filterLabelPath }}" class="d-inline small leading-5 text-gray-700 dark:text-white ">
                    {{ $filter->getName() }}
                </label>
                <div class="d-inline align-self-end text-right position-absolute">
                    <x-livewiretablesadvancedfilters::buttons.popover-open :theme="$theme" />
                </div>
            </div>
            <!-- End Label Replacement -->



            <!-- Start Existing Pop-Over -->
            <div x-cloak id="existingPopover"
                class="w-100 rounded-md position-relative d-inline-flex place-items-end justify-items-end items-end pr-2 smartSelectExistingPopOverWrapper"
                role="menu" aria-orientation="vertical" aria-labelledby="menu-button">
                <x-livewiretablesadvancedfilters::elements.smartselect-popover :theme="$theme" :iconStyling="$iconStyling" />
            </div>
            <!-- Stop Existing Pop-Over -->

            <!-- Start Drop Down -->
            <div class=" w-100 d-flex flex-column items-center justify-center text-black dark:text-white pr-4 ">
                <x-livewiretablesadvancedfilters::forms.smartselect-textinput :theme="$theme" :xRefKey="$xRefKey" />

                <div :class="{
                    'border-solid border-2 rounded-md border-gray-300 dark:border-gray-600': currentFilteredList
                        .length > 0
                }"
                    class="flex-column w-100 overflow-visible z-50">
                    <ul class="smartSelectDropDownList list-group bg-white dark:bg-gray-700 flex-column w-100 ">
                        <template x-for="(filteredItem, index) in currentFilteredList" :key="filteredItem.id">
                            <li class="list-group-item px-2 py-1"
                                :class="{ 'bg-light': (index % 2), 'bg-secondary': !(index % 2) }">
                                <template x-if="selectedItems.indexOf(filteredItem.id.toString()) > -1">
                                    <x-livewiretablesadvancedfilters::elements.smartselect-item-rem :iconStyling="$iconStyling"
                                        :theme="$theme" />
                                </template>
                                <template x-if="selectedItems.indexOf(filteredItem.id.toString()) < 0">

                                    <x-livewiretablesadvancedfilters::elements.smartselect-item-add :iconStyling="$iconStyling"
                                        :theme="$theme" />


                                </template>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
            <!-- End Drop Down -->
        </div>
        <style>
            .smartSelectExistingPopOverWrapper {
                top: -3.3em;
            }

            .smartSelectExistingPopOverElement {
                left: 16em;
                width: 18em;
            }

            .filterCalendarIcon {
                right: 0.5em;
                top: 0.25em;
            }

            /*
            ul.smartSelectDropDownList li {
                background-color: #FFFFFF;
                cursor: pointer;
            }

            /* bg-gray-700
            .dark ul.smartSelectDropDownList li {
                background-color: rgb(55 65 81);
            }
            /* bg-blue-500
            ul.smartSelectDropDownList li:hover {
                background-color: rgb(59 130 246);
            }

            /* bg-gray-400
            .dark ul.smartSelectDropDownList li:hover {
                background-color: rgb(156 163 175);
            }
            */
            .bg-blue-500 {
                background-color: rgb(59 130 246);
            }

            .bg-gray-400 {
                background-color: rgb(156 163 175);
            }

            .bg-gray-700 {
                background-color: rgb(55 65 81);
            }

            .bg-gray-800 {
                background-color: rgb(31 41 55);
            }
        </style>
    @elseif ($theme === 'bootstrap-5')
        <div class="position-relative">

            <!-- Start Label Replacement -->
            <div class="d-flex flex-column w-100 d-none h-8 pr-4" id="{{ $filterLabelPath }}-labelInternal">
                <label for="{{ $filterLabelPath }}"
                    class="d-inline-block w-11/12 small leading-5 text-gray-700 dark:text-white ">
                    {{ $filter->getName() }}
                </label>
                <div class="d-inline-block w-1/12">
                    <x-livewiretablesadvancedfilters::buttons.popover-open :theme="$theme" />
                </div>
            </div>
            <!-- End Label Replacement -->


            <!-- Start Existing Pop-Over -->
            <div id="existingPopover" x-cloak
                class="w-100 rounded-md position-relative d-inline-flex place-items-end justify-items-end items-end pr-2 smartSelectExistingPopOverWrapper"
                role="menu" aria-orientation="vertical" aria-labelledby="menu-button">
                <x-livewiretablesadvancedfilters::elements.smartselect-popover :theme="$theme" :iconStyling="$iconStyling" />
            </div>
            <!-- Stop Existing Pop-Over -->

            <!-- Start Drop Down -->
            <div class=" w-100 d-flex flex-column items-center justify-center text-black dark:text-white pr-4 ">
                <x-livewiretablesadvancedfilters::forms.smartselect-textinput :theme="$theme" :xRefKey="$xRefKey" />

                <div :class="{
                    'border-solid border-2 rounded-md border-gray-300 dark:border-gray-600': currentFilteredList
                        .length > 0
                }"
                    class="flex-column w-100 overflow-visible z-50">
                    <ul class="smartSelectDropDownList list-group bg-white dark:bg-gray-700 flex-column w-100 ">
                        <template x-for="(filteredItem, index) in currentFilteredList" :key="filteredItem.id">
                            <li class="list-group-item px-2 py-1 hover:bg-blue-500 dark:hover:bg-gray-400"
                                :class="{ 'dark:bg-gray-800': (index % 2) }">
                                <a href='#'>
                                    <template x-if="selectedItems.indexOf(filteredItem.id.toString()) > -1">
                                        <x-livewiretablesadvancedfilters::elements.smartselect-item-rem
                                            :iconStyling="$iconStyling" :theme="$theme" />
                                    </template>
                                    <template x-if="selectedItems.indexOf(filteredItem.id.toString()) < 0">
                                        <x-livewiretablesadvancedfilters::elements.smartselect-item-add
                                            :iconStyling="$iconStyling" :theme="$theme" />
                                    </template>
                                </a>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
            <!-- End Drop Down -->
        </div>
        <style>
            .smartSelectExistingPopOverWrapper {
                top: -3.3em;
            }

            .smartSelectExistingPopOverElement {
                left: 16em;
                width: 18em;
            }

            .filterCalendarIcon {
                right: 0.5em;
                top: 0.25em;
            }


            div.smartSelectPopoverList div:hover {
                background-color: rgb(59 130 246);
            }

            .dark div.smartSelectPopoverList div:hover {
                background-color: rgb(156 163 175);
            }

            ul.smartSelectDropDownList li {
                background-color: #FFFFFF;
                cursor: pointer;
            }

            /* bg-gray-700 */
            .dark ul.smartSelectDropDownList li {
                background-color: rgb(55 65 81);
            }

            /* bg-blue-500 */
            ul.smartSelectDropDownList li:hover {
                background-color: rgb(59 130 246);
            }

            /* bg-gray-400 */
            .dark ul.smartSelectDropDownList li:hover {
                background-color: rgb(156 163 175);
            }

            .bg-blue-500 {
                background-color: rgb(59 130 246);
            }

            .bg-gray-400 {
                background-color: rgb(156 163 175);
            }

            .bg-gray-700 {
                background-color: rgb(55 65 81);
            }

            .bg-gray-800 {
                background-color: rgb(31 41 55);
            }
        </style>
    @endif

</div>
