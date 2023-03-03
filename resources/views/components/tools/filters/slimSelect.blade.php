@php
    $theme = $component->getTheme();
    $tableName = $component->getTableName();
    $filterKey = $filter->getKey();
    $options = [];
    $empty = [];
    $empty[] = ['text' => '', 'value' => ''];
    $options = array_merge($empty, $filter->getOptions());
@endphp

@pushOnce('scripts')
    <script src="https://unpkg.com/slim-select@latest/dist/slimselect.min.js"></script>
    <link href="https://unpkg.com/slim-select@latest/dist/slimselect.css" rel="stylesheet" />
@endPushOnce

<div>
    @if ($theme === 'tailwind')
        <label for="{{ $tableName }}-filter-{{ $filterKey }}"
            class="block text-sm font-medium leading-5 text-gray-700 dark:text-white">
            {{ $filter->getName() }}
        </label>
        <div wire:ignore wire:key>
            <div wire:key class="rounded-md shadow-sm" x-data="{
                slimSelect: [],
                booting: true,
                bootSlimSelect() {
                    this.slimSelect = new SlimSelect({
                        select: '#test123',
                        settings: {
                            placeholderText: 'Select Values',
                            allowDeselect: true
                        },
                        events: {
                            afterChange: (newVal) => {
                                if (!this.booting) {
                                    if (this.slimSelect.getSelected().length > 0) {
                                        $wire.set('{{ $tableName }}.filters.{{ $filterKey }}', this.slimSelect.getSelected());
                                    }
                                } else {
                                    this.booting = false;
                                }
            
                            }
                        }
                    });
                },
                updateSlimSelect() {
                    if (this.slimSelect.length == 0) {
                        this.bootSlimSelect();
                        this.slimSelect.setData({{ json_encode($filter->getOptions()) }});
                    } else {
                        this.slimSelect.setData({{ json_encode($filter->getOptions()) }});
                    }
            
                },
                init() {
                    this.booting = true;
                    this.updateSlimSelect()
                }
            }">

            </div>
            <div wire:key>
                <select multiple id='test123' wire:key>
                    <option data-placeholder="true"></option>
                </select>
            </div>
        </div>
    @elseif ($theme === 'bootstrap-4' || $theme === 'bootstrap-5')
        <label for="{{ $tableName }}-filter-{{ $filterKey }}"
            class="block text-sm font-medium leading-5 text-gray-700 dark:text-white">
            {{ $filter->getName() }}
        </label>
        <div wire:ignore wire:key>
            <div wire:key class="rounded-md shadow-sm" x-data="{
                slimSelect: [],
                booting: true,
                bootSlimSelect() {
                    this.slimSelect = new SlimSelect({
                        select: '#test123',
                        settings: {
                            placeholderText: 'Select Values',
                            allowDeselect: true
                        },
                        events: {
                            afterChange: (newVal) => {
                                if (!this.booting) {
                                    if (this.slimSelect.getSelected().length > 0) {
                                        $wire.set('{{ $tableName }}.filters.{{ $filterKey }}', this.slimSelect.getSelected());
                                    }
                                } else {
                                    this.booting = false;
                                }
            
                            }
                        }
                    });
                },
                updateSlimSelect() {
                    if (this.slimSelect.length == 0) {
                        this.bootSlimSelect();
                        this.slimSelect.setData({{ json_encode($filter->getOptions()) }});
                    } else {
                        this.slimSelect.setData({{ json_encode($filter->getOptions()) }});
                    }
            
                },
                init() {
                    this.booting = true;
                    this.updateSlimSelect()
                }
            }">

            </div>
            <div wire:key>
                <select multiple id='test123' wire:key
                    class="{{ $theme === 'bootstrap-4' ? 'form-control' : 'form-select' }}">
                    <option data-placeholder="true"></option>
                </select>
            </div>
        </div>
    @endif
</div>
