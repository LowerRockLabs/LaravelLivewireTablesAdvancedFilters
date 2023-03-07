@props(['theme', 'iconStyling', 'linkClass' => ''])
@if ($theme == 'tailwind')
    <a class="cursor-pointer  inline-block w-full" x-on:click="removeSelectedItem(filteredItem.id.toString())">
        @if ($iconStyling['delete']['svgEnabled'])
            <x-livewiretablesadvancedfilters::icons.smartselect-removeIcon :theme="$theme" />
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
    <a class="cursor-pointer  d-inline-block w-100 {{ $linkClass }}"
        x-on:click="removeSelectedItem(filteredItem.id.toString())">
        @if ($iconStyling['delete']['svgEnabled'])
            <x-livewiretablesadvancedfilters::icons.smartselect-removeIcon :theme="$theme" />
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
