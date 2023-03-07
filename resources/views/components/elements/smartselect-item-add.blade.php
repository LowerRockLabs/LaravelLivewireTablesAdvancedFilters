@props(['theme', 'iconStyling'])
@if ($theme == 'tailwind')
    <a class="cursor-pointer inline-block w-full" x-on:click="addSelectedItem(filteredItem.id.toString())">
        @if ($iconStyling['add']['svgEnabled'])
            <x-livewiretablesadvancedfilters::icons.smartselect-addIcon :theme="$theme" />
        @endif
        <span class="smartSelect-NameDisplay-Wrapper pl-1">
            <template x-if="displayIdEnabled">
                <span class="smartSelect-NameDisplay-ID">
                    &#40;<span x-text="filteredItem.id"></span>&#41;
                </span>
            </template>
            <span class="smartSelect-NameDisplay-Name" x-text="filteredItem.name"></span>
        </span>
    </a>
@else
    <a class="cursor-pointer d-inline-block w-100" x-on:click="addSelectedItem(filteredItem.id.toString())">
        @if ($iconStyling['add']['svgEnabled'])
            <x-livewiretablesadvancedfilters::icons.smartselect-addIcon :theme="$theme" />
        @endif
        <span class="smartSelect-NameDisplay-Wrapper pl-1">
            <template x-if="displayIdEnabled">
                <span class="smartSelect-NameDisplay-ID">
                    &#40;<span x-text="filteredItem.id"></span>&#41;
                </span>
            </template>
            <span class="smartSelect-NameDisplay-Name" x-text="filteredItem.name"></span>
        </span>
    </a>
@endif
