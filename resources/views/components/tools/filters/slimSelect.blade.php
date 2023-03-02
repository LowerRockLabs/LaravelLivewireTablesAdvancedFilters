@php
    $theme = $component->getTheme();
    $options = [];
    $selectedValues = [];
    $selectedValues = $this->{$component->getTableName()}['filters'][$filter->getKey()];
    
    foreach ($filter->getOptions() as $idx => $val) {
        if (in_array($val['id'], $selectedValues) || in_array(intval($val['id']), $selectedValues)) {
            $options[] = ['tmp' => $idx, 'value' => $val['id'], 'text' => $val['name'], 'selected' => true];
        } else {
            $options[] = ['tmp' => $idx, 'value' => $val['id'], 'text' => $val['name'], 'selected' => false];
        }
    }
    
@endphp

@pushOnce('scripts')
    <script src="https://unpkg.com/slim-select@latest/dist/slimselect.min.js"></script>
    <link href="https://unpkg.com/slim-select@latest/dist/slimselect.css" rel="stylesheet" />
@endPushOnce

@if ($theme === 'tailwind')
    <div class="rounded-md shadow-sm" wire:ignore x-data="{
        slimSelect: [],
        bootSlimSelect() {
            this.slimSelect = new SlimSelect({
                select: '#test123',
                settings: {
                    placeholderText: 'Select Values',
                    allowDeselect: true
                },
                events: {
                    afterChange: (newVal) => {
                        if (this.slimSelect.getSelected().length > 0) {
                            $wire.set('{{ $component->getTableName() }}.filters.{{ $filter->getKey() }}', this.slimSelect.getSelected());
                        }
                        console.log(this.slimSelect.getData());
                    }
                }
            });
        },
        updateSlimSelect() {
            if (this.slimSelect.length == 0) {
                this.bootSlimSelect();
                this.slimSelect.setData({{ json_encode($options) }});
    
            } else {
                this.slimSelect.setData({{ json_encode($options) }});
            }
            console.log(this.slimSelect.getData());
        },
        init() {
            this.updateSlimSelect()
        }
    }">
        <select multiple id='test123'>
            <option data-placeholder="true"></option>
        </select>
    </div>
@elseif ($theme === 'bootstrap-4' || $theme === 'bootstrap-5')
    <select multiple wire:model.stop="{{ $component->getTableName() }}.filters.{{ $filter->getKey() }}"
        wire:key="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
        id="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
        class="{{ $theme === 'bootstrap-4' ? 'form-control' : 'form-select' }}">
        @if ($filter->getFirstOption() != '')
            <option @if ($filter->isEmpty($this)) selected @endif value="all">{{ $filter->getFirstOption() }}
            </option>
        @endif
        @foreach ($filter->getOptions() as $key => $value)
            @if (is_iterable($value))
                <optgroup label="{{ $key }}">
                    @foreach ($value as $optionKey => $optionValue)
                        <option value="{{ $optionKey }}">{{ $optionValue }}</option>
                    @endforeach
                </optgroup>
            @else
                <option value="{{ $key }}">{{ $value }}</option>
            @endif
        @endforeach
    </select>
@endif
