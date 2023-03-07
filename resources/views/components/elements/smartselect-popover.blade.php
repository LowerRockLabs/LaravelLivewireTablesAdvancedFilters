@props(['iconStyling', 'theme'])

@if ($theme == 'tailwind')
    <div x-show="popOpen" x-transition @click.outside="popOpen = false" :class="{ 'pb-2': popOpen }"
        class="absolute origin-top-right md:left-52 right-0 md:top-0 w-11/12 md:w-56 rounded-md shadow-lg bg-white dark:bg-gray-700 text-black dark:text-white ring-1 ring-black ring-opacity-5 focus:outline-none"
        tabindex="-1">
        <div class="pt-1 z-50">

            <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
            <div class="w-full">
                <span class="inline-block pt-2 pl-2">
                    <h2><strong>{{ __('Selected Items') }}</strong></h2>
                </span>
                <x-livewiretablesadvancedfilters::buttons.popover-close :theme="$theme" />
            </div>

            <div class="w-full flex flex-col">
                <template x-for="(item,index) in newSelectedItems">
                    <div class="w-full pl-2 py-1 text-sm hover:bg-blue-500 dark:hover:bg-gray-400"
                        :class="{ 'dark:bg-gray-800': (index % 2) }">
                        <a href="#" class="inline-block w-full" x-on:click="removeSelectedItem(item.id)">

                            <x-livewiretablesadvancedfilters::icons.smartselect-removeIcon :theme="$theme" />

                            <template x-if="displayIdEnabled">

                                (<span x-text="item.id"></span>)
                            </template>
                            <span x-text="item.name"></span>
                        </a>
                    </div>
                </template>
            </div>
        </div>
    </div>
@elseif ($theme == 'bootstrap-4')
    <div x-show="popOpen" x-transition @click.outside="popOpen = false" :class="{ 'pb-2': popOpen }"
        class="position-absolute pb-2 origin-top-right md:left-64 right-0 md:top-0 md:w-56 rounded-md shadow-lg bg-white dark:bg-gray-700 text-black dark:text-white ring-1 ring-black ring-opacity-5 focus:outline-none smartSelectExistingPopOverElement"
        tabindex="-1">
        <div class="pt-1 z-50">
            <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
            <div class="d-flex align-items-start ml-auto flex-row flex-nowrap">
                <div class="align-self-start mb-auto">
                    <h2><strong>{{ __('Selected Items') }}</strong></h2>
                </div>
                <div class="align-self-end text-right mb-auto p-2">
                    <x-livewiretablesadvancedfilters::buttons.popover-close :theme="$theme" />
                </div>
            </div>

            <table class="smartSelectPopoverList table-striped w-100 table-hover">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(item,index) in newSelectedItems">
                        <tr x-on:click="removeSelectedItem(item.id)">
                            <td>
                                <a href="#" x-on:click="removeSelectedItem(item.id)">
                                    <x-livewiretablesadvancedfilters::icons.smartselect-removeIcon :theme="$theme" />
                                </a>
                            </td>
                            <td>
                                <a href="#" x-on:click="removeSelectedItem(item.id)">
                                    <template x-if="displayIdEnabled">
                                        (<span x-text="item.id"></span>)
                                    </template>
                                    <span x-text="item.name"></span>
                                </a>

                            </td>
                        </tr>

                    </template>
                </tbody>
            </table>
        </div>
    </div>
@elseif ($theme == 'bootstrap-5')
    <div x-show="popOpen" x-transition @click.outside="popOpen = false" :class="{ 'pb-2': popOpen }"
        class="position-absolute pb-2 origin-top-right md:left-64 right-0 md:top-0 md:w-56 rounded-md shadow-lg bg-white dark:bg-gray-700 text-black dark:text-white ring-1 ring-black ring-opacity-5 focus:outline-none smartSelectExistingPopOverElement"
        tabindex="-1">
        <div class="pt-1 z-50">
            <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
            <div class="d-flex align-items-start ml-auto flex-row flex-nowrap">
                <div class="align-self-start mb-auto">
                    <h2><strong>{{ __('Selected Items') }}</strong></h2>
                </div>
                <div class="align-self-end text-right mb-auto p-2">
                    <x-livewiretablesadvancedfilters::buttons.popover-close :theme="$theme" />
                </div>
            </div>

            <table class="smartSelectPopoverList table-striped w-100 table-hover">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(item,index) in newSelectedItems">
                        <tr x-on:click="removeSelectedItem(item.id)">
                            <td>
                                <a href="#" x-on:click="removeSelectedItem(item.id)">
                                    <x-livewiretablesadvancedfilters::icons.smartselect-removeIcon :theme="$theme" />
                                </a>
                            </td>
                            <td>
                                <a href="#" x-on:click="removeSelectedItem(item.id)">
                                    <template x-if="displayIdEnabled">
                                        (<span x-text="item.id"></span>)
                                    </template>
                                    <span x-text="item.name"></span>
                                </a>

                            </td>
                        </tr>

                    </template>
                </tbody>
            </table>
        </div>
    </div>
@endif
