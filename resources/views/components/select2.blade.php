@props([
    'name',
    'id' => null,
    'options' => [],
    'selected' => null,
    'placeholder' => 'Select an option',
    'allowClear' => true,
    'multiple' => false,
    'searchable' => false,
    'route' => null,
    'theme' => 'default',
    'disabled' => false,
    'label',
    'required' => false,
])

@php
    $elementId = $id ?? $name . '_' . uniqid();

    $classes =
        [
            'default' => 'form-select',
            'transparent' => 'form-select form-select-transparent',
            'solid' => 'form-select form-select-solid',
        ][$theme] ?? 'form-select';

    $select2Options = [
        'data-control' => 'select2',
        'data-placeholder' => $placeholder,
        'data-allow-clear' => json_encode($allowClear),
    ];

    if ($searchable && $route) {
        $select2Options['data-ajax--url'] = $route;
        $select2Options['data-ajax--cache'] = 'true';
        $select2Options['data-minimum-input-length'] = '1';
    }
@endphp

@isset($label)
    <label for="{{ $name }}" class="form-label fw-semibold">
        {{ $label }}
        @if (!empty($required))
            <span class="text-danger">*</span>
        @endif
    </label>
@endisset

<select name="{{ $name }}" id="{{ $elementId }}" class="{{ $classes }}"
    @foreach ($select2Options as $key => $value)
        {{ $key }}="{{ $value }}" @endforeach
    @if ($multiple) multiple @endif @if ($disabled) disabled @endif>
    <option></option>
    @if (!$searchable)
        @foreach ($options as $key => $value)
            <option value="{{ $key }}" @selected($key == $selected)>{{ $value }}</option>
        @endforeach
    @elseif($selected)
        <option value="{{ $selected }}" selected></option>
    @endif
</select>

@push('scripts')
    <script>
        $(document).ready(function() {
            if (typeof $.fn.select2 === 'undefined') {
                console.error('Select2 is not loaded');
                return;
            }

            const selectElement = $('#{{ $elementId }}');
            const isInModal = selectElement.closest('.modal').length > 0;

            const select2Config = {
                @if ($searchable && $route)
                    ajax: {
                        url: '{{ $route }}',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                search: params.term
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.map(function(item) {
                                    return {
                                        id: item.id,
                                        text: item.name || item.text
                                    };
                                })
                            };
                        },
                        cache: true
                    },
                @endif
                placeholder: '{{ $placeholder }}',
                allowClear: {{ $allowClear ? 'true' : 'false' }},
                minimumInputLength: {{ $searchable ? '1' : '0' }},
                width: '100%',
                dropdownParent: isInModal ? selectElement.closest('.modal') : $(document.body)
            };

            selectElement.select2(select2Config);

            @if ($searchable && $selected)
                if (selectElement.val() && !selectElement.find('option[value="' + selectElement.val() + '"]')
                    .length) {
                    $.ajax({
                        url: '{{ $route }}',
                        data: {
                            id: selectElement.val()
                        },
                        dataType: 'json'
                    }).done(function(data) {
                        if (data && data.name) {
                            const option = new Option(data.name, data.id, true, true);
                            selectElement.append(option).trigger('change');
                        }
                    }).fail(function() {
                        console.error('Failed to load selected option');
                    });
                }
            @endif
        });
    </script>
@endpush
