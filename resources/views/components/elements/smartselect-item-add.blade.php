@props(['theme', 'iconStyling', 'displayHtmlName'])
@if ($theme == 'tailwind')
    <a class="cursor-pointer inline-block w-full" x-on:click="addSelectedItem(filteredItem.id.toString())">
        @if ($iconStyling['add']['svgEnabled'])
            <x-lrlAdvancedTableFilters::icons.smartselect-addIcon :theme="$theme" />
        @endif
        <span class="smartSelect-NameDisplay-Wrapper pl-1">
            <template x-if="displayIdEnabled">
                <span class="smartSelect-NameDisplay-ID">
                    &#40;<span x-text="filteredItem.id"></span>&#41;
                </span>
            </template>
            @if ($displayHtmlName)
                <span class="smartSelect-NameDisplay-Name" x-html="filteredItem.htmlName"></span>
            @else
                <span class="smartSelect-NameDisplay-Name" x-text="filteredItem.name"></span>
            @endif

        </span>
    </a>
@else
    <a class="cursor-pointer d-inline-block w-100" x-on:click="addSelectedItem(filteredItem.id.toString())">
        @if ($iconStyling['add']['svgEnabled'])
            <x-lrlAdvancedTableFilters::icons.smartselect-addIcon :theme="$theme" />
        @endif
        <span class="smartSelect-NameDisplay-Wrapper pl-1">
            <template x-if="displayIdEnabled">
                <span class="smartSelect-NameDisplay-ID">
                    &#40;<span x-text="filteredItem.id"></span>&#41;
                </span>
            </template>

            @if ($displayHtmlName)
                <span class="smartSelect-NameDisplay-Name" x-html="filteredItem.htmlName"></span>
            @else
                <span class="smartSelect-NameDisplay-Name" x-text="filteredItem.name"></span>
            @endif

        </span>
    </a>
@endif
