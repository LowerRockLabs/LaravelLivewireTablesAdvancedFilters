@php
    $tableName = $component->getTableName();
    $filterKey = $filter->getKey();
    $filterLabelPath = $tableName . '-filter-' . $filterKey;
    $filterBasePath = $tableName . '.filters.' . $filterKey;
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
    newSelectedItems: $wire.entangle('{{ $selectedWireKey }}'),
    allFilters: $wire.entangle('{{ $tableName }}.filters'),
    optionsMethod: '{{ $optionsMethod }}',
    displayIdEnabled: {{ $displayIdEnabled }},
    currentFilteredList: [],
    presentlySelectedList: [],
    filteredList: [],
    nameMapping: [],
    popOpen: false,
    selectedItems: $wire.entangle('{{ $filterBasePath }}'),
    swapLabels() {
        if (document.getElementById('{{ $filterLabelPath }}-label') === null) {
            document.getElementById('smartSelectContainer{{ $filterKey }}').parentElement.firstElementChild.classList.add('hidden');
            document.getElementById('smartSelectContainer{{ $filterKey }}').parentElement.firstElementChild.classList.add('d-none');

        } else {
            document.getElementById('{{ $filterLabelPath }}-label').classList.add('hidden');
            document.getElementById('{{ $filterLabelPath }}-label').classList.add('d-none');

        }
        document.getElementById('{{ $filterLabelPath }}-labelInternal').classList.remove('hidden');
        document.getElementById('{{ $filterLabelPath }}-labelInternal').classList.remove('d-none');
    },
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
        this.swapLabels();
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
        this.swapLabels();
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
        this.swapLabels();

    },
    init() {
        this.swapLabels();
        this.currentFilteredList = [];
        var testObject = Object.entries({{ json_encode($filter->getOptions()) }}).map((entry) => this.filteredList[entry[0]] = entry[1]);
        $watch('newSelectedItems', value => this.swapLabels());
        $watch('open', value => this.swapLabels());
        $watch('allFilters', value => this.swapLabels());
    },
}">
    @if ($theme === 'tailwind')
        <div class="absolute" class="h-16">
            <div class="relative">
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
                    class="w-full rounded-md relative inline-flex place-items-end justify-items-end items-end pr-2"
                    style="top: -3.3em" role="menu" aria-orientation="vertical" aria-labelledby="menu-button">
                    <x-livewiretablesadvancedfilters::elements.smartselect-popover :theme="$theme"
                        :iconStyling="$iconStyling" />
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
                                        <x-livewiretablesadvancedfilters::elements.smartselect-item-rem
                                            :iconStyling="$iconStyling" :theme="$theme" />
                                    </template>
                                    <template x-if="selectedItems.indexOf(filteredItem.id.toString()) < 0">
                                        <x-livewiretablesadvancedfilters::elements.smartselect-item-add
                                            :iconStyling="$iconStyling" :theme="$theme" />
                                    </template>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>
                <!-- End Drop Down -->
            </div>
        </div>
    @elseif ($theme === 'bootstrap-4')
        <div class="position-relative">
            <!-- Start Label Replacement -->
            <div class="d-flex flex-column align-items-start w-100 hidden" id="{{ $filterLabelPath }}-labelInternal">
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
                class="w-100 rounded-md position-relative d-inline-flex place-items-end justify-items-end items-end pr-2"
                style="top: -3.3em" role="menu" aria-orientation="vertical" aria-labelledby="menu-button">
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
        <div class="position-absolute">


            <div class="position-relative">

                <!-- Start Label Replacement -->
                <div class="d-flex flex-cols w-100 hidden h-8 pr-4" id="{{ $filterLabelPath }}-labelInternal">
                    <label for="{{ $filterLabelPath }}"
                        class="d-inline-block w-11/12 text-sm font-medium leading-5 text-gray-700 dark:text-white ">
                        {{ $filter->getName() }}
                    </label>
                    <div class="d-inline-block w-1/12">
                        <x-livewiretablesadvancedfilters::buttons.popover-open :theme="$theme" />
                    </div>
                </div>
                <!-- End Label Replacement -->


                <!-- Start Existing Pop-Over -->
                <div id="existingPopover" x-cloak
                    class="w-100 rounded-md position-relative d-inline-flex place-items-end justify-items-end items-end pr-2"
                    style="top: -3.3em" role="menu" aria-orientation="vertical" aria-labelledby="menu-button">
                    <x-livewiretablesadvancedfilters::elements.smartselect-popover :theme="$theme"
                        :iconStyling="$iconStyling" />
                </div>
                <!-- Stop Existing Pop-Over -->

                <!-- Start Drop Down -->
                <div class=" w-100 d-flex flex-col items-center justify-center text-black dark:text-white pr-4 ">
                    <x-livewiretablesadvancedfilters::forms.smartselect-textinput :theme="$theme"
                        :xRefKey="$xRefKey" />

                    <div :class="{
                        'border-solid border-2 rounded-md border-gray-300 dark:border-gray-600': currentFilteredList
                            .length > 0
                    }"
                        class="flex-col w-100 overflow-visible z-50">
                        <ul class="smartSelectDropDownList bg-white dark:bg-gray-700 flex-col w-100 ">
                            <template x-for="(filteredItem, index) in currentFilteredList" :key="filteredItem.id">
                                <li class="px-2 py-1 hover:bg-blue-500 dark:hover:bg-gray-400"
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
        </div>
        <style>
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
